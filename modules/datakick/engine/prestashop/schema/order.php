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

class Order {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'orders',
      'singular' => 'order',
      'description' => 'Orders',
      'key' => array('id'),
      'display' => 'description',
      'parameters' => array('shop', 'language', 'shareOrders', 'shopGroup'),
      'category' => 'sales',
      'priority' => 400,
      'psTab' => 'AdminOrders',
      'restrictions' => array(
        'shop' => array(
          'shop' => '<field:shopId>'
        )
      ),
      'tables' => array(
        'o' => array(
          'table' => 'orders',
          'conditions' => array(
            "if(<bind-param:shareOrders:1>, <bind-param:shopGroup:o.id_shop_group>, <bind-param:shop:o.id_shop>)"
          )
        ),
        'stl' => array(
          'table' => 'order_state_lang',
          'require' => array('o'),
          'join' => array(
            'type' => 'LEFT',
            'conditions' => array(
              'o.current_state = stl.id_order_state',
              '<bind-param:language:stl.id_lang>'
            )
          )
        ),
        's' => array(
          'table' => 'shop',
          'require' => array('o'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'o.id_shop = s.id_shop'
            )
          )
        ),
        'c' => array(
          'table' => 'customer',
          'require' => array('o'),
          'join' => array(
            'type' => 'LEFT',
            'conditions' => array(
              'o.id_customer = c.id_customer'
            )
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'sql' => 'o.id_order',
          'require' => array('o'),
          'selectRecord' => 'orders',
          'update' => false,
        ),
        'customerId' => array(
          'type' => 'number',
          'description' => 'customer id',
          'sql' => 'o.id_customer',
          'require' => array('o'),
          'selectRecord' => 'customers',
          'update' => false,
        ),
        'carrierId' => array(
          'type' => 'number',
          'description' => 'carrier id',
          'sql' => 'o.id_carrier',
          'require' => array('o'),
          'selectRecord' => 'carriers',
          'update' => false,
        ),
        'cartId' => array(
          'type' => 'number',
          'description' => 'cart id',
          'sql' => 'o.id_cart',
          'require' => array('o'),
          'selectRecord' => 'carts',
          'update' => false,
        ),
        'currencyId' => array(
          'type' => 'number',
          'description' => 'currency id',
          'sql' => 'o.id_currency',
          'require' => array('o'),
          'selectRecord' => 'currencies',
          'update' => false,
        ),
        'deliveryAddressId' => array(
          'type' => 'number',
          'description' => 'delivery address id',
          'sql' => 'o.id_address_delivery',
          'require' => array('o'),
          'selectRecord' => 'addresses',
          'update' => false,
        ),
        'invoiceAddressId' => array(
          'type' => 'number',
          'description' => 'invoice address id',
          'sql' => 'o.id_address_invoice',
          'require' => array('o'),
          'selectRecord' => 'addresses',
          'update' => false,
        ),
        'shopId' => array(
          'type' => 'number',
          'description' => 'shop id',
          'sql' => 'o.id_shop',
          'require' => array('o'),
          'hidden' => true,
          'update' => false,
        ),
        'shop' => array(
          'type' => 'string',
          'description' => 'shop',
          'sql' => 's.name',
          'require' => array('s'),
          'hidden' => ! SchemaUtils::isMultiShop(),
          'update' => false,
        ),
        'customer' => array(
          'type' => 'string',
          'description' => 'customer',
          'sql' => 'CONCAT(c.firstname, " ", c.lastname)',
          'require' => array('c'),
          'update' => false,
        ),
        'description' => array(
          'type' => 'string',
          'description' => 'order description',
          'sql' => 'TRIM(CONCAT("Order ", o.reference, IFNULL(CONCAT(" from ", c.firstname, " ", c.lastname), "")))',
          'require' => array('c', 'o'),
          'update' => false
        ),
        'statusId' => array(
          'type' => 'number',
          'description' => 'current status id',
          'sql' => 'o.current_state',
          'require' => array('o'),
          'update' => array(
            'o' => 'current_state'
          ),
        ),
        'status' => array(
          'type' => 'string',
          'description' => 'status',
          'sql' => 'stl.name',
          'require' => array('stl'),
          'update' => array()
        ),
        'trackingNumber' => array(
          'type' => 'string',
          'description' => 'tracking number',
          'sql' => 'o.shipping_number',
          'require' => array('o'),
          'update' => array(
            'o' => 'shipping_number'
          )
        ),
        'secureKey' => array(
          'type' => 'string',
          'description' => 'secure key',
          'sql' => 'o.secure_key',
          'require' => array('o'),
          'update' => array(
            'o' => 'secure_key'
          )
        ),
        'reference' => array(
          'type' => 'string',
          'description' => 'reference',
          'sql' => 'o.reference',
          'require' => array('o'),
          'update' => array(
            'o' => 'reference'
          )
        ),
        'payment' => array(
          'type' => 'string',
          'description' => 'payment type',
          'sql' => 'o.payment',
          'require' => array('o'),
          'update' => array(
            'o' => 'payment'
          )
        ),
        'validated' => array(
          'type' => 'boolean',
          'description' => 'is validated',
          'sql' => 'o.valid',
          'require' => array('o'),
          'update' => array(
            'o' => 'valid'
          )
        ),
        'gift' => array(
          'type' => 'boolean',
          'description' => 'gift wrapping',
          'sql' => 'o.gift',
          'require' => array('o'),
          'update' => array(
            'o' => 'gift'
          )
        ),
        'recyclable' => array(
          'type' => 'boolean',
          'description' => 'use recycled packaging',
          'sql' => 'o.recyclable',
          'require' => array('o'),
          'update' => array(
            'o' => 'recyclable'
          )
        ),
        'giftMessage' => array(
          'type' => 'string',
          'description' => 'gift message',
          'sql' => 'o.gift_message',
          'require' => array('o'),
          'update' => array(
            'o' => 'gift_message'
          )
        ),
        'created' => array(
          'type' => 'datetime',
          'description' => 'date created',
          'sql' => 'o.date_add',
          'require' => array('o'),
          'update' => false
        ),
        'updated' => array(
          'type' => 'datetime',
          'description' => 'date updated',
          'sql' => 'o.date_upd',
          'require' => array('o'),
          'update' => false
        ),
        'invoiceNumber' => array(
          'type' => 'number',
          'description' => 'invoice number',
          'sql' => 'IF(o.invoice_number = 0, NULL, o.invoice_number)',
          'require' => array('o'),
          'update' => false,
        ),
        'invoiceDate' => array(
          'type' => 'datetime',
          'description' => 'invoice date',
          'sql' => SchemaUtils::normalizeDate('o.invoice_date'),
          'require' => array('o'),
          'update' => false
        ),
        'deliveryDate' => array(
          'type' => 'datetime',
          'description' => 'delivery date',
          'sql' => SchemaUtils::normalizeDate('o.delivery_date'),
          'require' => array('o'),
          'update' => false
        ),
        'deliveryNumber' => array(
          'type' => 'number',
          'description' => 'delivery number',
          'sql' => 'IF(o.delivery_number = 0, NULL, o.delivery_number)',
          'require' => array('o'),
          'update' => false
        ),
        'conversionRate' => array(
          'type' => 'number',
          'description' => 'conversion rate',
          'sql' => 'o.conversion_rate',
          'require' => array('o'),
          'update' => false
        ),
        'totalPaid' => array(
          'type' => 'currency',
          'description' => 'total paid',
          'sql' => array(
            'value' => 'o.total_paid_real',
            'currency' => 'o.id_currency'
          ),
          'require' => array('o'),
          'update' => false,
          'fixedCurrency' => false
        ),
        'total' => array(
          'type' => 'currency',
          'description' => 'total',
          'sql' => array(
            'value' => 'o.total_paid_tax_incl',
            'currency' => 'o.id_currency'
          ),
          'require' => array('o'),
          'update' => false,
          'fixedCurrency' => false
        ),
        'totalWithoutTax' => array(
          'type' => 'currency',
          'description' => 'total tax excl.',
          'sql' => array(
            'value' => 'o.total_paid_tax_excl',
            'currency' => 'o.id_currency'
          ),
          'require' => array('o'),
          'update' => false,
          'fixedCurrency' => false
        ),
        'totalProducts' => array(
          'type' => 'currency',
          'description' => 'total products',
          'sql' => array(
            'value' => 'o.total_products_wt',
            'currency' => 'o.id_currency'
          ),
          'require' => array('o'),
          'update' => false,
          'fixedCurrency' => false
        ),
        'totalProductsWithoutTax' => array(
          'type' => 'currency',
          'description' => 'total products tax excl.',
          'sql' => array(
            'value' => 'o.total_products',
            'currency' => 'o.id_currency'
          ),
          'require' => array('o'),
          'update' => false,
          'fixedCurrency' => false
        ),
        'totalShipping' => array(
          'type' => 'currency',
          'description' => 'total shipping',
          'sql' => array(
            'value' => 'o.total_shipping_tax_incl',
            'currency' => 'o.id_currency'
          ),
          'require' => array('o'),
          'update' => false,
          'fixedCurrency' => false
        ),
        'totalShippingWithoutTax' => array(
          'type' => 'currency',
          'description' => 'total shipping tax excl.',
          'sql' => array(
            'value' => 'o.total_shipping_tax_excl',
            'currency' => 'o.id_currency'
          ),
          'require' => array('o'),
          'update' => false,
          'fixedCurrency' => false
        ),
        'totalWrapping' => array(
          'type' => 'currency',
          'description' => 'total wrapping',
          'sql' => array(
            'value' => 'o.total_wrapping_tax_incl',
            'currency' => 'o.id_currency'
          ),
          'require' => array('o'),
          'update' => false,
          'fixedCurrency' => false
        ),
        'totalWrappingWithoutTax' => array(
          'type' => 'currency',
          'description' => 'total wrapping tax excl.',
          'sql' => array(
            'value' => 'o.total_wrapping_tax_excl',
            'currency' => 'o.id_currency'
          ),
          'require' => array('o'),
          'update' => false,
          'fixedCurrency' => false
        ),
        'totalDiscounts' => array(
          'type' => 'currency',
          'description' => 'total discounts',
          'sql' => array(
            'value' => 'o.total_discounts_tax_incl',
            'currency' => 'o.id_currency'
          ),
          'require' => array('o'),
          'update' => false,
          'fixedCurrency' => false
        ),
        'totalDiscountsWithoutTax' => array(
          'type' => 'currency',
          'description' => 'total discounts tax excl.',
          'sql' => array(
            'value' => 'o.total_discounts_tax_excl',
            'currency' => 'o.id_currency'
          ),
          'require' => array('o'),
          'update' => false,
          'fixedCurrency' => false
        )
      ),
      'expressions' => array(
        'sameAddresses' => array(
          'type' => 'boolean',
          'description' => "same invoice and delivery address",
          'expression' => '<field:deliveryAddressId> = <field:invoiceAddressId>',
        ),
        'totalShopCurrency' => array(
          'type' => 'currency',
          'description' => "total [shop currency]",
          'expression' => 'toCurrency(toNumber(<field:total>) / <field:conversionRate>, <param:defaultCurrency>)',
        )
      ),
      'links' => array(
        'customer' => array(
          'description' => "Customer",
          'collection' => 'customers',
          'type' => 'HAS_ONE',
          'sourceFields' => array('customerId'),
          'targetFields' => array('id')
        ),
        'currency' => array(
          'description' => "Used currency",
          'collection' => 'currencies',
          'type' => 'HAS_ONE',
          'sourceFields' => array('currencyId'),
          'targetFields' => array('id')
        ),
        'carrier' => array(
          'description' => "Used carrier",
          'collection' => 'carriers',
          'type' => 'HAS_ONE',
          'sourceFields' => array('carrierId'),
          'targetFields' => array('id')
        ),
        'deliveryAddress' => array(
          'description' => "Delivery address",
          'collection' => 'addresses',
          'type' => 'HAS_ONE',
          'sourceFields' => array('deliveryAddressId'),
          'targetFields' => array('id')
        ),
        'invoiceAddress' => array(
          'description' => "Invoice address",
          'collection' => 'addresses',
          'type' => 'HAS_ONE',
          'sourceFields' => array('invoiceAddressId'),
          'targetFields' => array('id')
        ),
        'cart' => array(
          'description' => "Cart",
          'collection' => 'carts',
          'type' => 'HAS_ONE',
          'sourceFields' => array('cartId'),
          'targetFields' => array('id')
        ),
        'orderedProducts' => array(
          'description' => "Ordered products",
          'collection' => 'orderedProducts',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('orderId')
        ),
        'stockMovements' => array(
          'description' => "Stock movements",
          'collection' => 'stockMovements',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('orderId')
        )
      )
    ));
  }
}
