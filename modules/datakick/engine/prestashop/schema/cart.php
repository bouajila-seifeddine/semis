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

class Cart {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'carts',
      'singular' => 'cart',
      'description' => 'Shopping Carts',
      'key' => array('id'),
      'display' => 'description',
      'parameters' => array('shop', 'shareOrders', 'shopGroup'),
      'category' => 'sales',
      'psTab' => 'AdminCarts',
      'delete' => true,
      'restrictions' => array(
        'shop' => array(
          'shop' => '<field:shopId>'
        )
      ),
      'tables' => array(
        'c' => array(
          'table' => 'cart',
          'conditions' => array(
            "if(<bind-param:shareOrders:1>, <bind-param:shopGroup:c.id_shop_group>, <bind-param:shop:c.id_shop>)"
          )
        ),
        's' => array(
          'table' => 'shop',
          'require' => array('c'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'c.id_shop = s.id_shop'
            )
          )
        ),
        'cust' => array(
          'table' => 'customer',
          'require' => array('c'),
          'join' => array(
            'type' => 'LEFT',
            'conditions' => array(
              'cust.id_customer = c.id_customer'
            )
          )
        ),
        'o' => array(
          'table' => 'orders',
          'require' => array('c'),
          'join' => array(
            'type' => 'LEFT',
            'conditions' => array(
              'o.id_cart = c.id_cart'
            )
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'mapping' => array(
            'c' => 'id_cart'
          ),
          'selectRecord' => 'carts',
          'update' => false,
        ),
        'customerId' => array(
          'type' => 'number',
          'description' => 'customer id',
          'selectRecord' => 'customers',
          'update' => true,
          'mapping' => array(
            'c' => 'id_customer'
          )
        ),
        'orderId' => array(
          'type' => 'number',
          'description' => 'order id',
          'mapping' => array(
            'o' => 'id_order'
          ),
          'selectRecord' => 'orders',
          'update' => false,
        ),
        'visitorId' => array(
          'type' => 'number',
          'description' => 'visitor id',
          'selectRecord' => 'visitors',
          'update' => true,
          'mapping' => array(
            'c' => 'id_guest'
          )
        ),
        'carrierId' => array(
          'type' => 'number',
          'description' => 'carrier id',
          'mapping' => array('c' => 'id_carrier'),
          'selectRecord' => 'carriers',
          'update' => true,
        ),
        'currencyId' => array(
          'type' => 'number',
          'description' => 'currency id',
          'mapping' => array('c' => 'id_currency'),
          'selectRecord' => 'currencies',
          'update' => true,
        ),
        'items' => array(
          'type' => 'number',
          'description' => 'number of items',
          'sql' => '(SELECT COUNT(1) FROM '._DB_PREFIX_.'cart_product cp WHERE cp.id_cart = c.id_cart)',
          'require' => array('c'),
          'update' => false,
        ),
        'quantity' => array(
          'type' => 'number',
          'description' => 'total quantity',
          'sql' => '(SELECT IFNULL(SUM(quantity), 0) FROM '._DB_PREFIX_.'cart_product cp WHERE cp.id_cart = c.id_cart)',
          'require' => array('c'),
          'update' => false,
        ),
        'languageId' => array(
          'type' => 'number',
          'description' => 'language id',
          'mapping' => array('c' => 'id_lang'),
          'hidden' => true,
          'update' => true,
        ),
        'deliveryAddressId' => array(
          'type' => 'number',
          'description' => 'delivery address id',
          'mapping' => array('c' => 'id_address_delivery'),
          'selectRecord' => 'addresses',
          'update' => true,
        ),
        'invoiceAddressId' => array(
          'type' => 'number',
          'description' => 'invoice address id',
          'mapping' => array('c' => 'id_address_invoice'),
          'selectRecord' => 'addresses',
          'update' => true,
        ),
        'shopId' => array(
          'type' => 'number',
          'description' => 'shop id',
          'mapping' => array('c' => 'id_shop'),
          'hidden' => true,
          'update' => false,
        ),
        'shop' => array(
          'type' => 'string',
          'description' => 'shop',
          'mapping' => array('s' => 'name'),
          'hidden' => ! SchemaUtils::isMultiShop(),
          'update' => false,
        ),
        'customer' => array(
          'type' => 'string',
          'description' => 'customer',
          'sql' => 'CONCAT(cust.firstname, " ", cust.lastname)',
          'require' => array('cust'),
          'update' => false,
        ),
        'description' => array(
          'type' => 'string',
          'description' => 'cart description',
          'sql' => 'TRIM(CONCAT("Cart #", c.id_cart, IFNULL(CONCAT(" from ", cust.firstname, " ", cust.lastname), "")))',
          'require' => array('cust', 'c'),
          'update' => false
        ),
        'secureKey' => array(
          'type' => 'string',
          'description' => 'secure key',
          'update' => true,
          'mapping' => array(
            'c' => 'secure_key'
          )
        ),
        'deliveryOption' => array(
          'type' => 'string',
          'description' => 'delivery option',
          'update' => true,
          'mapping' => array(
            'c' => 'delivery_option'
          )
        ),
        'gift' => array(
          'type' => 'boolean',
          'description' => 'gift wrapping',
          'update' => true,
          'mapping' => array(
            'c' => 'gift'
          )
        ),
        'recyclable' => array(
          'type' => 'boolean',
          'description' => 'use recycled packaging',
          'update' => true,
          'mapping' => array(
            'c' => 'recyclable'
          )
        ),
        'giftMessage' => array(
          'type' => 'string',
          'description' => 'gift message',
          'update' => true,
          'mapping' => array(
            'c' => 'gift_message'
          )
        ),
        'allowSeparatedPackage' => array(
          'type' => 'boolean',
          'description' => 'allow separated package',
          'update' => true,
          'mapping' => array(
            'c' => 'allow_seperated_package'
          )
        ),
        'created' => array(
          'type' => 'datetime',
          'description' => 'date created',
          'mapping' => array('c' => 'date_add'),
          'update' => true
        ),
        'updated' => array(
          'type' => 'datetime',
          'description' => 'date updated',
          'mapping' => array('c' => 'date_upd'),
          'update' => true
        )
      ),
      'expressions' => array(
        'hasOrder' => array(
          'type' => 'boolean',
          'description' => 'has order',
          'expression' => 'not(isEmpty(<field:orderId>))',
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
        'visitor' => array(
          'description' => "Visitor",
          'collection' => 'visitors',
          'type' => 'HAS_ONE',
          'sourceFields' => array('visitorId'),
          'targetFields' => array('id')
        ),
        'currency' => array(
          'description' => "Used currency",
          'collection' => 'currencies',
          'type' => 'HAS_ONE',
          'sourceFields' => array('currencyId'),
          'targetFields' => array('id'),
          'unidirectional' => true
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
          'targetFields' => array('id'),
          'unidirectional' => true
        ),
        'invoiceAddress' => array(
          'description' => "Invoice address",
          'collection' => 'addresses',
          'type' => 'HAS_ONE',
          'sourceFields' => array('invoiceAddressId'),
          'targetFields' => array('id'),
          'unidirectional' => true
        ),
        'order' => array(
          'description' => "Order",
          'collection' => 'orders',
          'type' => 'HAS_ONE',
          'sourceFields' => array('id'),
          'targetFields' => array('cartId')
        ),
        'products' => array(
          'description' => 'Products in cart',
          'collection' => 'products',
          'type' => 'HABTM',
          'sourceFields' => array('id'),
          'targetFields' => array('id'),
          'joinTable' => 'cart_product',
          'joinFields' => array(
            'sourceFields' => array('id_cart'),
            'targetFields' => array('id_product'),
          ),
          'delete' => true,
          'create' => true
        ),
        'combinations' => array(
          'description' => 'Product combinations in cart',
          'collection' => 'combinations',
          'type' => 'HABTM',
          'sourceFields' => array('id'),
          'targetFields' => array('id'),
          'joinTable' => 'cart_product',
          'joinFields' => array(
            'sourceFields' => array('id_cart'),
            'targetFields' => array('id_product_attribute'),
          ),
          'joinConditions' => array(
            '<join:id_product_attribute> != 0'
          ),
          'delete' => false,
          'create' => true
        )
      )
    ));
  }
}
