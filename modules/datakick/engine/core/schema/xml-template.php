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

class XmlTemplate {
  public function register($dictionary) {
    $dictionary->registerSystemCollection(array(
      'id' => 'xmlTemplates',
      'singular' => 'xmlTemplate',
      'description' => 'XML Template',
      'key' => array('id'),
      'category' => 'system',
      'display' => 'name',
      'usage' => 'xml-templates',
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
          'table' => 'xml-templates',
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'unique id',
          'selectRecord' => 'xmlTemplates',
          'mapping' => array(
            't' => 'id'
          ),
          'update' => false
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'update' => true,
          'mapping' => array(
            't' => 'name'
          )
        ),
        'template' => array(
          'type' => 'string',
          'description' => 'Template JSON',
          'hidden' => true,
          'update' => false,
          'mapping' => array(
            't' => 'template'
          ),
        ),
        'parsed' => array(
          'type' => 'string',
          'description' => 'Parsed JSON',
          'hidden' => true,
          'update' => false,
          'mapping' => array(
            't' => 'parsed'
          ),
        ),
        'userParameters' => array(
          'type' => 'string',
          'description' => 'User defined parameters',
          'hidden' => true,
          'update' => false,
          'mapping' => array(
            't' => 'user_parameters'
          ),
        ),
        'requiredParameters' => array(
          'type' => 'string',
          'description' => 'Required parameters',
          'hidden' => true,
          'update' => false,
          'mapping' => array(
            't' => 'required_parameters'
          ),
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
            '<target:recordType> = "xmlTemplates"'
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
            '<target:recordType> = "xmlTemplates"'
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
            'id' => 'xmlTemplates',
            'description' => 'XML Templates',
            'type' => 'HAS_MANY'
          )
        )
      )
    ));
  }
}
