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

class SetVirtualField implements Statement {
  private $collection;
  private $fld;
  private $value;
  private $dep;

  public function __construct(Escape $escape, Collection $collection, Field $fld, $value, $dep) {
    $this->escape = $escape;
    $this->collection = $collection;
    $this->fld = $fld;
    $this->value = $value;
    $this->dep = $dep;
  }

  public function getSQL(Context $context) {
    return "// *** SET VIRTUAL FIELD {$this->fld->getId()} ***";
  }

  public function execute(Factory $factory, Context $context) {
    $id = $this->dep->getPrimaryKey();
    try {
      $params = array($id, $this->value, $factory, $context);
      call_user_func_array($this->fld->getSetFunction(), $params);
    } catch (\Exception $e) {
      if ($id) {
        $key = $this->collection->getKeys()[0];
        $pk = array($key => $id);
        $pks = array($pk);
        $delete = new DeleteRecord($factory, $this->escape, $this->collection, $pks);
        $delete->execute($factory, $context);
      }
      // exception - we need to delete
      throw $e;
    }
  }
}
