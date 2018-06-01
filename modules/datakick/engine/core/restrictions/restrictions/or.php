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

class OrRestriction implements Restriction {
  private $left;
  private $right;

  public function __construct($left, $right) {
    $this->left = $left;
    $this->right = $right;
  }

  public function getCondition(Array $fields) {
    $left = $this->left->getCondition($fields);
    $right = $this->right->getCondition($fields);
    if ($left == Restriction::ALLOW || $right == Restriction::ALLOW)
      return Restriction::ALLOW;
    if ($left == Restriction::DENY)
      return $right;
    if ($right == Restriction::DENY)
      return $left;
    return "($left OR $right)";
  }

}
