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

class SupplyOrder {
  public function register($dictionary) {
    if (\Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
      $dictionary->registerCollection(array(
        'id' => 'supplyOrders',
        'singular' => 'supplyOrder',
        'description' => 'Supply Orders',
        'key' => array('id'),
        'category' => 'stock',
        'psTab' => 'AdminSupplyOrders',
        'psController' => 'AdminSupplyOrders',
        'psClass' => 'SupplyOrder',
        'display' => 'reference',
        'parameters' => array(),
        'restrictions' => array(
        ),
        'tables' => array(
          'so' => array(
            'table' => 'supply_order'
          ),
          'state' => array(
            'table' => 'supply_order_state',
            'require' => array('so'),
            'join' => array(
              'type' => 'INNER',
              'conditions' => array(
                'state.id_supply_order_state = so.id_supply_order_state'
              )
            )
          ),
          'lang' => array(
            'table' => 'lang',
            'require' => array('so'),
            'join' => array(
              'type' => 'INNER',
              'conditions' => array(
                'so.id_lang = lang.id_lang'
              )
            )
          ),
          's' => array(
            'table' => 'supplier',
            'require' => array('so'),
            'join' => array(
              'type' => 'LEFT',
              'conditions' => array(
                'so.id_supplier = s.id_supplier'
              )
            )
          )
        ),
        'fields' => array(
          'id' => array(
            'type' => 'number',
            'description' => 'id',
            'sql' => 'so.id_supply_order',
            'require' => array('so'),
            'selectRecord' => 'supplyOrders',
            'update' => false
          ),
          'template' => array(
            'type' => 'boolean',
            'description' => 'is template',
            'sql' => 'so.is_template',
            'require' => array('so'),
            'update' => array(
              'so' => 'is_template'
            )
          ),
          'editable' => array(
            'type' => 'boolean',
            'description' => 'is editable',
            'sql' => 'state.editable',
            'require' => array('state'),
            'update' => false
          ),
          'closed' => array(
            'type' => 'boolean',
            'description' => 'is closed',
            'sql' => 'state.enclosed',
            'require' => array('state'),
            'update' => false
          ),
          'pendingDelivery' => array(
            'type' => 'boolean',
            'description' => 'pending delivery',
            'sql' => 'state.pending_receipt',
            'require' => array('state'),
            'update' => false
          ),
          'delivered' => array(
            'type' => 'boolean',
            'description' => 'stock delivered',
            'sql' => 'state.receipt_state',
            'require' => array('state'),
            'update' => false
          ),
          'warehouseId' => array(
            'type' => 'number',
            'description' => 'warehouse id',
            'sql' => 'so.id_warehouse',
            'require' => array('so'),
            'update' => false,
            'selectRecord' => 'warehouses',
          ),
          'supplierId' => array(
            'type' => 'number',
            'description' => 'supplier id',
            'sql' => 'so.id_supplier',
            'require' => array('so'),
            'update' => false,
            'selectRecord' => 'suppliers',
          ),
          'supplier' => array(
            'type' => 'string',
            'description' => 'supplier',
            'sql' => 'coalesce(s.name, so.supplier_name)',
            'require' => array('so', 's'),
            'update' => false,
          ),
          'currencyId' => array(
            'type' => 'number',
            'description' => 'currency id',
            'sql' => 'so.id_currency',
            'require' => array('so'),
            'update' => false,
            'selectRecord' => 'currencies',
          ),
          'languageId' => array(
            'type' => 'number',
            'description' => 'language id',
            'sql' => 'so.id_lang',
            'require' => array('so'),
            'update' => false,
            'selectRecord' => 'languages',
          ),
          'language' => array(
            'type' => 'string',
            'description' => 'order language',
            'sql' => 'lang.name',
            'require' => array('lang'),
            'update' => false,
          ),
          'reference' => array(
            'type' => 'string',
            'description' => 'reference',
            'sql' => 'so.reference',
            'require' => array('so'),
            'update' => array(
              'so' => 'reference'
            )
          ),
          'created' => array(
            'type' => 'datetime',
            'description' => 'date created',
            'sql' => 'so.date_add',
            'require' => array('so'),
            'update' => false
          ),
          'updated' => array(
            'type' => 'datetime',
            'description' => 'date updated',
            'sql' => 'so.date_upd',
            'require' => array('so'),
            'update' => array(
              'so' => 'date_upd'
            )
          ),
          'deliveryDate' => array(
            'type' => 'datetime',
            'description' => 'expected delivery date',
            'sql' => 'so.date_delivery_expected',
            'require' => array('so'),
            'update' => array(
              'so' => 'date_delivery_expected'
            )
          ),
          'total' => array(
            'type' => 'currency',
            'description' => 'total',
            'sql' => array(
              'value' => 'so.total_te',
              'currency' => 'so.id_currency'
            ),
            'fixedCurrency' => true,
            'require' => array('so'),
            'update' => false
          ),
          'totalWithDiscount' => array(
            'type' => 'currency',
            'description' => 'total with discount',
            'sql' => array(
              'value' => 'so.total_with_discount_te',
              'currency' => 'so.id_currency'
            ),
            'fixedCurrency' => true,
            'require' => array('so'),
            'update' => false
          ),
          'totalTax' => array(
            'type' => 'currency',
            'description' => 'total tax',
            'sql' => array(
              'value' => 'so.total_tax',
              'currency' => 'so.id_currency'
            ),
            'fixedCurrency' => true,
            'require' => array('so'),
            'update' => false
          ),
          'totalWithTax' => array(
            'type' => 'currency',
            'description' => 'total with tax',
            'sql' => array(
              'value' => 'so.total_ti',
              'currency' => 'so.id_currency'
            ),
            'fixedCurrency' => true,
            'require' => array('so'),
            'update' => false
          ),
          'discountRate' => array(
            'type' => 'number',
            'description' => 'discount rate %',
            'sql' => 'so.discount_rate',
            'require' => array('so'),
            'update' => array(
              'so' => 'discount_rate'
            )
          ),
        ),
        'expressions' => array(
          'fullDelivery' => array(
            'type' => 'boolean',
            'description' => 'delivered in full',
            'expression' => '<field:delivered> && not(<field:pendingDelivery>)'
          ),
          'partiallyDelivered' => array(
            'type' => 'boolean',
            'description' => 'partially delivered',
            'expression' => '<field:delivered> && <field:pendingDelivery>'
          ),
        ),
        'links' => array(
          'warehouse' => array(
            'description' => "Warehouse",
            'collection' => 'warehouses',
            'type' => 'BELONGS_TO',
            'sourceFields' => array('warehouseId'),
            'targetFields' => array('id')
          ),
          'supplier' => array(
            'description' => "Supplier",
            'collection' => 'suppliers',
            'type' => 'BELONGS_TO',
            'sourceFields' => array('supplierId'),
            'targetFields' => array('id')
          ),
          'language' => array(
            'description' => "Language",
            'collection' => 'languages',
            'type' => 'BELONGS_TO',
            'sourceFields' => array('languageId'),
            'targetFields' => array('id'),
            'unidirectional' => true
          ),
          'currency' => array(
            'description' => "Currency",
            'collection' => 'currencies',
            'type' => 'BELONGS_TO',
            'sourceFields' => array('currencyId'),
            'targetFields' => array('id'),
            'unidirectional' => true
          ),
          'orderedProducts' => array(
            'description' => "Ordered products",
            'collection' => 'supplyOrderDetails',
            'type' => 'HAS_MANY',
            'sourceFields' => array('id'),
            'targetFields' => array('supplyOrderId')
          ),
        )
      ));
    }
  }
}
