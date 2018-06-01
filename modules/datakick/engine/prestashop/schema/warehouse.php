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

class Warehouse {
  public function register($dictionary) {
    if (\Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
      $dictionary->registerCollection(array(
        'id' => 'warehouses',
        'singular' => 'warehouse',
        'description' => 'Warehouses',
        'key' => array('id'),
        'category' => 'stock',
        'psTab' => 'AdminWarehouses',
        'psController' => 'AdminWarehouses',
        'psClass' => 'Warehouse',
        'display' => 'name',
        'parameters' => array('shop'),
        'restrictions' => array(
          'shop' => array(
            'shop' => '<field:shopId>'
          )
        ),
        'tables' => array(
          'ws' => array(
            'table' => 'warehouse_shop'
          ),
          'w' => array(
            'table' => 'warehouse',
            'require' => array('ws'),
            'join' => array(
              'type' => 'INNER',
              'conditions' => array(
                'ws.id_warehouse = w.id_warehouse',
                '<bind-param:shop:ws.id_shop>'
              )
            )
          ),
          'c' => array(
            'table' => 'currency',
            'require' => array('w'),
            'join' => array(
              'type' => 'INNER',
              'conditions' => array(
                'w.id_currency = c.id_currency'
              )
            )
          )
        ),
        'fields' => array(
          'id' => array(
            'type' => 'number',
            'description' => 'id',
            'sql' => 'w.id_warehouse',
            'require' => array('w'),
            'selectRecord' => 'warehouses',
            'update' => false
          ),
          'shopId' => array(
            'type' => 'number',
            'description' => 'shop id',
            'sql' => 'ws.id_shop',
            'require' => array('ws'),
            'update' => false,
            'hidden' => true
          ),
          'currencyId' => array(
            'type' => 'number',
            'description' => 'currency id',
            'sql' => 'w.id_currency',
            'require' => array('w'),
            'update' => false,
            'selectRecord' => 'currencies'
          ),
          'employeeId' => array(
            'type' => 'number',
            'description' => 'manager',
            'sql' => 'w.id_employee',
            'require' => array('w'),
            'update' => array(
              'w' => 'id_employee'
            ),
            'selectRecord' => 'employees'
          ),
          'addressId' => array(
            'type' => 'number',
            'description' => 'address id',
            'sql' => 'w.id_address',
            'require' => array('w'),
            'update' => false,
            'selectRecord' => 'addresses'
          ),
          'name' => array(
            'type' => 'string',
            'description' => 'name',
            'sql' => 'w.name',
            'require' => array('w'),
            'update' => array(
              'w' => 'name'
            )
          ),
          'reference' => array(
            'type' => 'string',
            'description' => 'reference',
            'sql' => 'w.reference',
            'require' => array('w'),
            'update' => array(
              'w' => 'reference'
            )
          ),
          'deleted' => array(
            'type' => 'boolean',
            'description' => 'is deleted',
            'sql' => 'w.deleted',
            'require' => array('w'),
            'update' => array(
              'w' => 'deleted'
            )
          ),
          'currency' => array(
            'type' => 'string',
            'description' => 'currency',
            'sql' => 'c.name',
            'require' => array('c'),
            'update' => false,
          ),
          'managementType' => array(
            'type' => 'string',
            'description' => 'management type',
            'require' => array('w'),
            'sql' => 'w.management_type',
            'values' => array(
              'WA' => 'Weighted Average',
              'FIFO' => "First In, First Out",
              'LIFO' => "Last In, First Out"
            ),
            'update' => array(
              'w' => 'management_type'
            )
          )
        ),
        'links' => array(
          'address' => array(
            'description' => "Address",
            'collection' => 'addresses',
            'type' => 'BELONGS_TO',
            'sourceFields' => array('addressId'),
            'targetFields' => array('id')
          ),
          'manager' => array(
            'description' => "Manager",
            'collection' => 'employees',
            'type' => 'BELONGS_TO',
            'sourceFields' => array('employeeId'),
            'targetFields' => array('id')
          ),
          'currency' => array(
            'description' => "Currency",
            'collection' => 'currencies',
            'type' => 'BELONGS_TO',
            'sourceFields' => array('currencyId'),
            'targetFields' => array('id')
          ),
          'supplyOrders' => array(
            'description' => "Supply orders",
            'collection' => 'supplyOrders',
            'type' => 'HAS_MANY',
            'sourceFields' => array('id'),
            'targetFields' => array('warehouseId')
          ),
          'stocks' => array(
            'description' => "Stocks",
            'collection' => 'stock',
            'type' => 'HAS_MANY',
            'sourceFields' => array('id'),
            'targetFields' => array('warehouseId')
          ),
          'stockMovements' => array(
            'description' => "Stock movements",
            'collection' => 'stockMovements',
            'type' => 'HAS_MANY',
            'sourceFields' => array('id'),
            'targetFields' => array('warehouseId')
          ),
          'carriers' => array(
            'description' => "Warehouse carriers",
            'collection' => 'carriers',
            'type' => 'HABTM',
            'sourceFields' => array('id'),
            'targetFields' => array('referenceId'),
            'joinTable' => 'warehouse_carrier',
            'joinFields' => array(
              'sourceFields' => array('id_warehouse'),
              'targetFields' => array('id_carrier')
            ),
            'joinConditions' => array(
              '<field:deleted> = 0'
            )
          )
        )
      ));
    }
  }
}
