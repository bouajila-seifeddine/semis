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

class Carrier {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'carriers',
      'singular' => 'carrier',
      'description' => 'Carriers',
      'key' => array('referenceId'),
      'display' => 'name',
      'parameters' => array('shop', 'language'),
      'category' => 'shipping',
      'psTab' => 'AdminCarriers',
      'restrictions' => array(
        'shop' => array(
          'shop' => '<field:shopId>'
        )
      ),
      'tables' => array(
        'cs' => array(
          'table' => 'carrier_shop',
          'conditions' => array(
            '<bind-param:shop:cs.id_shop>'
          ),
          'require' => array('c')
        ),
        'c' => array(
          'table' => 'carrier',
          'require' => array('cs'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'cs.id_carrier = c.id_carrier'
            )
          )
        ),
        'cl' => array(
          'table' => 'carrier_lang',
          'require' => array('c', 'cs'),
          'join' => array(
            'type' => 'LEFT',
            'conditions' => array(
              "cl.id_shop = cs.id_shop",
              "cl.id_carrier = cs.id_carrier",
              "<bind-param:language:cl.id_lang>"
            ),
          ),
          'parameters' => array('shop', 'languages'),
          'create' => array(
            "id_shop" => "<param:shop>",
            "id_carrier" => "<pk>",
            "id_lang" => "<param:language>"
          )
        ),
        'v' => array(
          'table' => 'carrier',
          'require' => array('c'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'v.id_reference = c.id_reference'
            )
          )
        )
      ),
      'conditions' => array(
        'c.deleted = 0'
      ),
      'joinConditions' => array(
        // join conditions override - no deleted=0 condition here
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'version id',
          'sql' => 'c.id_carrier',
          'require' => array('c'),
          'hidden' => true,
          'update' => false,
          'selectRecord' => 'carriers'
        ),
        'shopId' => array(
          'type' => 'number',
          'description' => 'shop id',
          'sql' => 'cs.id_shop',
          'require' => array('cs'),
          'update' => false,
          'hidden' => true
        ),
        'referenceId' => array(
          'type' => 'number',
          'description' => 'id',
          'sql' => 'c.id_reference',
          'require' => array('c'),
          'selectRecord' => 'carriers',
          'update' => false
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'sql' => 'c.name',
          'require' => array('c'),
          'update' => array(
            'c' => 'name'
          )
        ),
        'transitTime' => array(
          'type' => 'string',
          'description' => 'transit time',
          'sql' => 'cl.delay',
          'require' => array('cl'),
          'update' => array(
            'cl' => 'delay'
          )
        ),
        'url' => array(
          'type' => 'string',
          'description' => 'tracking url',
          'sql' => 'c.url',
          'require' => array('c'),
          'update' => array(
            'c' => 'url'
          )
        ),
        'grade' => array(
          'type' => 'number',
          'description' => 'speed grade',
          'sql' => 'c.grade',
          'require' => array('c'),
          'update' => array(
            'c' => 'grade'
          )
        ),
        'position' => array(
          'type' => 'number',
          'description' => 'position',
          'sql' => 'c.position',
          'require' => array('c'),
          'update' => array(
            'c' => 'position'
          )
        ),
        'maxWidth' => array(
          'type' => 'number',
          'description' => 'max width',
          'sql' => 'c.max_width',
          'require' => array('c'),
          'update' => array(
            'c' => 'max_width'
          )
        ),
        'maxHeight' => array(
          'type' => 'number',
          'description' => 'max height',
          'sql' => 'c.max_height',
          'require' => array('c'),
          'update' => array(
            'c' => 'max_height'
          )
        ),
        'maxDepth' => array(
          'type' => 'number',
          'description' => 'max depth',
          'sql' => 'c.max_depth',
          'require' => array('c'),
          'update' => array(
            'c' => 'max_depth'
          )
        ),
        'maxWeight' => array(
          'type' => 'number',
          'description' => 'max weight',
          'sql' => 'c.max_weight',
          'require' => array('c'),
          'update' => array(
            'c' => 'max_weight'
          )
        ),
        'freeShipping' => array(
          'type' => 'boolean',
          'description' => 'free shipping',
          'sql' => 'c.is_free',
          'require' => array('c'),
          'update' => array(
            'c' => 'is_free'
          )
        ),
        'addHandlingCosts' => array(
          'type' => 'boolean',
          'description' => 'add handling costs',
          'sql' => 'c.shipping_handling',
          'require' => array('c'),
          'update' => array(
            'c' => 'shipping_handling'
          )
        ),
        'method' => array(
          'type' => 'string',
          'description' => 'billing method',
          'sql' => 'IF(c.shipping_method = 1, "price", IF(c.shipping_method = 2, "weight", (IF(c.shipping_method = 3, "free", "default"))))',
          'require' => array('c'),
          'update' => array()
        ),
        'disableOutOfRange' => array(
          'type' => 'boolean',
          'description' => 'disable when out of range',
          'sql' => 'c.range_behavior',
          'require' => array('c'),
          'update' => array(
            'c' => 'range_behavior'
          )
        ),
        'active' => array(
          'type' => 'boolean',
          'description' => 'is enabled',
          'sql' => '(c.active && !c.deleted)',
          'require' => array('c'),
          'update' => array(
            'c' => 'active'
          )
        ),
        'deleted' => array(
          'type' => 'boolean',
          'description' => 'is deleted',
          'sql' => 'c.deleted',
          'require' => array('c'),
          'update' => false,
        ),
        'version' => array(
          'type' => 'number',
          'description' => 'settings version id',
          'sql' => 'v.id_carrier',
          'require' => array('v'),
          'hidden' => true,
          'update' => false
        )
      ),
      'expressions' => array(
      ),
      'links' => array(
        'groups' => array(
          'description' => "Customer groups",
          'collection' => 'customerGroups',
          'type' => 'HABTM',
          'sourceFields' => array('id'),
          'targetFields' => array('id'),
          'joinTable' => 'carrier_group',
          'joinFields' => array(
            'sourceFields' => array('id_carrier'),
            'targetFields' => array('id_group'),
          ),
        ),
        'zones' => array(
          'description' => "Zones",
          'collection' => 'zones',
          'type' => 'HABTM',
          'sourceFields' => array('id'),
          'targetFields' => array('id'),
          'joinTable' => 'carrier_zone',
          'joinFields' => array(
            'sourceFields' => array('id_carrier'),
            'targetFields' => array('id_zone'),
          ),
        ),
        'assignedProducts' => array(
          'description' => "Assigned products",
          'collection' => 'products',
          'type' => 'HABTM',
          'sourceFields' => array('referenceId'),
          'targetFields' => array('shopId', 'id'),
          'joinTable' => 'product_carrier',
          'joinFields' => array(
            'sourceFields' => array('id_carrier_reference'),
            'targetFields' => array('id_shop', 'id_product'),
          ),
        ),
        'products' => array(
          'description' => "Products",
          'collection' => 'products',
          'type' => 'HABTM',
          'sourceFields' => array('referenceId'),
          'targetFields' => array('shopId', 'id'),
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
            'sourceFields' => array('id_carrier_reference'),
            'targetFields' => array('id_shop', 'id_product'),
          ),
        ),
        'orders' => array(
          'description' => "Orders",
          'collection' => 'orders',
          'type' => 'HAS_MANY',
          'joinType' => 'INNER',
          'sourceFields' => array('version'),
          'targetFields' => array('carrierId'),
        ),
        'carts' => array(
          'description' => "Carts",
          'collection' => 'carts',
          'type' => 'HAS_MANY',
          'joinType' => 'INNER',
          'sourceFields' => array('version'),
          'targetFields' => array('carrierId'),
        ),
        'warehouses' => array(
          'description' => "Warehouses",
          'collection' => 'warehouses',
          'type' => 'HABTM',
          'sourceFields' => array('referenceId'),
          'targetFields' => array('id'),
          'joinTable' => 'warehouse_carrier',
          'joinFields' => array(
            'sourceFields' => array('id_carrier'),
            'targetFields' => array('id_warehouse')
          )
        )
      )
    ));
  }
}
