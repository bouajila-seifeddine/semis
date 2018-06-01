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
namespace Datakick\Schema\Core;

class CustomField {
  public function register($dictionary) {
    $dictionary->registerSystemCollection(array(
      'id' => 'customFields',
      'singular' => 'customField',
      'description' => 'Custom field',
      'key' => array('id'),
      'category' => 'system',
      'display' => 'name',
      'usage' => 'custom-field',
      'create' => true,
      'delete' => true,
      'permissions' => array(
        'view' => true
      ),
      'tables' => array(
        'cf' => array(
          'table' => 'custom-field'
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'selectRecord' => 'customFields',
          'description' => 'unique id',
          'update' => false,
          'mapping' => array(
            'cf' => 'id'
          )
        ),
        'alias' => array(
          'type' => 'string',
          'description' => 'alias',
          'required' => true,
          'update' => false,
          'mapping' => array(
            'cf' => 'alias'
          )
        ),
        'type' => array(
          'type' => 'string',
          'required' => true,
          'description' => 'type',
          'update' => false,
          'mapping' => array(
            'cf' => 'type'
          )
        ),
        'fieldset' => array(
          'type' => 'string',
          'description' => 'fieldset',
          'update' => true,
          'mapping' => array(
            'cf' => 'fieldset'
          )
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'required' => true,
          'update' => true,
          'mapping' => array(
            'cf' => 'name'
          )
        ),
        'description' => array(
          'type' => 'string',
          'description' => 'description',
          'update' => true,
          'mapping' => array(
            'cf' => 'description'
          )
        ),
        'subtype' => array(
          'type' => 'string',
          'description' => 'subtype',
          'update' => false,
          'mapping' => array(
            'cf' => 'subtype'
          )
        ),
        'created' => array(
          'type' => 'datetime',
          'description' => 'date created',
          'update' => false,
          'mapping' => array(
            'cf' => 'created'
          )
        )
      ),
      'links' => array(
      )
    ));
  }
};
