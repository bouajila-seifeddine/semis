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

class Zone {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'zones',
      'singular' => 'zone',
      'description' => 'Zones',
      'key' => array('id'),
      'category' => 'common',
      'psTab' => 'AdminZones',
      'psController' => 'AdminZones',
      'psClass' => 'Zone',
      'display' => 'name',
      'parameters' => array('shop'),
      'restrictions' => array(
        'shop' => array(
          'shop' => '<field:shopId>'
        )
      ),
      'tables' => array(
        'zs' => array(
          'table' => 'zone_shop'
        ),
        'z' => array(
          'table' => 'zone',
          'require' => array('zs'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'zs.id_zone = z.id_zone',
              '<bind-param:shop:zs.id_shop>'
            )
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'sql' => 'z.id_zone',
          'require' => array('z'),
          'selectRecord' => 'zones',
          'update' => false
        ),
        'shopId' => array(
          'type' => 'number',
          'description' => 'shop id',
          'sql' => 'zs.id_shop',
          'require' => array('zs'),
          'update' => false,
          'hidden' => true
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'sql' => 'z.name',
          'require' => array('z'),
          'update' => array(
            'z' => 'name'
          )
        ),
        'active' => array(
          'type' => 'boolean',
          'description' => 'is active',
          'sql' => 'z.active',
          'require' => array('z'),
          'update' => array(
            'z' => 'active'
          )
        )
      ),
      'links' => array(
        'countries' => array(
          'description' => "Countries in zone",
          'collection' => 'countries',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('zoneId')
        ),
        'states' => array(
          'description' => "States in zone",
          'collection' => 'states',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('zoneId')
        ),
        'carriers' => array(
          'description' => "Carriers",
          'collection' => 'carriers',
          'type' => 'HABTM',
          'sourceFields' => array('id'),
          'targetFields' => array('id'),
          'joinTable' => 'carrier_zone',
          'joinFields' => array(
            'sourceFields' => array('id_zone'),
            'targetFields' => array('id_carrier'),
          ),
        )
      )
    ));
  }
}
