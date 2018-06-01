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

class DeleteAssociation implements Statement {
  private $delete;

  public function __construct(Escape $escape, Collection $collection, Link $link, $pks=null) {
    if (! $link->canDelete()) {
      throw new \Exception("Can't dissoc using link {$link->getId()}");
    }
    if (! $link->isHABTM())  {
      throw new \Exception("Can't dissoc non-habtm link {$link->getId()}");
    }

    $keys = $collection->getKeys();
    DeleteRecord::validateKeys($keys, $pks);

    $sourceFields = $link->getSourceFields();
    $joinSourceFields = $link->getJoinSourceFields();
    $table = $link->getJoinTable();
    if ($sourceFields == $keys) {
      // simple scenario = deassociate by keys
      $conds = DeleteRecord::getDeleteCondition(Utils::zip($sourceFields, $joinSourceFields), $pks);
      $this->delete = new Delete($escape, $table, $conds);
    } else {
      $subkeys = array();
      foreach ($keys as $key) {
        for ($i = 0; $i<count($sourceFields); $i++) {
          if ($sourceFields[$i] == $key) {
            $subkeys[$key] = $joinSourceFields[$i];
          }
        }
      }
      if (count($subkeys) === count($keys)) {
        // yey, it's subkey
        $conds = DeleteRecord::getDeleteCondition($subkeys, $pks);
        $this->delete = new Delete($escape, $table, $conds);
      } else {
        throw new \Exception("Can't delete from link $linkKey: it's keys are not subkeys of pks");
      }
    }
  }

  public function getSQL(Context $context) {
    return $this->delete->getSQL($context);
  }

  public function execute(Factory $factory, Context $context) {
    return $this->delete->execute($factory, $context);
  }
}
