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

class Group {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'customerGroups',
      'singular' => 'customerGroup',
      'description' => 'Customer Groups',
      'key' => array('id'),
      'category' => 'relationships',
      'display' => 'name',
      'parameters' => array('shop'),
      'psTab' => 'AdminGroups',
      'psController' => 'AdminGroups',
      'psClass' => 'Group',
      'restrictions' => array(
        'shop' => array(
          'shop' => '<field:shopId>'
        )
      ),
      'tables' => array(
        'gs' => array(
          'table' => 'group_shop'
        ),
        'g' => array(
          'table' => 'group',
          'require' => array('gs'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'gs.id_group = g.id_group',
              '<bind-param:shop:gs.id_shop>'
            )
          )
        ),
        'gl' => array(
          'table' => 'group_lang',
          'require' => array('gs'),
          'join' => array(
            'type' => 'LEFT',
            'conditions' => array(
              'gl.id_group = gs.id_group',
              '<bind-param:shop:gl.id_lang>'
            )
          ),
          'parameters' => array('shop'),
          'create' => array(
            'id_group' => '<pk>',
            'id_lang' => '<param:shop>'
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'sql' => 'g.id_group',
          'require' => array('g'),
          'selectRecord' => 'customerGroups',
          'update' => false,
        ),
        'shopId' => array(
          'type' => 'number',
          'description' => 'shop id',
          'sql' => 'gs.id_shop',
          'require' => array('gs'),
          'update' => false,
          'hidden' => true
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'sql' => 'gl.name',
          'require' => array('gl'),
          'update' => array(
            'gl' => 'name'
          )
        ),
        'showPrices' => array(
          'type' => 'boolean',
          'description' => 'show prices',
          'sql' => 'g.show_prices',
          'require' => array('g'),
          'update' => array(
            'g' => 'show_prices'
          )
        ),
        'discount' => array(
          'type' => 'number',
          'description' => 'discount %',
          'sql' => 'g.reduction',
          'require' => array('g'),
          'update' => array(
            'g' => 'reduction'
          )
        ),
        'pricesWithTax' => array(
          'type' => 'boolean',
          'description' => 'prices with tax',
          'sql' => 'IF(g.price_display_method = 0, 1, 0)',
          'require' => array('g'),
          'update' => array(),
        ),
        'created' => array(
          'type' => 'datetime',
          'description' => 'date created',
          'sql' => 'g.date_add',
          'require' => array('g'),
          'update' => false,
        ),
        'updated' => array(
          'type' => 'datetime',
          'description' => 'date updated',
          'sql' => 'g.date_upd',
          'require' => array('g'),
          'update' => false,
        ),
      ),
      'links' => array(
        'defaultCustomers' => array(
          'description' => "Customers using group as default",
          'collection' => 'customers',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('defaultGroupId')
        ),
        'customers' => array(
          'description' => "Customers in group",
          'collection' => 'customers',
          'type' => 'HABTM',
          'sourceFields' => array('id'),
          'targetFields' => array('id'),
          'joinTable' => 'customer_group',
          'joinFields' => array(
            'sourceFields' => array('id_group'),
            'targetFields' => array('id_customer'),
          ),
        ),
        'carriers' => array(
          'description' => "Carriers in group",
          'collection' => 'carriers',
          'type' => 'HABTM',
          'sourceFields' => array('id'),
          'targetFields' => array('id'),
          'joinTable' => 'carrier_group',
          'joinFields' => array(
            'sourceFields' => array('id_group'),
            'targetFields' => array('id_carrier'),
          ),
        )
      )
    ));
  }
}
