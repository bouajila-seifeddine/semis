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

class State {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'states',
      'singular' => 'state',
      'description' => 'States',
      'key' => array('id'),
      'category' => 'common',
      'psTab' => 'AdminStates',
      'psController' => 'AdminStates',
      'psClass' => 'State',
      'display' => 'name',
      'parameters' => array(),
      'tables' => array(
        's' => array(
          'table' => 'state'
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'sql' => 's.id_state',
          'require' => array('s'),
          'selectRecord' => 'states',
          'update' => false
        ),
        'countryId' => array(
          'type' => 'number',
          'description' => 'country id',
          'sql' => 's.id_country',
          'selectRecord' => 'countries',
          'require' => array('s'),
          'update' => array(
            's' => 'id_country'
          )
        ),
        'zoneId' => array(
          'type' => 'number',
          'description' => 'zone id',
          'sql' => 's.id_zone',
          'selectRecord' => 'zones',
          'require' => array('s'),
          'update' => array(
            's' => 'id_zone'
          )
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'sql' => 's.name',
          'require' => array('s'),
          'update' => array(
            's' => 'name'
          )
        ),
        'isoCode' => array(
          'type' => 'string',
          'description' => 'ISO code',
          'sql' => 's.iso_code',
          'require' => array('s'),
          'update' => array(
            's' => 'iso_code'
          )
        ),
        'active' => array(
          'type' => 'boolean',
          'description' => 'is active',
          'sql' => 's.active',
          'require' => array('s'),
          'update' => array(
            's' => 'active'
          )
        ),
      ),
      'links' => array(
        'country' => array(
          'description' => "Country",
          'collection' => 'countries',
          'type' => 'BELONGS_TO',
          'sourceFields' => array('countryId'),
          'targetFields' => array('id')
        ),
        'zone' => array(
          'description' => "Zone",
          'collection' => 'zones',
          'type' => 'BELONGS_TO',
          'sourceFields' => array('zoneId'),
          'targetFields' => array('id')
        ),
        'addresses' => array(
          'description' => "Addresses",
          'collection' => 'addresses',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('stateId')
        )
      )
    ));
  }
}
