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

class Currency {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'currencies',
      'singular' => 'currency',
      'description' => 'Currencies',
      'key' => array('id'),
      'category' => 'common',
      'display' => 'name',
      'parameters' => array('shop'),
      'psTab' => 'AdminCurrencies',
      'psController' => 'AdminCurrencies',
      'psClass' => 'Currency',
      'permissions' => array(
        'view' => true
      ),
      'restrictions' => array(
        'shop' => array(
          'shop' => '<field:shopId>'
        )
      ),
      'tables' => array(
        'c' => array(
          'table' => 'currency'
        ),
        'cs' => array(
          'table' => 'currency_shop',
          'require' => array('c'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'cs.id_currency = c.id_currency',
              '<bind-param:shop:cs.id_shop>'
            )
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'sql' => 'c.id_currency',
          'require' => array('c'),
          'selectRecord' => 'currencies',
          'update' => false
        ),
        'shopId' => array(
          'type' => 'number',
          'description' => 'shop id',
          'sql' => 'cs.id_shop',
          'require' => array('cs'),
          'update' => false,
          'hidden' => true
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'sql' => 'c.name',
          'require' => array('c'),
          'update' => array(
            'c' => 'name'
          )
        ),
        'code' => array(
          'type' => 'string',
          'description' => 'ISO code',
          'sql' => 'c.iso_code',
          'require' => array('c'),
          'update' => array(
            'c' => 'iso_code'
          )
        ),
        'conversionRate' => array(
          'type' => 'number',
          'description' => 'conversion rate',
          'sql' => 'cs.conversion_rate',
          'require' => array('cs'),
          'update' => array(
            'cs' => 'conversion_rate'
          )
        )
      ),
      'links' => array(
        'country' => array(
          'description' => "Countries",
          'collection' => 'countries',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('currencyId')
        ),
        'orders' => array(
          'description' => "Orders",
          'collection' => 'orders',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('currencyId')
        ),
        'warehouses' => array(
          'description' => "Warehouses",
          'collection' => 'warehouses',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('currencyId')
        )
      )
    ));
  }
}
