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

class ImportDefinitionSchema {
  public function register($dictionary) {
    $dictionary->registerSystemCollection(array(
      'id' => 'importDefinitions',
      'singular' => 'importDefinition',
      'description' => 'Import Definitions',
      'key' => array('id'),
      'category' => 'system',
      'display' => 'name',
      'create' => true,
      'delete' => true,
      'usage' => 'import-definition',
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
          'table' => 'import-definition',
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'unique id',
          'selectRecord' => 'importDefinitions',
          'mapping' => array(
            't' => 'id'
          ),
          'update' => false,
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'mapping' => array(
            't' => 'name'
          ),
          'update' => true,
        ),
        'definition' => array(
          'type' => 'string',
          'description' => 'import definition JSON',
          'mapping' => array(
            't' => 'definition'
          ),
          'hidden' => true,
          'update' => false,
        ),
        'parsed' => array(
          'type' => 'string',
          'description' => 'Parsed JSON',
          'mapping' => array(
            't' => 'parsed'
          ),
          'hidden' => true,
          'update' => false,
        ),
        'icon' => array(
          'type' => 'string',
          'description' => 'Icon URL',
          'mapping' => array(
            't' => 'icon'
          ),
          'update' => true,
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
          'description' => 'Description',
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
        'owner' => array(
          'description' => 'Owner',
          'collection' => array(
            'role' => 'user'
          ),
          'type' => 'BELONGS_TO',
          'sourceFields' => array('userId'),
          'targetFields' => array('id'),
          'generateReverse' => array(
            'id' => 'lists',
            'description' => 'Lists',
            'type' => 'HAS_MANY'
          )
        )
      )
    ));
  }
}
