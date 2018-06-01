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

class StockMovement {
  public function register($dictionary) {
    if (\Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
      $dictionary->registerCollection(array(
        'id' => 'stockMovements',
        'singular' => 'stockMovement',
        'description' => 'Stock Movements',
        'key' => array('id'),
        'category' => 'stock',
        'psTab' => 'AdminStockMvt',
        'psController' => 'AdminStockMvt',
        'psClass' => 'StockMvt',
        'display' => 'description',
        'parameters' => array('language'),
        'delete' => true,
        'restrictions' => array(
        ),
        'tables' => array(
          'm' => array(
            'table' => 'stock_mvt'
          ),
          's' => array(
            'table' => 'stock',
            'require' => array('m'),
            'join' => array(
              'type' => 'INNER',
              'conditions' => array(
                'm.id_stock = s.id_stock'
              )
            )
          ),
          'w' => array(
            'table' => 'warehouse',
            'require' => array('s'),
            'join' => array(
              'type' => 'INNER',
              'conditions' => array(
                's.id_warehouse = w.id_warehouse'
              )
            )
          ),
          'r' => array(
            'table' => 'stock_mvt_reason_lang',
            'require' => array('m'),
            'join' => array(
              'type' => 'INNER',
              'conditions' => array(
                'r.id_stock_mvt_reason = m.id_stock_mvt_reason',
                '<bind-param:language:r.id_lang>'
              )
            )
          ),
        ),
        'fields' => array(
          'id' => array(
            'type' => 'number',
            'description' => 'id',
            'mapping' => array(
              'm' => 'id_stock_mvt',
            ),
            'selectRecord' => 'stockMovements',
            'update' => false
          ),
          'stockId' => array(
            'type' => 'string',
            'description' => 'stock id',
            'mapping' => array(
              'm' => 'id_stock',
              's' => 'id_stock'
            ),
            'update' => true,
            'selectRecord' => 'stock',
          ),
          'orderId' => array(
            'type' => 'string',
            'description' => 'order id',
            'mapping' => array('m' => 'id_order'),
            'update' => true,
            'selectRecord' => 'orders',
          ),
          'supplyOrderId' => array(
            'type' => 'string',
            'description' => 'supply order id',
            'mapping' => array('m' => 'id_supply_order'),
            'update' => true,
            'selectRecord' => 'supplyOrders',
          ),
          'employeeId' => array(
            'type' => 'string',
            'description' => 'employee id',
            'mapping' => array('m' => 'id_employee'),
            'update' => true,
            'selectRecord' => 'employees',
          ),
          'reference' => array(
            'type' => 'string',
            'description' => 'reference',
            'mapping' => array('s' => 'reference'),
            'update' => false
          ),
          'label' => array(
            'type' => 'string',
            'description' => 'label',
            'mapping' => array('r' => 'name'),
            'update' => false
          ),
          'warehouseId' => array(
            'type' => 'string',
            'description' => 'warehouse id',
            'mapping' => array('s' => 'id_warehouse'),
            'selectRecord' => 'warehouses',
            'update' => false
          ),
          'warehouse' => array(
            'type' => 'string',
            'description' => 'warehouse',
            'mapping' => array('w' => 'name'),
            'update' => false
          ),
          'sign' => array(
            'type' => 'number',
            'description' => 'sign',
            'update' => true,
            'mapping' => array(
              'm' => 'sign'
            )
          ),
          'physicalQuantity' => array(
            'type' => 'number',
            'description' => 'physical quantity',
            'update' => true,
            'mapping' => array(
              'm' => array(
                'field' => 'physical_quantity',
                'read' => 'CAST(m.physical_quantity AS SIGNED)'
              )
            )
          ),
          'price' => array(
            'type' => 'currency',
            'description' => 'price',
            'sql' => array(
              'value'  => 'm.price_te',
              'currency' => 'w.id_currency'
            ),
            'fixedCurrency' => true,
            'require' => array('m', 'w'),
            'update' => true,
            'mapping' => array(
              'm' => array(
                'field' => array(
                  'value' => 'price_te'
                )
              )
            )
          ),
          'created' => array(
            'type' => 'datetime',
            'description' => 'date created',
            'mapping' => array('m' => 'date_add'),
            'update' => true
          ),
          'employee' => array(
            'type' => 'string',
            'description' => 'employee',
            'sql' => 'CONCAT(m.employee_firstname, " ", m.employee_lastname)',
            'require' => array('m'),
            'update' => false
          ),
        ),
        'expressions' => array(
          'description' => array(
            'type' => 'string',
            'description' => 'description',
            'expression' => '<field:label> + " - " + <field:reference> + " - " +<field:warehouse>'
          ),
          'quantity' => array(
            'type' => 'number',
            'description' => 'quantity',
            'expression' => '<field:physicalQuantity> * <field:sign>'
          ),
        ),
        'links' => array(
          'stock' => array(
            'description' => "Stock",
            'collection' => 'stock',
            'type' => 'BELONGS_TO',
            'sourceFields' => array('stockId'),
            'targetFields' => array('id')
          ),
          'warehouse' => array(
            'description' => "Warehouse",
            'collection' => 'warehouses',
            'type' => 'BELONGS_TO',
            'sourceFields' => array('warehouseId'),
            'targetFields' => array('id')
          ),
          'order' => array(
            'description' => "Order",
            'collection' => 'orders',
            'type' => 'HAS_ONE',
            'sourceFields' => array('orderId'),
            'targetFields' => array('id')
          ),
          'employee' => array(
            'description' => "Employee",
            'collection' => 'employees',
            'type' => 'HAS_ONE',
            'sourceFields' => array('employeeId'),
            'targetFields' => array('id')
          ),
        )
      ));
    }
  }
}
