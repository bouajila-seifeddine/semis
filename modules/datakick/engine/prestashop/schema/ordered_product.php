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

class OrderedProduct {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'orderedProducts',
      'singular' => 'orderedProduct',
      'description' => 'Ordered products',
      'key' => array('id'),
      'display' => 'name',
      'parameters' => array('shop', 'shopUrl'),
      'category' => 'sales',
      'psTab' => 'AdminOrders',
      'restrictions' => array(
        'shop' => array(
          'shop' => '<field:shopId>'
        )
      ),
      'tables' => array(
        'd' => array(
          'table' => 'order_detail',
        ),
        'o' => array(
          'table' => 'orders',
          'require' => array('d'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'o.id_order = d.id_order',
            )
          )
        )
      ),
      'conditions' => array(
        '<bind-param:shop:d.id_shop>'
      ),
      'joinConditions' => array(
        // join conditions override - no id_shop condition here
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'sql' => 'd.id_order_detail',
          'require' => array('d'),
          'selectRecord' => 'orderedProducts',
          'update' => false
        ),
        'shopId' => array(
          'type' => 'number',
          'description' => 'shop id',
          'sql' => 'd.id_shop',
          'require' => array('d'),
          'hidden' => ! SchemaUtils::isMultiShop(),
          'update' => false
        ),
        'currencyId' => array(
          'type' => 'number',
          'description' => 'currency id',
          'sql' => 'o.id_currency',
          'require' => array('o'),
          'selectRecord' => 'currencies',
          'update' => false,
        ),
        'orderId' => array(
          'type' => 'number',
          'description' => 'order id',
          'sql' => 'd.id_order',
          'require' => array('d'),
          'selectRecord' => 'orders',
          'update' => false
        ),
        'validatedOrder' => array(
          'type' => 'boolean',
          'description' => 'order is validated',
          'sql' => 'o.valid',
          'require' => array('o'),
          'update' => false
        ),
        'invoiceId' => array(
          'type' => 'number',
          'description' => 'invoice id',
          'sql' => 'd.id_order_invoice',
          'require' => array('d'),
          'update' => false
        ),
        'warehouseId' => array(
          'type' => 'number',
          'description' => 'warehouse id',
          'sql' => 'd.id_warehouse',
          'require' => array('d'),
          'selectRecord' => 'warehouses',
          'update' => false
        ),
        'productId' => array(
          'type' => 'number',
          'description' => 'product id',
          'sql' => 'd.product_id',
          'require' => array('d'),
          'selectRecord' => 'products',
          'update' => false
        ),
        'combinationId' => array(
          'type' => 'number',
          'description' => 'combination id',
          'sql' => 'd.product_attribute_id',
          'require' => array('d'),
          'selectRecord' => 'combinations',
          'update' => false
        ),
        'quantity' => array(
          'type' => 'number',
          'description' => 'quantity',
          'sql' => 'd.product_quantity',
          'require' => array('d'),
          'update' => false
        ),
        'onStock' => array(
          'type' => 'boolean',
          'description' => 'sufficient quantity on stock',
          'sql' => 'd.product_quantity_in_stock >= d.product_quantity',
          'require' => array('d'),
          'update' => false
        ),
        'returned' => array(
          'type' => 'number',
          'description' => 'quantity returned',
          'sql' => 'd.product_quantity_return',
          'require' => array('d'),
          'update' => false
        ),
        'refunded' => array(
          'type' => 'number',
          'description' => 'quantity refunded',
          'sql' => 'd.product_quantity_refunded',
          'require' => array('d'),
          'update' => false
        ),
        'discountPercent' => array(
          'type' => 'number',
          'description' => 'specific price - discount percentage',
          'sql' => 'd.reduction_percent',
          'require' => array('d'),
          'update' => false
        ),
        'discountAmount' => array(
          'type' => 'currency',
          'description' => 'specific price - discount amount',
          'sql' => array(
            'value' => 'd.reduction_amount_tax_incl',
            'currency' => 'o.id_currency'
          ),
          'require' => array('d', 'o'),
          'fixedCurrency' => false,
          'update' => false
        ),
        'discountAmountWithoutTax' => array(
          'type' => 'currency',
          'description' => 'specific price - discount amount without tax',
          'sql' => array(
            'value' => 'd.reduction_amount_tax_excl',
            'currency' => 'o.id_currency'
          ),
          'require' => array('d', 'o'),
          'fixedCurrency' => false,
          'update' => false
        ),
        'quantityDiscountApplied' => array(
          'type' => 'boolean',
          'description' => 'quantity discount applied',
          'sql' => 'd.discount_quantity_applied',
          'require' => array('d'),
          'update' => false
        ),
        'groupDiscountPercent' => array(
          'type' => 'number',
          'description' => 'group discount %',
          'sql' => 'd.group_reduction',
          'require' => array('d'),
          'update' => false
        ),
        'groupDiscount' => array(
          'type' => 'currency',
          'description' => 'group discount',
          'sql' => array(
            'value' => 'IF(d.group_reduction > 0, (d.group_reduction * d.total_price_tax_incl) / (100-d.group_reduction), 0)',
            'currency' => 'o.id_currency'
          ),
          'require' => array('d', 'o'),
          'fixedCurrency' => false,
          'update' => false
        ),
        'groupDiscountWithoutTax' => array(
          'type' => 'currency',
          'description' => 'group discount without tax',
          'sql' => array(
            'value' => 'IF(d.group_reduction > 0, (d.group_reduction * d.total_price_tax_excl) / (100-d.group_reduction), 0)',
            'currency' => 'o.id_currency'
          ),
          'fixedCurrency' => false,
          'require' => array('d', 'o'),
          'update' => false
        ),
        'productReference' => array(
          'type' => 'string',
          'description' => 'product reference',
          'sql' => 'd.product_reference',
          'require' => array('d'),
          'update' => array(
            'd' => 'product_reference'
          )
        ),
        'supplierReference' => array(
          'type' => 'string',
          'description' => 'product supplier reference',
          'sql' => 'd.product_supplier_reference',
          'require' => array('d'),
          'update' => array(
            'd' => 'product_supplier_reference'
          )
        ),
        'weight' => array(
          'type' => 'number',
          'description' => 'product weight',
          'sql' => 'd.product_weight',
          'require' => array('d'),
          'update' => array(
            'd' => 'product_weight'
          )
        ),
        'upc' => array(
          'type' => 'number',
          'description' => 'product UPC code',
          'sql' => 'd.product_upc',
          'require' => array('d'),
          'update' => array(
            'd' => 'product_upc'
          )
        ),
        'ean13' => array(
          'type' => 'string',
          'description' => 'product EAN 13/JAN Barcode',
          'sql' => 'd.product_upc',
          'require' => array('d'),
          'update' => array(
            'd' => 'product_upc'
          )
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'sql' => 'd.product_name',
          'require' => array('d'),
          'update' => array(
            'd' => 'product_name'
          )
        ),
        'isDownload' => array(
          'type' => 'boolean',
          'description' => 'is download',
          'sql' => 'COALESCE(d.download_hash != "", false)',
          'require' => array('d'),
          'update' => false
        ),
        'downloadHash' => array(
          'type' => 'string',
          'description' => 'download secure key',
          'sql' => 'd.download_hash',
          'require' => array('d'),
          'update' => array(
            'd' => 'download_hash'
          )
        ),
        'downloadCount' => array(
          'type' => 'number',
          'description' => 'number of downloads',
          'sql' => 'd.download_nb',
          'require' => array('d'),
          'update' => array(
            'd' => 'download_nb'
          )
        ),
        'downloadDeadline' => array(
          'type' => 'datetime',
          'description' => 'download deadline',
          'sql' => SchemaUtils::normalizeDate('d.download_deadline'),
          'require' => array('d'),
          'update' => array(
            'd' => 'download_deadline'
          )
        ),
        'ecotax' => array(
          'type' => 'currency',
          'description' => 'ecotax',
          'sql' => array(
            'value' => 'd.ecotax',
            'currency' => 'o.id_currency'
          ),
          'fixedCurrency' => false,
          'update' => false,
          'require' => array('d', 'o')
        ),
        'ecotaxRate' => array(
          'type' => 'number',
          'description' => 'ecotax rate',
          'sql' => 'd.ecotax_tax_rate',
          'update' => false,
          'require' => array('d')
        ),
        'unitPrice' => array(
          'type' => 'currency',
          'description' => 'unit price',
          'sql' => array(
            'value' => 'd.unit_price_tax_incl',
            'currency' => 'o.id_currency'
          ),
          'fixedCurrency' => false,
          'update' => false,
          'require' => array('d', 'o')
        ),
        'unitPriceWithoutTax' => array(
          'type' => 'currency',
          'description' => 'unit price tax excl.',
          'sql' => array(
            'value' => 'd.unit_price_tax_excl',
            'currency' => 'o.id_currency'
          ),
          'fixedCurrency' => false,
          'update' => false,
          'require' => array('d', 'o')
        ),
        'totalPrice' => array(
          'type' => 'currency',
          'description' => 'total price',
          'sql' => array(
            'value' => 'd.total_price_tax_incl',
            'currency' => 'o.id_currency'
          ),
          'fixedCurrency' => false,
          'update' => false,
          'require' => array('d', 'o')
        ),
        'totalPriceWithoutTax' => array(
          'type' => 'currency',
          'description' => 'total price tax excl.',
          'sql' => array(
            'value' => 'd.total_price_tax_excl',
            'currency' => 'o.id_currency'
          ),
          'fixedCurrency' => false,
          'update' => false,
          'require' => array('d', 'o')
        ),
        'shippingCost' => array(
          'type' => 'currency',
          'description' => 'shipping cost',
          'sql' => array(
            'value' => 'd.total_shipping_price_tax_incl',
            'currency' => 'o.id_currency'
          ),
          'fixedCurrency' => false,
          'update' => false,
          'require' => array('d', 'o')
        ),
        'shippingCostWithoutTax' => array(
          'type' => 'currency',
          'description' => 'shipping cost tax excl.',
          'sql' => array(
            'value' => 'd.total_shipping_price_tax_excl',
            'currency' => 'o.id_currency'
          ),
          'fixedCurrency' => false,
          'update' => false,
          'require' => array('d', 'o')
        ),
      ),
      'expressions' => array(
        'canDownload' => array(
          'type' => 'boolean',
          'expression' => "<field:isDownload> && <field:validatedOrder>",
          'description' => 'can download'
        ),
        'downloadLink' => array(
          'type' => 'string',
          'expression' => "if(<field:isDownload>, <param:shopUrl>+'index.php?controller=get-file&key=file-'+<field:downloadHash>,'')",
          'description' => 'download link'
        )
      ),
      'links' => array(
        'order' => array(
          'description' => "Order",
          'collection' => 'orders',
          'type' => 'BELONGS_TO',
          'sourceFields' => array('orderId'),
          'targetFields' => array('id')
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
        ),
      )
    ));
  }
}
