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

interface ImportDataStructure {

  // xml, csv, tsv
  function getFileType();

  // return true, if this structure satisfies all required inputs
  function satisfies(array $requiredInputs);

  // returns structure Datakick
  function  getData();

  // return unique grouping identifier
  function getGroupingIdentifier();
}
