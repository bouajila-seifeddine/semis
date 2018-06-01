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

class ImportManager {
  private $factory;

  public function __construct(Factory $factory) {
    $this->factory = $factory;
  }

  public function getMatchingImportDefinitions($structure) {
    $recType = $this->factory->getRecord('importDefinitions');
    $defs = $recType->loadRecords(array(), array('id', 'name', 'parsed'));
    $matching = array();
    $fileType = $structure->getFileType();
    foreach ($defs as $def) {
      $id = $def['id'];
      $name = $def['name'];
      try {
        $parsed = json_decode($def['parsed'], true);
        if ($parsed && isset($parsed['dataset']['type']) && $parsed['dataset']['type'] == $fileType) {
          if ($structure->satisfies($parsed['dataset']['requiredNodes'])) {
            $matching[$id] = $name;
          }
        }
      } catch (\Exception $e) {}
    }
    return $matching;
  }


  public function getImporter($definitionId, $state=null) {
    $def = $this->getDefinition($definitionId);
    $type = $this->getType($def);
    if ($type === 'xml') {
      return new XmlImporter($this->factory, $this->factory->getContext(), $def, XmlImporter::IMPORT, $state);
    }
    throw new UserError("Can't create importer for import definition $definitinId / $type");
  }

  public function countRows($input, $definitionId, $progress) {
    $def = $this->getDefinition($definitionId);
    $type = $this->getType($def);
    if ($type === 'xml') {
      $nodeCount = new XmlNodeCount();
      $ret = $nodeCount->run($input, $progress);
      $rootNode = $def['dataset']['root'];
      if (isset($ret[$rootNode])) {
        return $ret[$rootNode];
      }
      throw new UserError("Input file does not contain path $rootNode");
    }
    throw new UserError("Can't count rows for definition $definitinId / $type");
  }

  private function getType($definition) {
    if (isset($definition['dataset']['type'])) {
      return $definition['dataset']['type'];
    }
    throw new UserError("Input is not a valid import definition");
  }

  private function getDefinition($definitionId) {
    $recType = $this->factory->getRecord('importDefinitions');
    $rec = $recType->load($definitionId, array('parsed'));
    $parsed = json_decode($rec['parsed'], true);
    if ($parsed) {
      return $parsed;
    }
    throw new UserError("Can't parse import definition $definitionId");
  }

  public function getImportDefinition($definitionId) {
    $recType = $this->factory->getRecord('importDefinitions');
    $rec = $recType->load($definitionId, array('id', 'name', 'definition'));
    return $rec;
  }

  public function getStructure($fileType, $rawStructure) {
    if ($fileType === 'xml') {
      return new XmlStructure($rawStructure);
    }
    throw new UserError("Unknown datasource file type: $fileType");
  }

  /**
   * returns fresh copy of datasource
   */
  public function getFile($datasourceId) {
    $recType = $this->factory->getRecord('importDatasources');
    $rec = $recType->load($datasourceId, array('sourceType', 'source', 'fileType'));
    $sourceType = $rec['sourceType'];
    $source = $rec['source'];
    $fileType = $rec['fileType'];

    // uploaded file can't be refreshed - return staged version
    if ($sourceType === 'upload') {
      return $this->getDatasourceLocalFile($datasourceId);
    }

    // url or file sources can be refreshed
    if ($sourceType === 'url' || $sourceType === 'file') {
      $sourceFile = $this->fetchSource($sourceType, $source);
      $this->stageFile($sourceType, $sourceFile, $fileType, $datasourceId);
      return $this->getDatasourceLocalFile($datasourceId);
    }

    throw new UserError("Unknown datasource type: $sourceType");
  }

  /**
   * return staged (possibly stale) copy of datasoure
   */
  public function getDatasourceLocalFile($datasourceId) {
    $recType = $this->factory->getRecord('importDatasources');
    $rec = $recType->load($datasourceId, array('name', 'fileType'));
    $name = $rec['name'];
    $fileType = $rec['fileType'];
    $filename =  $this->getStagedFile($fileType, $datasourceId);
    if (! @is_file($filename)) {
      throw new UserError("Staged file for datasource $name not found: $filename");
    }
    return $filename;
  }

  public function shouldManageFile($sourceType) {
    return ($sourceType === 'upload' || $sourceType === 'url');
  }

  public function stageFile($sourceType, $source, $fileType, $datasourceId) {
    $target = $this->getStagedFile($fileType, $datasourceId);
    if (@is_file($source)) {
      if (@is_file($target)) {
        @unlink($target);
      }
      if ($this->shouldManageFile($sourceType)) {
        return @rename($source, $target);
      } else {
        return @copy($source, $target);
      }
    }
  }

  /**
   * fetches fresh content from datasource and returns file to temporary (not staging) directory
   */
  public function fetchSource($type, $source) {
    if ($type === 'upload' || $type === 'file') {
      return $this->validateFile($source);
    }
    if ($type === 'url') {
      $info = $this->factory->fetch($source)->download();
      if ($info['success'] && $info['path']) {
        return $info['path'];
      }
      throw new UserError('Failed to download file from URL');
    }
    throw new UserError('Unsupported type');
  }

  public function getStagedFile($fileType, $id) {
    $stageDir = $this->factory->getImportStagingDirectory();
    $padded = str_pad($id, 4, '0', STR_PAD_LEFT);
    return "$stageDir/datasource_$padded.$fileType";
  }

  private function validateFile($filename) {
    if (@is_dir($filename)) {
      throw new UserError("Can't import directory");
    }
    if (! @is_file($filename)) {
      throw new UserError("File not found");
    }
    if (! @is_readable($filename)) {
      throw new UserError("File is not readable");
    }
    return $filename;
  }

}
