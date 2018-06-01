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

class ChangeOwnerService extends Service {

  public function __construct() {
    parent::__construct('change-owner');
  }

  public function process($factory, $request) {
    $perm = $factory->getUser()->getPermissions()->checkAdmin();

    $type = $this->getParameter('recordType');
    $id = (int)$this->getParameter('recordId');
    $owner = (int)$this->getParameter('owner');

    $col = $factory->getDictionary()->getCollection($type);
    if ($col->getCategory() != 'system' || ! $col->hasField('userId')) {
      throw new UserError("Can't change owner of ".$col->getName());
    }

    $userIdField = $col->getField('userId');
    $connection = $factory->getConnection();
    $ret = true;
    foreach ($userIdField->getMapping() as $tableAlias => $field) {
      $table = $col->getTable($tableAlias)['table'];
      $ret = $ret && $connection->update($table, array($field => $owner), array('id' => $id));
    }
    return $ret;
  }

}
