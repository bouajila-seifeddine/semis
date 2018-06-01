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

class Place {
  public function register($dictionary, $factory) {
    $scheduleParameter = $factory->getServiceTable('schedule-parameter');
    $dictionary->registerSystemCollection(array(
      'id' => 'places',
      'singular' => 'place',
      'description' => 'Place',
      'key' => array('id'),
      'display' => 'name',
      'category' => 'system',
      'create' => true,
      'delete' => true,
      'permissions' => array(
        'view' => true,
        'edit' => false,
        'delete' => false,
        'create' => false
      ),
      'tables' => array(
        'p' => array(
          'table' => 'place',
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'unique id',
          'selectRecord' => 'places',
          'update' => false,
          'mapping' => array(
            'p' => 'id'
          )
        ),
        'type' => array(
          'type' => 'string',
          'description' => 'Type',
          'update' => false,
          'mapping' => array(
            'p' => 'type'
          )
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'Name',
          'update' => true,
          'mapping' => array(
            'p' => 'name'
          )
        ),
        'isUsed' => array(
          'type' => 'boolean',
          'description' => 'Is Used',
          'mapping' => array(
            'p' => array(
              'field' => 'id',
              'read' => "EXISTS(SELECT 1 FROM $scheduleParameter sp WHERE sp.name = 'task::placeId' AND sp.value = <field>)"
            )
          ),
          'update' => false
        )
      ),
      'links' => array(
        'config' => array(
          'description' => 'Place Configuration',
          'collection' => 'placeConfig',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('placeId'),
          'delete' => true,
        )
      )
    ));

    $dictionary->registerSystemCollection(array(
      'id' => 'placeConfig',
      'singular' => 'placeConfig',
      'description' => 'Place Configuration',
      'key' => array('placeId', 'name'),
      'category' => 'system',
      'display' => 'value',
      'delete' => true,
      'tables' => array(
        'd' => array(
          'table' => 'place-config',
        )
      ),
      'permissions' => array(
        'view' => true,
        'edit' => false,
        'delete' => false,
        'create' => false
      ),
      'fields' => array(
        'placeId' => array(
          'type' => 'number',
          'description' => 'place id',
          'mapping' => array('d' => 'place_id'),
          'selectRecord' => 'places',
          'update' => false
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'Name',
          'mapping' => array('d' => 'name'),
          'update' => false
        ),
        'value' => array(
          'type' => 'string',
          'description' => 'Value',
          'mapping' => array('d' => 'value'),
          'update' => false
        )
      ),
      'links' => array(
        'place' => array(
          'description' => 'Place',
          'collection' => 'places',
          'type' => 'BELONGS_TO',
          'sourceFields' => array('placeId'),
          'targetFields' => array('id')
        )
      )
    ));

  }
}
