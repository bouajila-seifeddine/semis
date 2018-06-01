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

interface RestrictionType {
  // fields this restriction depends on
  function fields();

  // return default restriction level for read operation
  function getDefaultReadLevel();

  // return default restriction level for write operation
  function getDefaultWriteLevel();

  // get levels
  function getLevels();

  // get icon
  function getIcon();

  // get name
  function getName();

  // get description
  function getDescription();

  // create restriction for given level
  function create($level);
}
