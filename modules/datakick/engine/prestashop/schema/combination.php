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

class Combination {
  public function register($dictionary) {
    $hasISBN = version_compare(_PS_VERSION_, '1.7.0', '>=');
    $newVersion = version_compare(_PS_VERSION_, '1.6.1', '>=');
    $schema;
    if ($newVersion) {
      $schema = array(
        'id' => 'combinations',
        'singular' => 'combination',
        'description' => 'Combinations',
        'key' => array('id'),
        'display' => 'fullName',
        'parameters' => array('shop', 'language', 'stockManagement', 'shareStock', 'shopGroup', 'defaultCurrency'),
        'category' => 'catalog',
        'priority' => 700,
        'psTab' => 'AdminProducts',
        'delete' => true,
        'create' => true,
        'restrictions' => array(
          'shop' => array(
            'shop' => '<field:shopId>'
          )
        ),
        'tables' => array(
          'pa' => array(
            'table' => 'product_attribute'
          ),
          'pas' => array(
            'table' => 'product_attribute_shop',
            'primary' => true,
            'require' => array('pa'),
            'parameters' => array('shop'),
            'create' => array(
              'id_product_attribute' => '<pk>',
              'id_shop' => '<param:shop>'
            ),
            'join' => array(
              'type' => 'INNER',
              'conditions' => array(
                "pas.id_product_attribute = pa.id_product_attribute",
                "<bind-param:shop:pas.id_shop>"
              )
            )
          ),
          'p' => array(
            'table' => 'product',
            'require' => array('pas'),
            'join' => array(
              'type' => 'INNER',
              'conditions' => array(
                "pas.id_product = p.id_product",
              )
            )
          ),
          'ps' => array(
            'table' => 'product_shop',
            'require' => array('pas'),
            'join' => array(
              'type' => 'INNER',
              'conditions' => array(
                'ps.id_shop = pas.id_shop',
                "ps.id_product = pas.id_product"
              )
            )
          ),
          'pl' => array(
            'table' => 'product_lang',
            'require' => array('pas'),
            'join' => array(
              'type' => 'INNER',
              'conditions' => array(
                "pas.id_shop = pl.id_shop",
                "pas.id_product = pl.id_product",
                "<bind-param:language:pl.id_lang>"
              )
            )
          ),
          'sa' => array(
            'table' => 'stock_available',
            'join' => array(
              'type' => 'LEFT',
              'conditions' => array(
                'sa.id_product = pas.id_product',
                'sa.id_product_attribute = pas.id_product_attribute',
                'sa.id_shop = IF(<bind-param:shareStock:1>, 0, pas.id_shop)',
                'sa.id_shop_group = IF(<bind-param:shareStock:1>, <param:shopGroup>, 0)',
              ),
            ),
            'parameters' => array('shop'),
            'create' => array(
              'id_product_attribute' => '<pk>',
              'id_product' => '<field:productId>',
              'id_shop' => 'IF(<bind-param:shareStock:1>, 0, <param:shop>)',
              'id_shop_group' => 'IF(<bind-param:shareStock:1>, <param:shopGroup>, 0)',
            ),
            'unique' => array(
              array(
                'id_product_attribute' => '<pk>',
                'id_product' => '<field:productId>',
                'id_shop' => 'IF(<bind-param:shareStock:1>, 0, <param:shop>)',
                'id_shop_group' => 'IF(<bind-param:shareStock:1>, <param:shopGroup>, 0)',
              )
            ),
            'require' => array('pas')
          )
        ),
        'fields' => array(
          'id' => array(
            'type' => 'number',
            'description' => 'id',
            'selectRecord' => 'combinations',
            'mapping' => array(
              'pa' => 'id_product_attribute',
              'pas' => 'id_product_attribute',
              'sa' => 'id_product_attribute'
            ),
            'update' => false
          ),
          'shopId' => array(
            'type' => 'number',
            'description' => 'shop id',
            'mapping' => array(
              'pas' => 'id_shop'
            ),
            'update' => false,
            'hidden' => true
          ),
          'active' => array(
            'type' => 'boolean',
            'description' => 'is enabled',
            'mapping' => array(
              'ps' => 'active',
              'p' => 'active'
            ),
            'update' => false
          ),
          'productId' => array(
            'type' => 'number',
            'description' => 'product id',
            'mapping' => array(
              'pas' => 'id_product',
              'pa' => 'id_product',
              'sa' => 'id_product'
            ),
            'required' => true,
            'selectRecord' => 'products',
            'update' => true
          ),
          'categoryId' => array(
            'type' => 'number',
            'description' => 'product category id',
            'mapping' => array(
              'ps' => 'id_category_default',
              'p' => 'id_category_default'
            ),
            'selectRecord' => 'categories',
            'update' => false
          ),
          'productName' => array(
            'type' => 'string',
            'description' => 'product name',
            'mapping' => array('pl' => 'name'),
            'update' => false
          ),
          'productDescription' => array(
            'type' => 'string',
            'description' => 'description',
            'mapping' => array('pl' => 'description_short'),
            'update' => false
          ),
          'productCoverImageId' => array(
            'type' => 'number',
            'description' => 'product cover image id',
            'update' => false,
            'sql' => '(SELECT i.id_image FROM '._DB_PREFIX_.'image_shop AS i WHERE i.id_shop = pas.id_shop AND i.id_product = pas.id_product ORDER BY i.cover DESC, i.id_image ASC LIMIT 1)',
            'require' => array('pas')
          ),
          'productLongDescription' => array(
            'type' => 'string',
            'description' => 'long description',
            'mapping' => array('pl' => 'description'),
            'update' => false
          ),
          'productFriendlyUrl' => array(
            'type' => 'string',
            'description' => 'friendly URL',
            'mapping' => array('pl' => 'link_rewrite'),
            'update' => false
          ),
          'productReference' => array(
            'type' => 'string',
            'description' => 'product reference',
            'mapping' => array('p' => 'reference'),
            'update' => false
          ),
          'productEan13' => array(
            'type' => 'string',
            'description' => 'product EAN 13/JAN Barcode',
            'mapping' => array('p' => 'ean13'),
            'update' => false,
          ),
          'productUpcCode' => array(
            'type' => 'string',
            'description' => 'product EAN 13/JAN Barcode',
            'update' => false,
            'mapping' => array('p' => 'upc')
          ),
          'productBasePrice' => array(
            'type' => 'number',
            'description' => 'product base price',
            'type' => 'currency',
            'sql' => array(
              'value' => 'ps.price',
              'currency' => '<param:defaultCurrency>'
            ),
            'require' => array('ps'),
            'fixedCurrency' => true,
            'update' => false
          ),
          'taxRuleId' => array(
            'type' => 'number',
            'description' => 'tax rule id',
            'mapping' => array(
              'ps' => 'id_tax_rules_group',
              'p' => 'id_tax_rules_group'
            ),
            'update' => false
          ),
          'imageId' => array(
            'type' => 'number',
            'description' => 'image id',
            'sql' => "(SELECT i.id_image FROM "._DB_PREFIX_."image_shop AS i JOIN "._DB_PREFIX_."product_attribute_image AS pai ON (i.id_image = pai.id_image) WHERE i.id_shop = pas.id_shop AND i.id_product = pas.id_product AND pai.id_product_attribute = pas.id_product_attribute ORDER BY i.cover DESC, i.id_image ASC LIMIT 1)",
            'require' => array('pas'),
            'update' => false
          ),
          'name' => array(
            'type' => 'string',
            'description' => 'name',
            'sql' => '(SELECT
            GROUP_CONCAT(CONCAT(agl.name, " - ", al.name) ORDER BY comb.id_attribute SEPARATOR ", ")
            FROM '._DB_PREFIX_.'product_attribute_combination comb
            INNER JOIN '._DB_PREFIX_.'attribute a ON (comb.id_attribute = a.id_attribute)
            INNER JOIN '._DB_PREFIX_.'attribute_lang al ON (comb.id_attribute = al.id_attribute AND <bind-param:language:al.id_lang>)
            INNER JOIN '._DB_PREFIX_.'attribute_group_lang agl ON (a.id_attribute_group = agl.id_attribute_group AND <bind-param:language:agl.id_lang>)
            WHERE comb.id_product_attribute = pas.id_product_attribute
          )',
          'require' => array('pas'),
          'update' => false
        ),
        'attributeNames' => array(
            'type' => 'array[string]',
            'description' => 'attribute names',
            'sql' => '(SELECT
            REPLACE(GROUP_CONCAT(agl.name ORDER BY ag.position SEPARATOR "@SEP@"), "@SEP@", CHAR(1))
            FROM '._DB_PREFIX_.'product_attribute_combination comb
            INNER JOIN '._DB_PREFIX_.'attribute a ON (comb.id_attribute = a.id_attribute)
            INNER JOIN '._DB_PREFIX_.'attribute_lang al ON (comb.id_attribute = al.id_attribute AND <bind-param:language:al.id_lang>)
            INNER JOIN '._DB_PREFIX_.'attribute_group ag ON (a.id_attribute_group = ag.id_attribute_group)
            INNER JOIN '._DB_PREFIX_.'attribute_group_lang agl ON (a.id_attribute_group = agl.id_attribute_group AND <bind-param:language:agl.id_lang>)
            WHERE comb.id_product_attribute = pas.id_product_attribute
          )',
          'require' => array('pas'),
          'update' => false
        ),
        'attributeValues' => array(
            'type' => 'array[string]',
            'description' => 'attribute values',
            'sql' => '(SELECT
            REPLACE(GROUP_CONCAT(al.name ORDER BY ag.position SEPARATOR "@SEP@"), "@SEP@", CHAR(1))
            FROM '._DB_PREFIX_.'product_attribute_combination comb
            INNER JOIN '._DB_PREFIX_.'attribute a ON (comb.id_attribute = a.id_attribute)
            INNER JOIN '._DB_PREFIX_.'attribute_group ag ON (a.id_attribute_group = ag.id_attribute_group)
            INNER JOIN '._DB_PREFIX_.'attribute_lang al ON (comb.id_attribute = al.id_attribute AND <bind-param:language:al.id_lang>)
            WHERE comb.id_product_attribute = pas.id_product_attribute
          )',
          'require' => array('pas'),
          'update' => false
        ),
        'reference' => array(
          'type' => 'string',
          'description' => 'reference',
          'update' => true,
          'mapping' => array(
            'pa' => 'reference'
          )
        ),
        'isDefault' => array(
          'type' => 'boolean',
          'description' => 'is default',
          'update' => true,
          'mapping' => array(
            'pas' => array(
              'field' => 'default_on',
              'read' => 'IFNULL(<field>, 0)',
            ),
            'pa' => array(
              'field' => 'default_on',
              'read' => 'IFNULL(<field>, 0)',
            )
          )
        ),
        'ean13' => array(
          'type' => 'string',
          'description' => 'EAN 13/JAN Barcode',
          'update' => true,
          'mapping' => array(
            'pa' => 'ean13'
          )
        ),
        'upcCode' => array(
          'type' => 'string',
          'description' => 'UPC code',
          'update' => true,
          'mapping' => array(
            'pa' => 'upc'
          )
        ),
        'isbn' => array(
          'type' => 'string',
          'description' => 'product ISBN',
          'require' => array('p'),
          'sql' => ($hasISBN ? 'p.isbn' : "''"),
          'mapping' => array(
            'p' => 'isbn'
          ),
          'hidden' => !$hasISBN,
          'update' => $hasISBN,
        ),
        'wholesalePrice' => array(
          'type' => 'currency',
          'description' => 'wholesale price',
          'sql' => array(
            'value' => 'pas.wholesale_price',
            'currency' => '<param:defaultCurrency>'
          ),
          'fixedCurrency' => true,
          'require' => array('pas'),
          'update' => true,
          'mapping' => array(
            'pas' => array(
              'field' => array(
                'value' => 'wholesale_price'
              )
            )
          )
        ),
        'visibility' => array(
          'type' => 'string',
          'description' => 'visibility',
          'mapping' => array(
            'ps' => 'visibility',
            'p' => 'visibility'
          ),
          'values' => array(
            'both' => 'Everywhere',
            'catalog' => 'Catalog only',
            'search' => 'Search only',
            'none' => 'Nowhere'
          ),
          'update' => false
        ),
        'unitPriceImpact' => array(
          'type' => 'currency',
          'description' => 'impact on unit price',
          'sql' => array(
            'value' => 'pas.unit_price_impact',
            'currency' => '<param:defaultCurrency>'
          ),
          'fixedCurrency' => true,
          'require' => array('pas'),
          'update' => true,
          'mapping' => array(
            'pas' => array(
              'field' => array(
                'value' => 'unit_price_impact'
              )
            )
          )
        ),
        'priceImpact' => array(
          'type' => 'currency',
          'description' => 'impact on price',
          'sql' => array(
            'value' => 'pas.price',
            'currency' => '<param:defaultCurrency>'
          ),
          'fixedCurrency' => true,
          'require' => array('pas'),
          'update' => true,
          'mapping' => array(
            'pas' => array(
              'field' => array(
                'value' => 'price'
              )
            )
          )
        ),
        'weightImpact' => array(
          'type' => 'number',
          'description' => 'impact on weight',
          'update' => true,
          'mapping' => array(
            'pas' => 'weight',
            'pa' => 'weight',
          )
        ),
        'packageWeight' => array(
          'type' => 'number',
          'description' => 'package weight',
          'sql' => 'IF((pas.weight + p.weight) = 0, null, (pas.weight + p.weight))',
          'require' => array('p', 'pas'),
          'update' => false
        ),
        'packageWidth' => array(
          'type' => 'number',
          'description' => 'package width',
          'sql' => 'IF(p.width = 0, null, p.width)',
          'require' => array('p'),
          'update' => false
        ),
        'packageHeight' => array(
          'type' => 'number',
          'description' => 'package height',
          'sql' => 'IF(p.height = 0, null, p.height)',
          'require' => array('p'),
          'update' => false
        ),
        'packageDepth' => array(
          'type' => 'number',
          'description' => 'package depth',
          'sql' => 'IF(p.depth = 0, null, p.depth)',
          'require' => array('p'),
          'update' => false
        ),
        'ecotax' => array(
          'type' => 'currency',
          'description' => 'ecotax',
          'sql' => array(
            'value' => 'pas.ecotax',
            'currency' => '<param:defaultCurrency>'
          ),
          'fixedCurrency' => true,
          'require' => array('pas'),
          'update' => true,
          'mapping' => array(
            'pas' => array(
              'field' => array(
                'value' => 'ecotax'
              )
            )
          )
        ),
        'minOrderQuantity' => array(
          'type' => 'number',
          'description' => 'minimum order quantity',
          'update' => true,
          'mapping' => array(
            'pas' => array(
              'field' => 'minimal_quantity',
              'read' => 'IFNULL(<field>, 1)',
            ),
            'pa' => array(
              'field' => 'minimal_quantity',
              'read' => 'IFNULL(<field>, 1)',
            ),
          )
        ),
        'availabilityDate' => array(
          'type' => 'datetime',
          'description' => 'availability date',
          'update' => true,
          'mapping' => array(
            'pas' => array(
              'field' => 'available_date',
              'read' => 'IF(<field> = "0000-00-00", null, <field>)',
            ),
            'pa' => array(
              'field' => 'available_date',
              'read' => 'IF(<field> = "0000-00-00", null, <field>)',
            ),
          )
        ),
        'quantity' => array(
          'type' => 'number',
          'description' => 'quantity',
          'update' => true,
          'mapping' => array(
            'sa' => array(
              'field' => 'quantity',
              'read' => 'IF(<param:stockManagement>, IFNULL(<field>, 0), null)',
            )
          )
        ),
        'allowOrder' => array(
          'type' => 'boolean',
          'description' => 'can be ordered',
          'sql' => '(p.active AND p.available_for_order AND (!<param:stockManagement> || COALESCE(IF(sa.quantity>0, 1, IF(sa.out_of_stock=2, <param:allowOrderOutOfStock>, sa.out_of_stock)), false)))',
          'require' => array('sa', 'p'),
          'update' => false
        ),
        'manufacturerId' => array(
          'type' => 'number',
          'description' => 'default manufacturer id',
          'mapping' => array('p' => 'id_manufacturer'),
          'selectRecord' => 'manufacturers',
          'update' => false
        ),
        'supplierId' => array(
          'type' => 'number',
          'description' => 'default supplier id',
          'mapping' => array('p' => 'id_supplier'),
          'selectRecord' => 'suppliers',
          'update' => false
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
              AND sup.id_product_attribute=pa.id_product_attribute)",
          'require' => array('p', 'pa')
        ),
        'condition' => array(
          'type' => 'string',
          'description' => 'condition',
          'mapping' => array(
            'ps' => 'condition',
            'p' => 'condition'
          ),
          'values' => array(
            'new' => 'New',
            'used' => 'Used',
            'refurbished' => 'Refurbished'
          ),
          'update' => false
        ),
      ),
      'expressions' => array(
        'fullName' => array(
          'type' => 'string',
          'expression' => "(<field:productName> + ': ' + <field:name>)",
          'description' => 'full name'
        ),
        'price' => array(
          'type' => 'currency',
          'expression' => 'productPrice(<field:productId>, <field:id>, false)',
          'description' => 'price tax excl.'
        ),
        'basePrice' => array(
          'type' => 'currency',
          'expression' => '<field:productBasePrice> + <field:priceImpact>',
          'description' => 'base price'
        ),
        'priceVat' => array(
          'type' => 'currency',
          'expression' => 'productPrice(<field:productId>, <field:id>, true)',
          'description' => 'price tax incl.'
        ),
        'productDescription' => array(
          'type' => 'string',
          'expression' => 'clean(<field:productDescription>)',
          'description' => 'description'
        ),
        'productLongDescription' => array(
          'type' => 'string',
          'expression' => 'clean(<field:productLongDescription>)',
          'description' => 'long description'
        ),
        'image' => array(
          'type' => 'string',
          'expression' => 'productImage(coalesce(<field:imageId>, <field:productCoverImageId>), <field:productFriendlyUrl>)',
          'description' => 'image'
        ),
        'images' => array(
          'type' => 'string',
          'expression' => "join(productImages(<field:productId>, -1, <field:id>), ', ')",
          'description' => 'images (x,y,z...)',
        ),
        'url' => array(
          'type' => 'string',
          'expression' => 'productUrl(<field:productId>, <field:id>)',
          'description' => 'url'
        ),
      ),
      'links' => array(
        'product' => array(
          'description' => "Product",
          'collection' => 'products',
          'type' => 'BELONGS_TO',
          'sourceFields' => array('productId'),
          'targetFields' => array('id')
        ),
        'stock' => array(
          'description' => "Warehouse stocks",
          'collection' => 'stock',
          'type' => 'HAS_MANY',
          'delete' => true,
          'sourceFields' => array('id'),
          'targetFields' => array('combinationId')
        ),
        'images' => array(
          'description' => 'Combination images',
          'collection' => 'images',
          'type' => 'HABTM',
          'delete' => true,
          'create' => true,
          'sourceFields' => array('id'),
          'targetFields' => array('id'),
          'joinTable' => 'product_attribute_image',
          'joinFields' => array(
            'sourceFields' => array('id_product_attribute'),
            'targetFields' => array('id_image')
          )
        ),
        'attributeValues' => array(
          'description' => 'Attributes',
          'collection' => 'attributeValues',
          'type' => 'HABTM',
          'delete' => true,
          'create' => true,
          'sourceFields' => array('id'),
          'targetFields' => array('valueId'),
          'joinTable' => 'product_attribute_combination',
          'joinFields' => array(
            'sourceFields' => array('id_product_attribute'),
            'targetFields' => array('id_attribute')
          )
        ),
        'suppliers' => array(
          'description' => 'Combination suppliers',
          'collection' => 'suppliers',
          'type' => 'HABTM',
          'delete' => true,
          'create' => true,
          'sourceFields' => array('productId', 'id'),
          'targetFields' => array('id'),
          'joinTable' => 'product_supplier',
          'joinFields' => array(
            'sourceFields' => array('id_product', 'id_product_attribute'),
            'targetFields' => array('id_supplier')
          )
        ),
        'defaultCategory' => array(
          'description' => 'Combination default category',
          'collection' => 'categories',
          'type' => 'BELONGS_TO',
          'sourceFields' => array('categoryId'),
          'targetFields' => array('id')
        ),
        'categories' => array(
          'description' => 'Combination categories',
          'collection' => 'categories',
          'type' => 'HABTM',
          'delete' => false,
          'create' => false,
          'sourceFields' => array('productId'),
          'targetFields' => array('id'),
          'joinTable' => 'category_product',
          'joinFields' => array(
            'sourceFields' => array('id_product'),
            'targetFields' => array('id_category')
          ),
        ),
        'manufacturer' => array(
          'description' => 'Combination manufacturer',
          'collection' => 'manufacturers',
          'type' => 'BELONGS_TO',
          'sourceFields' => array('manufacturerId'),
          'targetFields' => array('id')
        ),
        'featureValues' => array(
          'description' => 'Features',
          'collection' => 'featureValues',
          'type' => 'HABTM',
          'delete' => false,
          'create' => false,
          'sourceFields' => array('productId'),
          'targetFields' => array('featureId', 'valueId'),
          'joinTable' => 'feature_product',
          'joinFields' => array(
            'sourceFields' => array('id_product'),
            'targetFields' => array('id_feature', 'id_feature_value')
          )
        ),
        'tags' => array(
          'description' => "Tags",
          'collection' => 'tags',
          'type' => 'HABTM',
          'delete' => false,
          'create' => false,
          'sourceFields' => array('productId'),
          'targetFields' => array('id', 'languageId'),
          'joinTable' => 'product_tag',
          'joinFields' => array(
            'sourceFields' => array('id_product'),
            'targetFields' => array('id_tag', 'id_lang'),
          ),
        ),
        'carts' => array(
          'description' => 'Carts',
          'collection' => 'carts',
          'type' => 'HABTM',
          'delete' => false,
          'create' => false,
          'sourceFields' => array('id'),
          'targetFields' => array('id'),
          'joinTable' => 'cart_product',
          'joinFields' => array(
            'sourceFields' => array('id_product_attribute'),
            'targetFields' => array('id_cart'),
          ),
          'joinConditions' => array(
            '<join:id_product_attribute> != 0'
          )
        ),
        'ordered' => array(
          'description' => "Ordered",
          'collection' => 'orderedProducts',
          'type' => 'HAS_MANY',
          'delete' => false,
          'sourceFields' => array('id'),
          'targetFields' => array('combinationId')
        ),
        'supplyOrderDetail' => array(
          'description' => "Supply Order Detail",
          'collection' => 'supplyOrderDetails',
          'delete' => false,
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('combinationId')
        ),
      )
    );
  } else {
    $schema = array(
      'id' => 'combinations',
      'singular' => 'combination',
      'description' => 'Combination',
      'key' => array('id'),
      'display' => 'fullName',
      'parameters' => array('shop', 'language', 'stockManagement', 'shareStock', 'shopGroup', 'defaultCurrency'),
      'category' => 'catalog',
      'psTab' => 'AdminProducts',
      'tables' => array(
        'ps' => array(
          'table' => 'product_shop',
          'conditions' => array(
            '<bind-param:shop:ps.id_shop>'
          )
        ),
        'pa' => array(
          'table' => 'product_attribute',
          'require' => array('ps'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              "ps.id_product = pa.id_product",
            )
          )
        ),
        'p' => array(
          'table' => 'product',
          'require' => array('ps'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              "ps.id_product = p.id_product",
            )
          )
        ),
        'pl' => array(
          'table' => 'product_lang',
          'require' => array('ps'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              "ps.id_product = pl.id_product",
              "<bind-param:language:pl.id_lang>"
            )
          )
        ),
        'sa' => array(
          'table' => 'stock_available',
          'join' => array(
            'type' => 'LEFT',
            'conditions' => array(
              'sa.id_product = pa.id_product',
              'sa.id_product_attribute = pa.id_product_attribute',
              'sa.id_shop = IF(<bind-param:shareStock:1>, 0, ps.id_shop)',
              'sa.id_shop_group = IF(<bind-param:shareStock:1>, <param:shopGroup>, 0)',
            ),
          ),
          'require' => array('pa', 'ps')
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'sql' => 'pa.id_product_attribute',
          'update' => false,
          'selectRecord' => 'combinations',
          'require' => array('pa')
        ),
        'active' => array(
          'type' => 'boolean',
          'description' => 'is enabled',
          'sql' => 'p.active',
          'update' => array(
            'p' => 'active',
          ),
          'require' => array('p', 'pa')
        ),
        'productId' => array(
          'type' => 'number',
          'description' => 'product id',
          'sql' => 'pa.id_product',
          'update' => false,
          'selectRecord' => 'products',
          'require' => array('pa')
        ),
        'categoryId' => array(
          'type' => 'number',
          'description' => 'product category id',
          'update' => false,
          'sql' => 'ps.id_category_default',
          'selectRecord' => 'categories',
          'require' => array('ps')
        ),
        'productName' => array(
          'type' => 'string',
          'description' => 'product name',
          'sql' => 'pl.name',
          'update' => false,
          'require' => array('pl')
        ),
        'productDescription' => array(
          'type' => 'string',
          'description' => 'description',
          'sql' => 'pl.description_short',
          'update' => false,
          'require' => array('pl')
        ),
        'productLongDescription' => array(
          'type' => 'string',
          'description' => 'long description',
          'sql' => 'pl.description',
          'update' => false,
          'require' => array('pl')
        ),
        'productFriendlyUrl' => array(
          'type' => 'string',
          'description' => 'friendly URL',
          'sql' => 'pl.link_rewrite',
          'update' => false,
          'require' => array('pl')
        ),
        'productReference' => array(
          'type' => 'string',
          'description' => 'product reference',
          'sql' => 'p.reference',
          'update' => false,
          'require' => array('p', 'ps')
        ),
        'productEan13' => array(
          'type' => 'string',
          'description' => 'product EAN 13/JAN Barcode',
          'sql' => 'p.ean13',
          'update' => false,
          'require' => array('p')
        ),
        'productUpcCode' => array(
          'type' => 'string',
          'description' => 'product EAN 13/JAN Barcode',
          'sql' => 'p.ean13',
          'update' => false,
          'require' => array('p')
        ),
        'taxRuleId' => array(
          'type' => 'number',
          'description' => 'tax rule id',
          'sql' => 'ps.id_tax_rules_group',
          'update' => false,
          'require' => array('ps')
        ),
        'imageId' => array(
          'type' => 'number',
          'description' => 'image id',
          'sql' => '(SELECT i.id_image FROM '._DB_PREFIX_.'image AS i JOIN '._DB_PREFIX_.'product_attribute_image AS pai ON (i.id_image = pai.id_image) WHERE i.id_product = pa.id_product AND pai.id_product_attribute = pa.id_product_attribute ORDER BY i.cover DESC, i.id_image ASC LIMIT 1)',
          'update' => false,
          'require' => array('pa')
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'sql' => '(SELECT
          GROUP_CONCAT(CONCAT(agl.name, " - ", al.name) ORDER BY comb.id_attribute SEPARATOR ", ")
          FROM '._DB_PREFIX_.'product_attribute_combination comb
          INNER JOIN '._DB_PREFIX_.'attribute a ON (comb.id_attribute = a.id_attribute)
          INNER JOIN '._DB_PREFIX_.'attribute_lang al ON (comb.id_attribute = al.id_attribute AND <bind-param:language:al.id_lang>)
          INNER JOIN '._DB_PREFIX_.'attribute_group_lang agl ON (a.id_attribute_group = agl.id_attribute_group AND <bind-param:language:agl.id_lang>)
          WHERE comb.id_product_attribute = pa.id_product_attribute
        )',
        'update' => false,
        'require' => array('pa')
      ),
      'reference' => array(
        'type' => 'string',
        'description' => 'reference',
        'sql' => 'pa.reference',
        'update' => array(
          'pa' => 'reference'
        ),
        'require' => array('pa')
      ),
      'isDefault' => array(
        'type' => 'boolean',
        'description' => 'is default',
        'sql' => 'IFNULL(pa.default_on, 0)',
        'update' => array(
          'pa' => 'default_on'
        ),
        'require' => array('pa')
      ),
      'ean13' => array(
        'type' => 'string',
        'description' => 'EAN 13/JAN Barcode',
        'sql' => 'pa.ean13',
        'update' => array(
          'pa' => 'ean13'
        ),
        'require' => array('pa')
      ),
      'upcCode' => array(
        'type' => 'string',
        'description' => 'UPC code',
        'sql' => 'pa.upc',
        'update' => array(
          'pa' => 'upc'
        ),
        'require' => array('pa')
      ),
      'isbn' => array(
        'type' => 'string',
        'description' => 'ISBN',
        'sql' => "''",
        'update' => false,
        'require' => array('p'),
        'hidden' => true,
      ),
      'wholesalePrice' => array(
        'type' => 'currency',
        'description' => 'wholesale price',
        'sql' => array(
          'value' => 'pa.wholesale_price',
          'currency' => '<param:defaultCurrency>'
        ),
        'fixedCurrency' => true,
        'update' => array(
          'pa' => array(
            'field' => array(
              'value' => 'wholesale_price'
            )
          )
        ),
        'require' => array('pa')
      ),
      'unitPriceImpact' => array(
        'type' => 'currency',
        'description' => 'impact on unit price',
        'sql' => array(
          'value' => 'pa.unit_price_impact',
          'currency' => '<param:defaultCurrency>'
        ),
        'fixedCurrency' => true,
        'update' => array(
          'pa' => array(
            'field' => array(
              'value' => 'unit_price_impact'
            )
          )
        ),
        'require' => array('pa')
      ),
      'priceImpact' => array(
        'type' => 'currency',
        'description' => 'impact on price',
        'sql' => array(
          'value' => 'pa.price',
          'currency' => '<param:defaultCurrency>'
        ),
        'fixedCurrency' => true,
        'update' => array(
          'pa' => array(
            'field' => array(
              'value' => 'price'
            )
          )
        ),
        'require' => array('pa')
      ),
      'weightImpact' => array(
        'type' => 'number',
        'description' => 'impact on weight',
        'sql' => 'pa.weight',
        'update' => array(
          'pa' => 'weight'
        ),
        'require' => array('pa')
      ),
      'packageWeight' => array(
        'type' => 'number',
        'description' => 'package weight',
        'sql' => 'IF((pa.weight + p.weight) = 0, null, (pa.weight + p.weight))',
        'update' => false,
        'require' => array('p', 'pa')
      ),
      'packageWidth' => array(
        'type' => 'number',
        'description' => 'package width',
        'sql' => 'IF(p.width = 0, null, p.width)',
        'update' => false,
        'require' => array('p', 'pa')
      ),
      'packageHeight' => array(
        'type' => 'number',
        'description' => 'package height',
        'sql' => 'IF(p.height = 0, null, p.height)',
        'update' => false,
        'require' => array('p', 'pa')
      ),
      'packageDepth' => array(
        'type' => 'number',
        'description' => 'package depth',
        'sql' => 'IF(p.depth = 0, null, p.depth)',
        'update' => false,
        'require' => array('p', 'pa')
      ),
      'ecotax' => array(
        'type' => 'currency',
        'description' => 'ecotax',
        'sql' => array(
          'value' => 'pa.ecotax',
          'currency' => '<param:defaultCurrency>'
        ),
        'fixedCurrency' => true,
        'update' => array(
          'pa' => array(
            'field' => array(
              'value' => 'ecotax'
            )
          )
        ),
        'require' => array('pa')
      ),
      'minOrderQuantity' => array(
        'type' => 'number',
        'description' => 'minimum order quantity',
        'sql' => 'IFNULL(pa.minimal_quantity, 1)',
        'update' => array(
          'pa' => 'minimal_quantity'
        ),
        'require' => array('pa')
      ),
      'availabilityDate' => array(
        'type' => 'datetime',
        'description' => 'availability date',
        'sql' => 'IF(pa.available_date = "0000-00-00", null, pa.available_date)',
        'update' => array(
          'pa' => 'available_date'
        ),
        'require' => array('pa')
      ),
      'quantity' => array(
        'type' => 'number',
        'description' => 'quantity',
        'sql' => 'IF(<param:stockManagement>, sa.quantity, null)',
        'update' => false,
        'require' => array('sa')
      ),
      'allowOrder' => array(
        'type' => 'boolean',
        'description' => 'can be ordered',
        'sql' => '(p.active AND p.available_for_order AND (!<param:stockManagement> || COALESCE(IF(sa.quantity>0, 1, IF(sa.out_of_stock=2, <param:allowOrderOutOfStock>, sa.out_of_stock)), false)))',
        'update' => false,
        'require' => array('sa', 'p')
      ),
      'manufacturerId' => array(
        'type' => 'number',
        'description' => 'default manufacturer id',
        'sql' => 'p.id_manufacturer',
        'update' => false,
        'selectRecord' => 'manufacturers',
        'require' => array('p', 'pa')
      ),
      'condition' => array(
        'type' => 'string',
        'description' => 'condition',
        'sql' => 'p.condition',
        'update' => false,
        'require' => array('p')
      ),
    ),
    'expressions' => array(
      'fullName' => array(
        'type' => 'string',
        'expression' => "(<field:productName> + ': ' + <field:name>)",
        'description' => 'full name'
      ),
      'price' => array(
        'type' => 'currency',
        'expression' => 'productPrice(<field:productId>, <field:id>, false)',
        'description' => 'price tax excl.'
      ),
      'priceVat' => array(
        'type' => 'currency',
        'expression' => 'productPrice(<field:productId>, <field:id>, true)',
        'description' => 'price tax incl.'
      ),
      'productDescription' => array(
        'type' => 'string',
        'expression' => 'clean(<field:productDescription>)',
        'description' => 'description'
      ),
      'productLongDescription' => array(
        'type' => 'string',
        'expression' => 'clean(<field:productLongDescription>)',
        'description' => 'long description'
      ),
      'image' => array(
        'type' => 'string',
        'expression' => 'productImage(<field:imageId>, <field:productFriendlyUrl>)',
        'description' => 'image'
      ),
      'images' => array(
        'type' => 'string',
        'expression' => "join(productImages(<field:productId>, -1, <field:id>), ', ')",
        'description' => 'images (x,y,z...)',
      ),
      'url' => array(
        'type' => 'string',
        'expression' => 'productUrl(<field:productId>, <field:id>)',
        'description' => 'url'
      ),
      'supplierReference' => array(
        'type' => 'string',
        'expression' => 'supplierReference(<field:supplierId>, <field:productId>, <field:id>)',
        'description' => 'supplier reference'
      )
    ),
    'links' => array(
      'product' => array(
        'description' => "Product",
        'collection' => 'products',
        'type' => 'BELONGS_TO',
        'sourceFields' => array('productId'),
        'targetFields' => array('id')
      ),
      'stock' => array(
        'description' => "Warehouse stocks",
        'collection' => 'stock',
        'type' => 'HAS_MANY',
        'sourceFields' => array('id'),
        'targetFields' => array('combinationId')
      ),
      'images' => array(
        'description' => 'Combination images',
        'collection' => 'images',
        'type' => 'HABTM',
        'sourceFields' => array('id'),
        'targetFields' => array('id'),
        'joinTable' => 'product_attribute_image',
        'joinFields' => array(
          'sourceFields' => array('id_product_attribute'),
          'targetFields' => array('id_image')
        )
      ),
      'attributeValues' => array(
        'description' => 'Attributes',
        'collection' => 'attributeValues',
        'type' => 'HABTM',
        'sourceFields' => array('id'),
        'targetFields' => array('valueId'),
        'joinTable' => 'product_attribute_combination',
        'joinFields' => array(
          'sourceFields' => array('id_product_attribute'),
          'targetFields' => array('id_attribute')
        )
      ),
      'suppliers' => array(
        'description' => 'Combination suppliers',
        'collection' => 'suppliers',
        'type' => 'HABTM',
        'sourceFields' => array('productId', 'id'),
        'targetFields' => array('id'),
        'joinTable' => 'product_supplier',
        'joinFields' => array(
          'sourceFields' => array('id_product', 'id_product_attribute'),
          'targetFields' => array('id_supplier')
        )
      ),
      'defaultCategory' => array(
        'description' => 'Combination default category',
        'collection' => 'categories',
        'type' => 'BELONGS_TO',
        'sourceFields' => array('categoryId'),
        'targetFields' => array('id')
      ),
      'categories' => array(
        'description' => 'Combination categories',
        'collection' => 'categories',
        'type' => 'HABTM',
        'sourceFields' => array('productId'),
        'targetFields' => array('id'),
        'joinTable' => 'category_product',
        'joinFields' => array(
          'sourceFields' => array('id_product'),
          'targetFields' => array('id_category')
        ),
      ),
      'manufacturer' => array(
        'description' => 'Combination manufacturer',
        'collection' => 'manufacturers',
        'type' => 'BELONGS_TO',
        'sourceFields' => array('manufacturerId'),
        'targetFields' => array('id')
      ),
      'featureValues' => array(
        'description' => 'Features',
        'collection' => 'featureValues',
        'type' => 'HABTM',
        'sourceFields' => array('productId'),
        'targetFields' => array('featureId', 'valueId'),
        'joinTable' => 'feature_product',
        'joinFields' => array(
          'sourceFields' => array('id_product'),
          'targetFields' => array('id_feature', 'id_feature_value')
        )
      ),
      'tags' => array(
        'description' => "Tags",
        'collection' => 'tags',
        'type' => 'HABTM',
        'sourceFields' => array('productId'),
        'targetFields' => array('id', 'languageId'),
        'joinTable' => 'product_tag',
        'joinFields' => array(
          'sourceFields' => array('id_product'),
          'targetFields' => array('id_tag', 'id_lang'),
        ),
      ),
      'carts' => array(
        'description' => 'Carts',
        'collection' => 'carts',
        'type' => 'HABTM',
        'sourceFields' => array('id'),
        'targetFields' => array('id'),
        'joinTable' => 'cart_product',
        'joinFields' => array(
          'sourceFields' => array('id_product_attribute'),
          'targetFields' => array('id_cart'),
        ),
        'joinConditions' => array(
          '<join:id_product_attribute> != 0'
        )
      ),
      'supplyOrderDetail' => array(
        'description' => "Supply Order Detail",
        'collection' => 'supplyOrderDetails',
        'type' => 'HAS_MANY',
        'sourceFields' => array('id'),
        'targetFields' => array('combinationId')
      ),
      'ordered' => array(
        'description' => "Ordered",
        'collection' => 'orderedProducts',
        'type' => 'HAS_MANY',
        'sourceFields' => array('id'),
        'targetFields' => array('combinationId')
      )));
    }
    $dictionary->registerCollection($schema);
  }
}
