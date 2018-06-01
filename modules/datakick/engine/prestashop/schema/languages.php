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
namespace Datakick\Schema\Prestashop;

class Languages {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'languages',
      'singular' => 'language',
      'description' => 'Languages',
      'key' => array('id'),
      'category' => 'common',
      'display' => 'name',
      'parameters' => array(),
      'psTab' => 'AdminLanguages',
      'psController' => 'AdminLanguages',
      'psClass' => 'Language',
      'permissions' => array(
        'view' => true
      ),
      'tables' => array(
        'l' => array(
          'table' => 'lang'
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'sql' => 'l.id_lang',
          'require' => array('l'),
          'selectRecord' => 'languages',
          'update' => false
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'sql' => 'l.name',
          'require' => array('l'),
          'update' => array(
            'l' => 'name'
          )
        ),
        'isoCode' => array(
          'type' => 'string',
          'description' => 'ISO code',
          'sql' => 'l.iso_code',
          'require' => array('l'),
          'update' => array(
            'l' => 'iso_code'
          )
        ),
        'code' => array(
          'type' => 'string',
          'description' => 'code',
          'sql' => 'l.language_code',
          'require' => array('l'),
          'update' => array(
            'l' => 'language_code'
          )
        ),
        'dateFormat' => array(
          'type' => 'string',
          'description' => 'date format',
          'sql' => 'l.date_format_lite',
          'require' => array('l'),
          'update' => array(
            'l' => 'date_format_lite'
          )
        ),
        'dateTimeFormat' => array(
          'type' => 'string',
          'description' => 'date/time format',
          'sql' => 'l.date_format_full',
          'require' => array('l'),
          'update' => array(
            'l' => 'date_format_full'
          )
        ),
        'isRTL' => array(
          'type' => 'boolean',
          'description' => 'is RTL',
          'sql' => 'l.is_rtl',
          'require' => array('l'),
          'update' => array(
            'l' => 'is_rtl'
          )
        ),
      ),
      'links' => array(
        'employees' => array(
          'description' => "Employee language",
          'collection' => 'employees',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('languageId')
        )
      )
    ));
  }
}
