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

class Callback implements Statement {
  public function __construct($callback, $params=array()) {
    $this->callback = $callback;
    $this->params = $params;
  }

  public function getSQL(Context $context) {
    return "// *** EXECUTE PHP FUNCTION\n// " . $this->callback[0] . '::' . $this->callback[1] . '($factory);';
  }

  public function execute(Factory $factory, Context $context) {
    call_user_func($this->callback, $factory, $this->params);
  }
}
