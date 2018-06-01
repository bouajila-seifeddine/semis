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

class PrestashopBackupDbTask extends Task {

  public function __construct(Factory $factory, $identity) {
    parent::__construct($factory, $identity);
  }

  public function doExecute(Context $context, Progress $progress, $executionId, $resumeState) {
    $progress->start('Backup DB');
    $placeId = $context->getValue('task::placeId');
    $ignoreStats = $context->getValue('task::ignoreStats');
    $dropTables = $context->getValue('task::dropTables');

    $factory = $this->getFactory();
    $place = $factory->getPlaces()->load($placeId, $this->getTaskInfo($context, $executionId));

    $original = null;
    if (defined('_PS_ADMIN_DIR_')) {
      $original = _PS_ADMIN_DIR_;
    }

    // a little hack - remap ps admin dir to root, and ensure existance of backups directory in there
    define('_PS_ADMIN_DIR_', _PS_ROOT_DIR_);
    $dir = DIRECTORY_SEPARATOR . "backups" . DIRECTORY_SEPARATOR;
    @mkdir(_PS_ROOT_DIR_ .  $dir);

    $shopIds = $this->getShopIDs($context);
    $ret = array();
    $status = true;
    $ts = $context->getValue('timestamp')->format('YmdHim');
    $i = 0;
    foreach ($shopIds as $shopId) {
      $i++;
      try {
        \Shop::setContext(\Shop::CONTEXT_SHOP, (int)$shopId);
        $shop = new \Shop($shopId);
        $back = new \PrestaShopBackup();
        $back->psBackupAll = !$ignoreStats;
        $back->psBackupDropTable = $dropTables;
        if ($back->add()) {
          $tmpFile = $back->id;
          $name = Utils::decamelize(Utils::toCamelCase($shop->name));
          $outputPath = $ts . '-' . $name .'-' . basename($tmpFile);
          if (! $place->saveFile($back->id, $outputPath)) {
            $status = false;
            $ret[$shopId] = false;
          } else {
            @unlink($tmpFile);
            $ret[$shopId] = $outputPath;
          }
        } else {
          $status = false;
          $ret[$shopId] = false;
        }
      } catch (\Exception $e) {
        $status = false;
        $ret[$shopId] = false;
      }
      $progress->setProgress(count($shopIds), $i);
    }
    $place->finalize();
    $progress->end();
    if ($original) {
      define('_PS_ADMIN_DIR_', $original);
    }

    return array(
      'success' => $status,
      'place' => array(
        'id' => $place->getId(),
        'name' => $place->getName(),
        'type' => $place->getType(),
      ),
      'files' => $ret
    );
  }

  public function getRequiredParameters() {
    return array_keys($this->getUserParameters());
  }

  public function getUserParameters() {
    $factory = $this->getFactory();
    $def = $factory->getRecord('places')->loadFirst();
    $enums = $factory->getEnums();
    $shops = $enums['shops'];
    $hidden = count($shops) <= 1;
    $shops[-1] = '-- All shops --';
    $psBackupAll = \Configuration::get('PS_BACKUP_ALL');
    $psBackupDropTable = \Configuration::get('PS_BACKUP_DROP_TABLE');

    return array(
      'task::placeId' => array(
        'type' => 'number',
        'description' => "Destination",
        'selectRecord' => 'places',
        'default' => is_null($def) ? null : $def['id'],
        'order' => 1,
      ),
      'task::shopId' => array(
        'type' => 'number',
        'description' => "Select shop(s)",
        'values' => $shops,
        'default' => -1,
        'hidden' => $hidden,
        'order' => 2
      ),
      'task::ignoreStats' => array(
        'type' => 'boolean',
        'description' => 'Ignore statistics tables',
        'default' => $psBackupAll == true,
        'order' => 3
      ),
      'task::dropTables' => array(
        'type' => 'boolean',
        'description' => 'Drop existing tables during import',
        'default' => $psBackupDropTable == true,
        'order' => 4
      )
    );
  }

  private function getShopIDs($context) {
    $shopId = $context->getValue('task::shopId');
    if ($shopId == -1)
      return \Shop::getCompleteListOfShopsID();
    return array($shopId);
  }

}
