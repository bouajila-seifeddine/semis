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

class SqlError extends UserError {
  private $msg;

  public function __construct($error, $sql, $code = 0, Exception $previous = null) {
    $message = "Failed to execute sql: $error\n\nQUERY:\n$sql";
    parent::__construct($message, $code, $previous);
    $this->msg = $error;
  }

  public function getUserMessage() {
    return "Failed to execute sql: ".$this->msg;
  }

}
