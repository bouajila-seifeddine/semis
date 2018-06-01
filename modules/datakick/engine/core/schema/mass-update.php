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

class MassUpdate {
  public function register($dictionary) {
    $dictionary->registerSystemCollection(array(
      'id' => 'massUpdates',
      'singular' => 'massUpdate',
      'description' => 'Mass Update',
      'key' => array('id'),
      'category' => 'system',
      'display' => 'name',
      'usage' => 'mass-updates',
      'create' => true,
      'delete' => true,
      'permissions' => array(
        'view' => true,
      ),
      'restrictions' => array(
        'user' => array(
          'user' => '<field:userId>',
          'public' => '<field:public>',
        )
      ),
      'tables' => array(
        't' => array(
          'table' => 'mass-updates',
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'unique id',
          'selectRecord' => 'massUpdates',
          'update' => false,
          'mapping' => array(
            't' => 'id'
          )
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'update' => true,
          'mapping' => array(
            't' => 'name'
          )
        ),
        'definition' => array(
          'type' => 'string',
          'description' => 'Mass Update Definition JSON',
          'hidden' => true,
          'update' => false,
          'mapping' => array(
            't' => 'definition'
          )
        ),
        'parsed' => array(
          'type' => 'string',
          'description' => 'Parsed JSON',
          'hidden' => true,
          'update' => false,
          'mapping' => array(
            't' => 'parsed'
          )
        ),
        'requiredParameters' => array(
          'type' => 'string',
          'description' => 'Required parameters',
          'hidden' => true,
          'update' => false,
          'mapping' => array(
            't' => 'required_parameters'
          )
        ),
        'icon' => array(
          'type' => 'string',
          'description' => 'Icon URL',
          'update' => true,
          'mapping' => array(
            't' => 'icon'
          )
        ),
        'image' => array(
          'type' => 'string',
          'description' => 'Image URL',
          'update' => true,
          'mapping' => array(
            't' => 'image'
          )
        ),
        'description' => array(
          'type' => 'string',
          'description' => 'description',
          'update' => true,
          'mapping' => array(
            't' => 'description'
          )
        ),
        'userId' => array(
          'type' => 'number',
          'description' => 'User id',
          'selectRecord' => array(
            'role' => 'user'
          ),
          'update' => true,
          'mapping' => array(
            't' => 'user_id'
          )
        ),
        'public' => array(
          'type' => 'boolean',
          'description' => "Is public",
          'update' => true,
          'mapping' => array(
            't' => 'public'
          )
        )
      ),
      'links' => array(
        'endpoints' => array(
          'description' => 'Endpoints',
          'collection' => 'endpoints',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('recordId'),
          'conditions' => array(
            '<target:recordType> = "massUpdates"'
          ),
          'delete' => true,
          'unidirectional' => true
        ),
        'schedules' => array(
          'description' => 'Schedules',
          'collection' => 'schedules',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('recordId'),
          'conditions' => array(
            '<target:recordType> = "massUpdates"'
          ),
          'delete' => true,
          'unidirectional' => true
        ),
        'owner' => array(
          'description' => 'Owner',
          'collection' => array(
            'role' => 'user'
          ),
          'type' => 'BELONGS_TO',
          'sourceFields' => array('userId'),
          'targetFields' => array('id'),
          'generateReverse' => array(
            'id' => 'massUpdates',
            'description' => 'Mass Updates',
            'type' => 'HAS_MANY'
          )
        )
      )
    ));
  }
}
