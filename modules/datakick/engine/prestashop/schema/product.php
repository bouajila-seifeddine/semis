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

class Product {
  public function register($dictionary) {
    $ps17 = version_compare(_PS_VERSION_, '1.7.0', '>=');
    $useImageShop = version_compare(_PS_VERSION_, '1.6.1', '>=');
    $packActive = \Pack::isFeatureActive();
    $categoryProduct = _DB_PREFIX_.'cateogory_product';

    $imageIdSql;
    if ($useImageShop) {
      $imageIdSql = '(SELECT i.id_image FROM '._DB_PREFIX_.'image_shop AS i WHERE i.id_shop = ps.id_shop AND i.id_product = ps.id_product ORDER BY i.cover DESC, i.id_image ASC LIMIT 1)';
      $hasCombinations = 'EXISTS(SELECT 1 FROM '._DB_PREFIX_.'product_attribute_shop AS pas WHERE pas.id_shop = ps.id_shop AND pas.id_product = ps.id_product)';
    } else {
      $imageIdSql = '(SELECT i.id_image FROM '._DB_PREFIX_.'image AS i WHERE i.id_product = ps.id_product ORDER BY i.cover DESC, i.id_image ASC LIMIT 1)';
      $hasCombinations = 'EXISTS(SELECT 1 FROM '._DB_PREFIX_.'product_attribute AS pa WHERE pa.id_product = ps.id_product)';
    }

    $idProductRedirected = $ps17 ? 'id_type_redirected': 'id_product_redirected';

    $dictionary->registerCollection(array(
      'id' => 'products',
      'singular' => 'product',
      'description' => 'Products',
      'parameters' => array('shop', 'shopGroup', 'stockManagement', 'shareStock', 'allowOrderOutOfStock', 'defaultCurrency', 'language'),
      'key' => array('id'),
      'priority' => 100,
      'display' => 'name',
      'category' => 'catalog',
      'psTab' => 'AdminProducts',
      'psController' => 'AdminProducts',
      'psClass' => 'Product',
      'psDrilldownParams' => array(
        'id_product' => '<key>',
        'updateproduct' => true
      ),
      'create' => true,
      'delete' => array(
        'value' => true,
        'extraTables' => array(
          array(
            'table' => 'specific_price',
            'fkeys' => array('id_product')
          ),
          array(
            'table' => 'specific_price_priority',
            'fkeys' => array('id_product')
          ),
          array(
            'table' => 'compare_product',
            'fkeys' => array('id_product')
          ),
          array(
            'table' => 'product_attachment',
            'fkeys' => array('id_product')
          ),
          array(
            'table' => 'product_country_tax',
            'fkeys' => array('id_product')
          ),
          array(
            'table' => 'product_download',
            'fkeys' => array('id_product')
          ),
          array(
            'table' => 'product_group_reduction_cache',
            'fkeys' => array('id_product')
          ),
          array(
            'table' => 'product_sale',
            'fkeys' => array('id_product')
          ),
          array(
            'table' => 'scene_products',
            'fkeys' => array('id_product')
          ),
          array(
            'table' => 'warehouse_product_location',
            'fkeys' => array('id_product')
          ),
          array(
            'table' => 'customization',
            'fkeys' => array('id_product')
          ),
          array(
            'table' => 'customization_field',
            'fkeys' => array('id_product')
          ),
          array(
            'table' => 'attribute_impact',
            'fkeys' => array('id_product')
          ),
          array(
            'table' => 'pack',
            'fkeys' => array('id_product_pack')
          ),
          array(
            'table' => 'pack',
            'fkeys' => array('id_product_item')
          ),
        ),
      ),
      'restrictions' => array(
        'shop' => array(
          'shop' => '<field:shopId>'
        )
      ),
      'callbacks' => array(
        'beforeCreate' => array($this, 'beforeCreate'),
      ),
      'tables' => array(
        'p' => array(
          'table' => 'product',
          'create' => array(
            'condition' => "'new'",
            'id_category_default' => \Configuration::get('PS_HOME_CATEGORY'),
            'active' => true,
            'available_for_order' => true,
            'visibility' => "'both'",
            'minimal_quantity' => 1,
            'indexed' => 1,
            'date_add' => '<param:timestamp>',
            'date_upd' => '<param:timestamp>',
          )
        ),
        'ps' => array(
          'table' => 'product_shop',
          'primary' => true,
          'parameters' => array('shop'),
          'create' => array(
            'id_shop' => '<param:shop>',
            'id_product' => '<pk>',
            'id_category_default' => \Configuration::get('PS_HOME_CATEGORY'),
            'active' => true,
            'available_for_order' => true,
            'visibility' => "'both'",
            'minimal_quantity' => 1,
            'indexed' => 1,
            'date_add' => '<param:timestamp>',
            'date_upd' => '<param:timestamp>'
          ),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              '<bind-param:shop:ps.id_shop>',
              'ps.id_product = p.id_product'
            )
          ),
          'require' => array('p'),
        ),
        'pl' => array(
          'table' => 'product_lang',
          'require' => array('ps', 'p'),
          'parameters' => array('shop', 'language'),
          'create' => array(
            'id_shop' => '<param:shop>',
            'id_product' => '<pk>',
            'id_lang' => '<param:language>',
            'link_rewrite' => "''"
          ),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              "pl.id_product = ps.id_product",
              "pl.id_shop = ps.id_shop",
              "<bind-param:language:pl.id_lang>"
            )
          )
        ),
        'sa' => array(
          'table' => 'stock_available',
          'parameters' => array('shop'),
          'create' => array(
            'id_product' => '<pk>',
            'id_product_attribute' => '0',
            'id_shop' => 'IF(<bind-param:shareStock:1>, 0, <param:shop>)',
            'id_shop_group' => 'IF(<bind-param:shareStock:1>, <param:shopGroup>, 0)'
          ),
          'join' => array(
            'type' => 'LEFT',
            'conditions' => array(
              'sa.id_product = ps.id_product',
              'sa.id_product_attribute = 0',
              'sa.id_shop = IF(<bind-param:shareStock:1>, 0, ps.id_shop)',
              'sa.id_shop_group = IF(<bind-param:shareStock:1>, <param:shopGroup>, 0)',
            )
          ),
          'unique' => array(
            array(
              'id_product_attribute' => '<pk>',
              'id_product' => '<field:productId>',
              'id_shop' => 'IF(<bind-param:shareStock:1>, 0, <param:shop>)',
              'id_shop_group' => 'IF(<bind-param:shareStock:1>, <param:shopGroup>, 0)',
            )
          ),
          'require' => array('ps')
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'selectRecord' => 'products',
          'update' => false,
          'mapping' => array(
            'p' => 'id_product',
            'ps' => 'id_product',
            'pl' => 'id_product',
            'sa' => 'id_product'
          )
        ),
        'shopId' => array(
          'type' => 'number',
          'description' => 'shop id',
          'selectRecord' => 'shops',
          'hidden' => true,
          'update' => false,
          'mapping' => array(
            'ps' => 'id_shop',
            'pl' => 'id_shop'
          )
        ),
        'reference' => array(
          'type' => 'string',
          'description' => 'reference',
          'update' => true,
          'mapping' => array(
            'p' => 'reference'
          )
        ),
        'categoryId' => array(
          'type' => 'number',
          'description' => 'default category',
          'selectRecord' => 'categories',
          'update' => true,
          'required' => true,
          'afterUpdate' => array($this, 'fixDefaultCategory'),
          'mapping' => array(
            'ps' => 'id_category_default',
            'p' => 'id_category_default'
          )
        ),
        'imageId' => array(
          'type' => 'number',
          'description' => 'default image',
          'sql' => $imageIdSql,
          'require' => array('ps'),
          'selectRecord' => 'images',
          'update' => false
        ),
        'taxRuleId' => array(
          'type' => 'number',
          'description' => 'tax rule id',
          'required' => true,
          'update' => true,
          'selectRecord' => 'taxRules',
          'mapping' => array(
            'ps' => 'id_tax_rules_group',
            'p' => 'id_tax_rules_group'
          )
        ),
        'ecotax' => array(
          'type' => 'currency',
          'description' => 'ecotax',
          'fixedCurrency' => true,
          'update' => true,
          'mapping' => array(
            'ps' => array(
              'field' => array(
                'value' => 'ecotax',
                'currency' => '<param:defaultCurrency>'
              )
            ),
            'p' => array(
              'field' => array(
                'value' => 'ecotax',
                'currency' => '<param:defaultCurrency>'
              )
            )
          )
        ),
        'manufacturerId' => array(
          'type' => 'number',
          'description' => 'default manufacturer',
          'selectRecord' => 'manufacturers',
          'update' => true,
          'mapping' => array(
            'p' => 'id_manufacturer'
          )
        ),
        'supplierId' => array(
          'type' => 'number',
          'description' => 'default supplier',
          'selectRecord' => 'suppliers',
          'update' => true,
          'mapping' => array(
            'p' => 'id_supplier'
          )
        ),
        'supplierReference' => array(
          'type' => 'string',
          'description' => "default supplier reference",
          'update' => false,
          'sql' => "(
            SELECT sup.product_supplier_reference
            FROM "._DB_PREFIX_."product_supplier sup
            WHERE sup.id_product=p.id_product
              AND sup.id_supplier=p.id_supplier
              AND sup.id_product_attribute=0)",
          'require' => array('p')
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'update' => true,
          'required' => true,
          'mapping' => array(
            'pl' => 'name'
          )
        ),
        'description' => array(
          'type' => 'string',
          'description' => 'description',
          'update' => true,
          'mapping' => array(
            'pl' => 'description_short'
          )
        ),
        'longDescription' => array(
          'type' => 'string',
          'description' => 'long description',
          'update' => true,
          'mapping' => array(
            'pl' => 'description'
          )
        ),
        'friendlyUrl' => array(
          'type' => 'string',
          'description' => 'friendly URL',
          'update' => true,
          'mapping' => array(
            'pl' => 'link_rewrite'
          )
        ),
        'onSale' => array(
          'type' => 'boolean',
          'description' => 'on sale',
          'update' => true,
          'mapping' => array(
            'ps' => 'on_sale',
            'p' => 'on_sale'
          )
        ),
        'basePrice' => array(
          'type' => 'currency',
          'description' => 'base price',
          'fixedCurrency' => true,
          'update' => true,
          'mapping' => array(
            'ps' => array(
              'field' => array(
                'value' => 'price',
                'currency' => '<param:defaultCurrency>'
              )
            ),
            'p' => array(
              'field' => array(
                'value' => 'price',
                'currency' => '<param:defaultCurrency>'
              )
            )
          )
        ),
        'wholesalePrice' => array(
          'type' => 'currency',
          'description' => 'wholesale price',
          'fixedCurrency' => true,
          'update' => true,
          'mapping' => array(
            'ps' => array(
              'field' => array(
                'value' => 'wholesale_price',
                'currency' => '<param:defaultCurrency>'
              )
            ),
            'p' => array(
              'field' => array(
                'value' => 'wholesale_price',
                'currency' => '<param:defaultCurrency>'
              )
            ),
          )
        ),
        'unitPriceRatio' => array(
          'type' => 'number',
          'description' => 'unit price ratio',
          'update' => true,
          'mapping' => array(
            'ps' => 'unit_price_ratio',
            'p' => 'unit_price_ratio'
          )
        ),
        'unit' => array(
          'type' => 'string',
          'description' => 'unit',
          'update' => true,
          'mapping' => array(
            'ps' => 'unity',
            'p' => 'unity'
          )
        ),
        'isbn' => array(
          'type' => 'string',
          'description' => 'ISBN',
          'hidden' => !$ps17,
          'update' => $ps17,
          'mapping' => ($ps17 ? array('p' => 'isbn') : array())
        ),
        'ean13' => array(
          'type' => 'string',
          'description' => 'EAN 13/JAN Barcode',
          'update' => true,
          'mapping' => array(
            'p' => 'ean13'
          )
        ),
        'upcCode' => array(
          'type' => 'string',
          'description' => 'UPC code',
          'update' => true,
          'mapping' => array(
            'p' => 'upc'
          )
        ),
        'active' => array(
          'type' => 'boolean',
          'description' => 'is enabled',
          'update' => true,
          'mapping' => array(
            'ps' => 'active',
            'p' => 'active'
          )
        ),
        'quantity' => array(
          'type' => 'number',
          'description' => 'quantity',
          'update' => true,
          'mapping' => array(
            'sa' => array(
              'field' => 'quantity',
              'write' => 'IF(<param:stockManagement>, IFNULL(<field>, 0), null)',
            )
          )
        ),
        'minOrderQuantity' => array(
          'type' => 'number',
          'description' => 'minimum order quantity',
          'update' => true,
          'mapping' => array(
            'ps' => array(
              'field' => 'minimal_quantity',
              'write' => 'IFNULL(<field>, 1)',
            ),
            'p' => array(
              'field' => 'minimal_quantity',
              'write' => 'IFNULL(<field>, 1)',
            )
          )
        ),
        'condition' => array(
          'type' => 'string',
          'description' => 'condition',
          'update' => true,
          'values' => array(
            'new' => 'New',
            'used' => 'Used',
            'refurbished' => 'Refurbished'
          ),
          'mapping' => array(
            'ps' => 'condition',
            'p' => 'condition'
          )
        ),
        'availableForOrder' => array(
          'type' => 'boolean',
          'description' => 'available for order',
          'update' => true,
          'mapping' => array(
            'ps' => 'available_for_order',
            'p' => 'available_for_order'
          )
        ),
        'availabilityDate' => array(
          'type' => 'datetime',
          'description' => 'availability date',
          'update' => true,
          'mapping' => array(
            'ps' => array(
              'field' => 'available_date',
              'write' => 'IF(<field> = "0000-00-00", null, <field>)'
            ),
            'p' => array(
              'field' => 'available_date',
              'write' => 'IF(<field> = "0000-00-00", null, <field>)'
            )
          )
        ),
        'allowOrder' => array(
          'type' => 'boolean',
          'description' => 'can be ordered',
          'sql' => '(ps.active AND ps.available_for_order AND (!<param:stockManagement> || COALESCE(IF(sa.quantity>0, 1, IF(sa.out_of_stock=2, <param:allowOrderOutOfStock>, sa.out_of_stock)), false)))',
          'require' => array('sa', 'ps'),
          'update' => false
        ),
        'allowOrderOutOfStock' => array(
          'type' => 'boolean',
          'description' => 'can order if out of stock',
          'sql' => 'IF(IFNULL(sa.out_of_stock, 0)=2, <param:allowOrderOutOfStock>, IFNULL(sa.out_of_stock, 0))',
          'require' => array('sa'),
          'update' => false
        ),
        'onlineOnly' => array(
          'type' => 'boolean',
          'description' => 'online only',
          'update' => true,
          'mapping' => array(
            'ps' => 'online_only',
            'p' => 'online_only'
          )
        ),
        'packageWidth' => array(
          'type' => 'number',
          'description' => 'package width',
          'update' => true,
          'mapping' => array(
            'p' => 'width'
          )
        ),
        'packageHeight' => array(
          'type' => 'number',
          'description' => 'package height',
          'update' => true,
          'mapping' => array(
            'p' => 'height'
          )
        ),
        'packageDepth' => array(
          'type' => 'number',
          'description' => 'package depth',
          'update' => true,
          'mapping' => array(
            'p' => 'depth'
          )
        ),
        'packageWeight' => array(
          'type' => 'number',
          'description' => 'package weight',
          'update' => true,
          'mapping' => array(
            'p' => 'weight'
          )
        ),
        'additionalShippingCost' => array(
          'type' => 'number',
          'description' => 'additional shipping cost',
          'update' => true,
          'mapping' => array(
            'ps' => 'additional_shipping_cost',
            'p' => 'additional_shipping_cost'
          )
        ),
        'isVirtual' => array(
          'type' => 'boolean',
          'description' => 'is virtual',
          'update' => true,
          'mapping' => array(
            'p' => 'is_virtual'
          )
        ),
        'isPack' => array(
          'type' => 'boolean',
          'description' => 'is pack',
          'sql' => 'EXISTS(SELECT 1 FROM '._DB_PREFIX_.'pack AS tpack WHERE tpack.id_product_pack = ps.id_product)',
          'require' => array('ps'),
          'update' => false
        ),
        'showPrice' => array(
          'type' => 'boolean',
          'description' => 'show price',
          'update' => true,
          'mapping' => array(
            'ps' => 'show_price',
            'p' => 'show_price'
          )
        ),
        'showInCatalog' => array(
          'type' => 'boolean',
          'description' => 'show in catalog',
          'sql' => '(ps.visibility = "both" || ps.visibility = "catalog")',
          'require' => array('ps'),
          'update' => false
        ),
        'showInSearch' => array(
          'type' => 'boolean',
          'description' => 'show in search',
          'sql' => '(ps.visibility = "both" || ps.visibility = "search")',
          'require' => array('ps'),
          'update' => false
        ),
        'visibility' => array(
          'type' => 'string',
          'description' => 'visibility',
          'values' => array(
            'both' => 'Everywhere',
            'catalog' => 'Catalog only',
            'search' => 'Search only',
            'none' => 'Nowhere'
          ),
          'update' => true,
          'mapping' => array(
            'ps' => 'visibility',
            'p' => 'visibility'
          )
        ),
        'indexed' => array(
          'type' => 'boolean',
          'description' => 'is indexed',
          'update' => true,
          'mapping' => array(
            'ps' => 'indexed',
            'p' => 'indexed'
          )
        ),
        'hasCombinations' => array(
          'type' => 'boolean',
          'description' => 'has combinations',
          'sql' => $hasCombinations,
          'require' => array('ps'),
          'update' => false
        ),
        'metaTitle' => array(
          'type' => 'string',
          'description' => 'meta title',
          'update' => true,
          'mapping' => array(
            'pl' => 'meta_title'
          )
        ),
        'metaDescription' => array(
          'type' => 'string',
          'description' => 'meta description',
          'update' => true,
          'mapping' => array(
            'pl' => 'meta_description'
          )
        ),
        'metaKeywords' => array(
          'type' => 'array[string]',
          'description' => 'meta keywords',
          'sql' => 'REPLACE(pl.meta_keywords, ",", CHAR(1))',
          'require' => array('pl'),
          'update' => false
        ),
        'textInStock' => array(
          'type' => 'string',
          'description' => 'text displayed when in stock',
          'update' => true,
          'mapping' => array(
            'pl' => 'available_now'
          )
        ),
        'textBackorder' => array(
          'type' => 'string',
          'description' => 'text displayed when not in stock',
          'update' => true,
          'mapping' => array(
            'pl' => 'available_later'
          )
        ),
        'redirectType' => array(
          'type' => 'string',
          'description' => 'redirection type',
          'values' => array(
            '' => 'Not set',
            '404' => 'No redirect (404)',
            '301' => 'Redirected permanently (301)',
            '302' => 'Redirected temporarily (302)'
          ),
          'update' => true,
          'mapping' => array(
            'ps' => array(
              'field' => 'redirect_type',
              'write' => 'TRIM(<field>)'
            ),
            'p' => array(
              'field' => 'redirect_type',
              'write' => 'TRIM(<field>)'
            )
          )
        ),
        'redirectedToProduct' => array(
          'type' => 'string',
          'description' => 'redirected to product id',
          'sql' => "IF(ps.active || ps.redirect_type='404', NULL, ps.$idProductRedirected)",
          'require' => array('ps'),
          'update' => false
        ),
        'isCustomizable' => array(
          'type' => 'boolean',
          'description' => 'can be customized',
          'sql' => '(ps.customizable > 0)',
          'require' => array('ps'),
          'update' => false
        ),
        'requiresCustomization' => array(
          'type' => 'boolean',
          'description' => 'customization is required',
          'sql' => '(ps.customizable > 0 AND EXISTS(SELECT 1 FROM '._DB_PREFIX_.'customization_field AS tcust WHERE tcust.required = 1 AND tcust.id_product = ps.id_product))',
          'require' => array('ps'),
          'update' => false
        ),
        'created' => array(
          'type' => 'datetime',
          'description' => 'date created',
          'update' => true,
          'sqlStrategy' => 'LEAST',
          'mapping' => array(
            'ps' => 'date_add',
            'p' => 'date_add'
          )
        ),
        'updated' => array(
          'type' => 'datetime',
          'description' => 'date updated',
          'update' => true,
          'sqlStrategy' => 'GREATEST',
          'mapping' => array(
            'ps' => 'date_upd',
            'p' => 'date_upd'
          )
        ),
        'categories' => array(
          'type' => 'array[string]',
          'description' => 'categories (x,y,z...)',
          'sql' => '(
            SELECT
            REPLACE(GROUP_CONCAT(cl2.name ORDER BY c2.nleft SEPARATOR "@SEP@"), "@SEP@", CHAR(1))
            FROM '._DB_PREFIX_.'category_product cp
            INNER JOIN '._DB_PREFIX_.'category c2 ON (c2.id_category = cp.id_category)
            INNER JOIN '._DB_PREFIX_.'category_lang cl2 ON (cl2.id_category = c2.id_category AND <bind-param:shop:cl2.id_shop> AND <bind-param:language:cl2.id_lang>)
            WHERE cp.id_product = ps.id_product
          )',
          'require' => array('ps'),
          'update' => false
        ),
        'tags' => array(
          'type' => 'array[string]',
          'description' => 'tags (x,y,z...)',
          'sql' => '(
            SELECT
            REPLACE(GROUP_CONCAT(t.name ORDER BY t.name SEPARATOR "@SEP@"), "@SEP@", CHAR(1))
            FROM '._DB_PREFIX_.'product_tag pt
            INNER JOIN '._DB_PREFIX_.'tag t ON (t.id_tag = pt.id_tag AND <bind-param:language:t.id_lang>)
            WHERE pt.id_product = ps.id_product
          )',
          'require' => array('ps'),
          'update' => false
        ),
        'features' => array(
          'type' => 'array[string]',
          'description' => 'features (x,y,z...)',
          'sql' => '(
            SELECT
            REPLACE(GROUP_CONCAT(CONCAT(fl.name, ":", vl.value) ORDER BY f.position SEPARATOR "@SEP@"), "@SEP@", CHAR(1))
            FROM '._DB_PREFIX_.'feature_product pf
            INNER JOIN '._DB_PREFIX_.'feature f ON (f.id_feature = pf.id_feature)
            INNER JOIN '._DB_PREFIX_.'feature_lang fl ON (fl.id_feature = f.id_feature AND <bind-param:language:fl.id_lang>)
            INNER JOIN '._DB_PREFIX_.'feature_value v ON (v.id_feature = f.id_feature AND v.id_feature_value = pf.id_feature_value)
            INNER JOIN '._DB_PREFIX_.'feature_value_lang vl ON (vl.id_feature_value = v.id_feature_value AND <bind-param:language:vl.id_lang>)
            WHERE pf.id_product = ps.id_product
          )',
          'require' => array('ps'),
          'update' => false
        ),
      ),
      'expressions' => array(
        'price' => array(
          'type' => 'currency',
          'expression' => 'productPrice(<field:id>, 0, false)',
          'description' => 'price tax excl.'
        ),
        'priceVat' => array(
          'type' => 'currency',
          'expression' => 'productPrice(<field:id>, 0, true)',
          'description' => 'price tax incl.'
        ),
        'unitPrice' => array(
          'type' => 'currency',
          'expression' => 'productPrice(<field:id>, 0, false) / <field:unitPriceRatio>',
          'description' => 'unit price tax excl.'
        ),
        'unitPriceVat' => array(
          'type' => 'currency',
          'expression' => 'productPrice(<field:id>, 0, true) / <field:unitPriceRatio>',
          'description' => 'unit price tax incl.'
        ),
        'url' => array(
          'type' => 'string',
          'expression' => 'productUrl(<field:id>)',
          'description' => 'url'
        ),
        'image' => array(
          'type' => 'string',
          'expression' => 'productImage(<field:imageId>, <field:friendlyUrl>)',
          'description' => 'image'
        ),
        'images' => array(
          'type' => 'string',
          'expression' => "join(productImages(<field:id>), ', ')",
          'description' => 'images (x,y,z...)',
        ),
        'description' => array(
          'type' => 'string',
          'expression' => 'clean(<field:description>)',
          'description' => 'description'
        ),
        'longDescription' => array(
          'type' => 'string',
          'expression' => 'clean(<field:longDescription>)',
          'description' => 'long description'
        ),
        'type' => array(
          'type' => 'string',
          'expression' => 'if(<field:isPack>, "pack", if(<field:isVirtual>, "virtual", "standard"))',
          'description' => 'type'
        )
      ),
      'links' => array(
        'defaultCategory' => array(
          'description' => 'Product default category',
          'collection' => 'categories',
          'type' => 'BELONGS_TO',
          'sourceFields' => array('categoryId'),
          'targetFields' => array('id')
        ),
        'stock' => array(
          'description' => "Warehouse stocks",
          'collection' => 'stock',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('productId'),
          'delete' => true
        ),
        'categories' => array(
          'description' => 'Product categories',
          'collection' => 'categories',
          'type' => 'HABTM',
          'sourceFields' => array('id'),
          'targetFields' => array('id'),
          'joinTable' => 'category_product',
          'joinFields' => array(
            'sourceFields' => array('id_product'),
            'targetFields' => array('id_category'),
            'extra' => array(
              'position' => array(
                'type' => 'number',
                'description' => 'Position',
                'sqlField' => 'position'
              )
            )
          ),
          'create' => true,
          'delete' => true,
          'callbacks' => array(
            'beforeCreate' => array($this, 'beforeAssocCategory'),
          )
        ),
        'images' => array(
          'description' => 'Product images',
          'collection' => 'images',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('productId'),
          'delete' => true,
          'create' => true
        ),
        'manufacturer' => array(
          'description' => 'Product manufacturer',
          'collection' => 'manufacturers',
          'type' => 'HAS_ONE',
          'sourceFields' => array('manufacturerId'),
          'targetFields' => array('id')
        ),
        'suppliers' => array(
          'description' => 'Product suppliers',
          'collection' => 'suppliers',
          'type' => 'HABTM',
          'sourceFields' => array('id'),
          'targetFields' => array('id'),
          'joinTable' => 'product_supplier',
          'joinFields' => array(
            'sourceFields' => array('id_product'),
            'targetFields' => array('id_supplier')
          ),
          'joinConditions' => array(
            '<join:id_product_attribute> = 0'
          ),
          'delete' => true,
          'create' => true
        ),
        'featureValues' => array(
          'description' => 'Features',
          'collection' => 'featureValues',
          'type' => 'HABTM',
          'sourceFields' => array('id'),
          'targetFields' => array('featureId', 'valueId'),
          'joinTable' => 'feature_product',
          'joinFields' => array(
            'sourceFields' => array('id_product'),
            'targetFields' => array('id_feature', 'id_feature_value')
          ),
          'delete' => true,
          'create' => true
        ),
        'tags' => array(
          'description' => "Tags",
          'collection' => 'tags',
          'type' => 'HABTM',
          'sourceFields' => array('id'),
          'targetFields' => array('id', 'languageId'),
          'joinTable' => 'product_tag',
          'joinFields' => array(
            'sourceFields' => array('id_product'),
            'targetFields' => array('id_tag', 'id_lang'),
          ),
          'delete' => true,
          'create' => true
        ),
        'carriers' => array(
          'description' => "Available carriers",
          'collection' => 'carriers',
          'type' => 'HABTM',
          'delete' => false,
          'sourceFields' => array('shopId', 'id'),
          'targetFields' => array('referenceId'),
          'joinTable' => array(
            'sql' => '(
              SELECT id_shop, id_product, id_carrier_reference FROM '._DB_PREFIX_.'product_carrier
              UNION
              SELECT mps.id_shop, mps.id_product, mc.id_reference
              FROM '._DB_PREFIX_.'carrier mc,
              '._DB_PREFIX_.'product_shop mps
              WHERE mc.deleted = 0
              AND <bind-param:shop:mps.id_shop>
              AND NOT EXISTS (SELECT 1 FROM '._DB_PREFIX_.'product_carrier pc2 WHERE pc2.id_shop = mps.id_shop AND pc2.id_product = mps.id_product)
            )'
          ),
          'joinFields' => array(
            'sourceFields' => array('id_shop', 'id_product'),
            'targetFields' => array('id_carrier_reference'),
          ),
        ),
        'assignedCarriers' => array(
          'description' => "Assigned carriers",
          'collection' => 'carriers',
          'type' => 'HABTM',
          'sourceFields' => array('shopId', 'id'),
          'targetFields' => array('referenceId'),
          'joinTable' => 'product_carrier',
          'joinFields' => array(
            'sourceFields' => array('id_shop', 'id_product'),
            'targetFields' => array('id_carrier_reference'),
          ),
          'joinConditions' => array(
            '<field:deleted> = 0'
          ),
          'delete' => true,
          'create' => true
        ),
        'carts' => array(
          'description' => "Carts",
          'collection' => 'carts',
          'type' => 'HABTM',
          'sourceFields' => array('id'),
          'targetFields' => array('id'),
          'joinTable' => 'cart_product',
          'joinFields' => array(
            'sourceFields' => array('id_product'),
            'targetFields' => array('id_cart'),
          ),
          'delete' => true,
        ),
        'combinations' => array(
          'description' => "Combinations",
          'collection' => 'combinations',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('productId'),
          'create' => true,
          'delete' => true,
        ),
        'ordered' => array(
          'description' => "Ordered",
          'collection' => 'orderedProducts',
          'type' => 'HAS_MANY',
          'delete' => false,
          'sourceFields' => array('id'),
          'targetFields' => array('productId')
        ),
        'supplyOrderDetail' => array(
          'description' => "Supply Order Detail",
          'collection' => 'supplyOrderDetails',
          'type' => 'HAS_MANY',
          'delete' => false,
          'sourceFields' => array('id'),
          'targetFields' => array('productId')
        ),
        'taxRule' => array(
          'description' => "Tax Rule",
          'collection' => 'taxRules',
          'type' => 'BELONGS_TO',
          'sourceFields' => array('taxRuleId'),
          'targetFields' => array('id'),
        ),
      ),
      'list' => array(
        'columns' => array('id', 'name', 'url', 'quantity', 'priceVat'),
        'sorts' => array('id')
      )
    ));
  }

  public function beforeAssocCategory(&$values, $factory) {
    if (! isset($values['position'])) {
      $conn = $factory->getConnection();
      $idCategory = (int)$values['id_category'];
      $sql = "select IFNULL(MAX(position), 0) + 1 from "._DB_PREFIX_."category_product where id_category = $idCategory";
      $position = (int)$conn->singleSelect($sql);
      $values['position'] = $position;
    }
  }

  public function beforeCreate(&$values, $factory) {
    // genereate link rewrite
    if (isset($values['name']) && !isset($values['friendlyUrl'])) {
      $urls = array();
      foreach($values['name'] as $item) {
        $item['value'] = \Tools::link_rewrite($item['value']);
        $urls[] = $item;
      }
      $values['friendlyUrl'] = $urls;
    }
  }

  public function fixDefaultCategory($factory) {
    $conn = $factory->getConnection();
    $cp = _DB_PREFIX_ . "category_product";
    $ps = _DB_PREFIX_ . "product_shop";
    $sql  = "INSERT INTO $cp(id_product, id_category, position) ";
    $sql .= "SELECT DISTINCT id_product, id_category_default, (SELECT IFNULL(MAX(position),0)+1 FROM $cp cp where cp.id_category = ps.id_category_default)";
    $sql .= "  FROM $ps ps ";
    $sql .= "  WHERE id_category_default IS NOT NULL";
    $sql .= "  AND NOT EXISTS (SELECT 1 FROM $cp cp WHERE cp.id_product=ps.id_product and cp.id_category=ps.id_category_default)";
    $conn->execute($sql);
  }
}
