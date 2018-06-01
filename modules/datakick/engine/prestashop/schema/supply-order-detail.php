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

class SupplyOrderDetail {
  public function register($dictionary) {
    if (\Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
      $dictionary->registerCollection(array(
        'id' => 'supplyOrderDetails',
        'singular' => 'supplyOrderDetail',
        'description' => 'Supply Order Details',
        'key' => array('id'),
        'category' => 'stock',
        'psTab' => 'AdminSupplyOrders',
        'psController' => 'AdminSupplyOrders',
        'psClass' => 'SupplyOrder',
        'display' => 'name',
        'parameters' => array(),
        'restrictions' => array(
        ),
        'tables' => array(
          'd' => array(
            'table' => 'supply_order_detail'
          )
        ),
        'fields' => array(
          'id' => array(
            'type' => 'number',
            'description' => 'id',
            'sql' => 'd.id_supply_order_detail',
            'require' => array('d'),
            'selectRecord' => 'supplyOrderDetails',
            'update' => false
          ),
          'supplyOrderId' => array(
            'type' => 'number',
            'description' => 'supply order id',
            'sql' => 'd.id_supply_order',
            'require' => array('d'),
            'update' => false,
            'selectRecord' => 'supplyOrders',
          ),
          'currencyId' => array(
            'type' => 'number',
            'description' => 'currency id',
            'sql' => 'd.id_currency',
            'require' => array('d'),
            'update' => false,
            'selectRecord' => 'currencies',
          ),
          'productId' => array(
            'type' => 'number',
            'description' => 'product id',
            'sql' => 'd.id_product',
            'require' => array('d'),
            'update' => false,
            'selectRecord' => 'products',
          ),
          'combinationId' => array(
            'type' => 'number',
            'description' => 'combination id',
            'sql' => 'd.id_product_attribute',
            'require' => array('d'),
            'update' => false,
            'selectRecord' => 'combinations',
          ),
          'reference' => array(
            'type' => 'string',
            'description' => 'reference',
            'sql' => 'd.reference',
            'require' => array('d'),
            'update' => array(
              'd' => 'reference'
            )
          ),
          'supplierReference' => array(
            'type' => 'string',
            'description' => 'supplier reference',
            'sql' => 'd.supplier_reference',
            'require' => array('d'),
            'update' => array(
              'd' => 'supplier_reference'
            )
          ),
          'name' => array(
            'type' => 'string',
            'description' => 'name',
            'sql' => 'd.name',
            'require' => array('d'),
            'update' => array(
              'd' => 'name'
            )
          ),
          'ean13' => array(
            'type' => 'string',
            'description' => 'ean13',
            'sql' => 'd.ean13',
            'require' => array('d'),
            'update' => array(
              'd' => 'ean13'
            )
          ),
          'upc' => array(
            'type' => 'string',
            'description' => 'upc code',
            'sql' => 'd.upc',
            'require' => array('d'),
            'update' => array(
              'd' => 'upc'
            )
          ),
          'quantity' => array(
            'type' => 'number',
            'description' => 'quantity',
            'sql' => 'd.quantity_expected',
            'require' => array('d'),
            'update' => array(
              'd' => 'quantity_expected'
            )
          ),
          'received' => array(
            'type' => 'number',
            'description' => 'quantity received',
            'sql' => 'd.quantity_received',
            'require' => array('d'),
            'update' => array(
              'd' => 'quantity_received'
            )
          ),
          'exchangeRate' => array(
            'type' => 'number',
            'description' => 'exchange rate',
            'sql' => 'd.exchange_rate',
            'require' => array('d'),
            'update' => array(
              'd' => 'exchange_rate'
            )
          ),
          'discountRate' => array(
            'type' => 'number',
            'description' => 'discount rate',
            'sql' => 'd.discount_rate',
            'require' => array('d'),
            'update' => array(
              'd' => 'discount_rate'
            )
          ),
          'discount' => array(
            'type' => 'currency',
            'description' => 'discount',
            'sql' => array(
              'value' => 'd.discount_value_te',
              'currency' => 'd.id_currency'
            ),
            'fixedCurrency' => true,
            'require' => array('d'),
            'update' => false
          ),
          'price' => array(
            'type' => 'currency',
            'description' => 'price',
            'sql' => array(
              'value' => 'd.price_te',
              'currency' => 'd.id_currency'
            ),
            'fixedCurrency' => true,
            'require' => array('d'),
            'update' => false
          ),
          'priceWithTax' => array(
            'type' => 'currency',
            'description' => 'price with tax',
            'sql' => array(
              'value' => 'd.price_ti',
              'currency' => 'd.id_currency'
            ),
            'fixedCurrency' => true,
            'require' => array('d'),
            'update' => false
          ),
          'discountedPrice' => array(
            'type' => 'currency',
            'description' => 'price with discount',
            'sql' => array(
              'value' => 'd.price_with_discount_te',
              'currency' => 'd.id_currency'
            ),
            'fixedCurrency' => true,
            'require' => array('d'),
            'update' => false
          ),
          'taxRate' => array(
            'type' => 'number',
            'description' => 'tax rate',
            'sql' => 'd.tax_rate',
            'require' => array('d'),
            'update' => array(
              'd' => 'tax_rate'
            )
          ),
          'tax' => array(
            'type' => 'currency',
            'description' => 'tax',
            'sql' => array(
              'value' => 'd.tax_value',
              'currency' => 'd.id_currency'
            ),
            'fixedCurrency' => true,
            'require' => array('d'),
            'update' => false
          ),
          'unitPrice' => array(
            'type' => 'currency',
            'description' => 'unit price',
            'sql' => array(
              'value' => 'd.unit_price_te',
              'currency' => 'd.id_currency'
            ),
            'fixedCurrency' => true,
            'require' => array('d'),
            'update' => false
          ),
        ),
        'expressions' => array(
        ),
        'links' => array(
          'supplyOrder' => array(
            'description' => "Supply Order",
            'collection' => 'supplyOrders',
            'type' => 'BELONGS_TO',
            'sourceFields' => array('supplyOrderId'),
            'targetFields' => array('id')
          ),
          'currency' => array(
            'description' => "Currency",
            'collection' => 'currencies',
            'type' => 'BELONGS_TO',
            'sourceFields' => array('currencyId'),
            'targetFields' => array('id'),
            'unidirectional' => true
          ),
          'product' => array(
            'description' => "Product",
            'collection' => 'products',
            'type' => 'HAS_ONE',
            'sourceFields' => array('productId'),
            'targetFields' => array('id')
          ),
          'combination' => array(
            'description' => "Combination",
            'collection' => 'combinations',
            'type' => 'HAS_ONE',
            'sourceFields' => array('combinationId'),
            'targetFields' => array('id')
          )
        )
      ));
    }
  }
}
