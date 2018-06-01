<?php
/**
* NOTICE OF LICENSE
*
*   This file is property of Petr Hucik. You may NOT redistribute the code in any way
*   See license.txt for the complete license agreement
*
* @author    Petr Hucik
* @website   https://www.getdatakick.com
* @copyright Petr Hucik <petr@getdatakick.com>
* @license   see license.txt
* @version   2.1.3
*/
namespace Datakick;

class AnalyzeDatasourceService extends Service {

  public function __construct() {
    parent::__construct('analyze-datasource');
  }

  public function process($factory, $request) {
    $manager = new ImportManager($factory);
    $sourceType = $this->getParameter('sourceType');
    $source = $this->getParameter('source');

    if (! in_array($sourceType, array('upload', 'url', 'file'))) {
      throw new UserError('Parameter sourceType has invalid value: '.$sourceType);
    }

    // check permissions
    $table = $factory->getServiceTable('import-datasource');
    $perm = $factory->getUser()->getPermissions()->checkCreate('importDatasources');

    // retrieve file
    $filename = $manager->fetchSource($sourceType, $source);
    $filetype = $this->getFileType($filename);

    $sha1 = sha1_file($filename);
    $sourceFile = ($sourceType === 'upload' ? null : $source);
    $id = $this->structureExists($factory, $manager, $sourceType, $sourceFile, $sha1, $filetype);
    if ($id === false) {
      $id = $this->findDatasource($factory, $sourceFile);
      $structure = $this->analyzeFile($filetype, $filename, $manager->shouldManageFile($sourceType));
      $data = array(
        'user_id' => $factory->getUser()->getId(),
        'filetype' => $filetype,
        'structure_sha1' => $sha1,
        'structure' => json_encode($structure->getData()),
        'source_type' => $sourceType,
        'source' => $sourceFile,
        'source_refreshed' => date("Y-m-d H:i:s")
      );
      $conn = $factory->getConnection();
      if ($id) {
        $conn->update($table, $data, array('id' => $id));
      } else {
        $data['name'] = self::deriveSourceName($source);
        $id = $conn->insert($table, $data);
      }
      $manager->stageFile($sourceType, $filename, $filetype, $id);
    }
    return (int)$id;
  }


  private function getFileType($filename) {
    // 1. try detect type
    $type = Asset::detectType($filename);
    if (strpos($type, 'xml') !== false) {
      return 'xml';
    }
    if (strpos($type, 'csv') !== false || strpos($type, 'ms-excel') !== false || strpos($type, 'separated-values') !== false) {
      return 'csv';
    }
    // TODO: try to detect by parsing
    return 'xml';
  }

  private function structureExists($factory, $manager, $sourceType, $source, $sha1, $filetype) {
    $recType = $factory->getRecord('importDatasources');
    $conditions = array(
      'sourceType' => $sourceType,
      'structureSha1' => $sha1
    );
    if ($source) {
      $conditions['source'] = $source;
    }
    $rec = $recType->loadBy($conditions, array('id'), array(), false);
    if ($rec) {
      $id = $rec['id'];
      $filename = $manager->getStagedFile($filetype, $id);
      if (@is_file($filename) && sha1_file($filename) == $sha1) {
        return $id;
      }
    }
    return false;
  }

  private function findDatasource($factory, $source) {
    if ($source) {
      $recType = $factory->getRecord('importDatasources');
      $rec = $recType->loadBy(array(
        'source' => $source,
      ), array('id'), array(), false);
      if ($rec) {
        return $rec['id'];
      }
    }
    return false;
  }

  private function analyzeFile($filetype, $filename, $deleteOnError) {
    try {
      if ($filetype == 'xml') {
        $progress = new Progress(true);
        $analyze = new XmlAnalyze();
        return $analyze->run($filename, $progress);
      }
      if ($filetype == 'csv') {
        throw new UserError('CSV import is not implemented yet');
      }
      throw new UserError('Unsupported file type: '.$filetype);
    } catch (\Exception $e) {
      if ($deleteOnError) {
        @unlink($filename);
      }
      throw $e;
    }
  }

  private static function deriveSourceName($path) {
    $name = null;
    if ($path) {
      $name = basename($path);
      if ($name) {
        $name = strtolower($name);
        $pos = strpos($name, '?');
        if ($pos > 0) {
          $name = substr($name, 0, $pos);
        }
        if (Utils::endsWith('.xml', $name) || Utils::endsWith('.csv', $name)) {
          $name = substr($name, 0, -4);
        }
        $name = Utils::toUppercaseWords($name);
      }
    }
    if (! $name) {
      return 'Unnamed datasource';
    }
    return $name;
  }


}
