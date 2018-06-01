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

class Stock {
  public function register($dictionary) {
    if (\Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
      $dictionary->registerCollection(array(
        'id' => 'stock',
        'singular' => 'stock',
        'description' => 'Stock',
        'key' => array('id'),
        'category' => 'stock',
        'psTab' => 'AdminStockManagement',
        'psController' => 'AdminStockManagement',
        'psClass' => 'Stock',
        'display' => 'reference',
        'delete' => true,
        'parameters' => array(),
        'restrictions' => array(
        ),
        'tables' => array(
          's' => array(
            'table' => 'stock'
          ),
          'w' => array(
            'table' => 'warehouse',
            'join' => array(
              'type' => 'INNER',
              'conditions' => array(
                's.id_warehouse = w.id_warehouse'
              )
            )
          )
        ),
        'fields' => array(
          'id' => array(
            'type' => 'number',
            'description' => 'id',
            'mapping' => array('s' => 'id_stock'),
            'selectRecord' => 'stock',
            'update' => false
          ),
          'warehouseId' => array(
            'type' => 'number',
            'description' => 'warehouse id',
            'mapping' => array('s' => 'id_warehouse'),
            'selectRecord' => 'warehouses',
            'update' => true,
          ),
          'productId' => array(
            'type' => 'number',
            'description' => 'product id',
            'mapping' => array('s' => 'id_product'),
            'update' => true,
            'selectRecord' => 'products',
          ),
          'combinationId' => array(
            'type' => 'number',
            'description' => 'combination id',
            'mapping' => array('s' => 'id_product_attribute'),
            'update' => true,
            'selectRecord' => 'combinations',
          ),
          'warehouse' => array(
            'type' => 'string',
            'description' => 'warehouse',
            'mapping' => array('w' => 'name'),
            'update' => false
          ),
          'reference' => array(
            'type' => 'string',
            'description' => 'reference',
            'update' => true,
            'mapping' => array(
              's' => 'reference'
            )
          ),
          'ean13' => array(
            'type' => 'string',
            'description' => 'ean13',
            'update' => true,
            'mapping' => array(
              's' => 'ean13'
            )
          ),
          'upc' => array(
            'type' => 'string',
            'description' => 'upc',
            'update' => true,
            'mapping' => array(
              's' => 'upc'
            )
          ),
          'physicalQuantity' => array(
            'type' => 'number',
            'description' => 'physical quantity',
            'update' => true,
            'mapping' => array(
              's' => 'physical_quantity'
            )
          ),
          'usableQuantity' => array(
            'type' => 'number',
            'description' => 'usable quantity',
            'update' => true,
            'mapping' => array(
              's' => 'usable_quantity'
            )
          ),
          'unitValuation' => array(
            'type' => 'currency',
            'description' => 'unit valuation',
            'sql' => array(
              'value'  => 's.price_te',
              'currency' => 'w.id_currency'
            ),
            'fixedCurrency' => true,
            'require' => array('s', 'w'),
            'update' => true,
            'mapping' => array(
              's' => array(
                'field' => array(
                  'value' => 'price_te'
                )
              )
            )
          )
        ),
        'expressions' => array(
          'valuation' => array(
            'type' => 'currency',
            'description' => 'valuation',
            'expression' => '<field:unitValuation> * <field:usableQuantity>'
          )
        ),
        'links' => array(
          'product' => array(
            'description' => "Product",
            'collection' => 'products',
            'type' => 'HAS_ONE',
            'sourceFields' => array('productId'),
            'targetFields' => array('id')
          ),
          'warehouse' => array(
            'description' => "Warehouse",
            'collection' => 'warehouses',
            'type' => 'BELONGS_TO',
            'sourceFields' => array('warehouseId'),
            'targetFields' => array('id')
          ),
          'combination' => array(
            'description' => "Combination",
            'collection' => 'combinations',
            'type' => 'HAS_ONE',
            'sourceFields' => array('combinationId'),
            'targetFields' => array('id')
          ),
          'movements' => array(
            'description' => "Stock movements",
            'collection' => 'stockMovements',
            'type' => 'HAS_MANY',
            'sourceFields' => array('id'),
            'targetFields' => array('stockId'),
            'delete' => true,
          )
        )
      ));
    }
  }
}
