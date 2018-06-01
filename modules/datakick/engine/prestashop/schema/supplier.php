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

class Supplier {
  private $imageHandler;

  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'suppliers',
      'singular' => 'supplier',
      'description' => 'Suppliers',
      'key' => array('id'),
      'display' => 'name',
      'priority' => 600,
      'parameters' => array('shop', 'language'),
      'category' => 'relationships',
      'psTab' => 'AdminSuppliers',
      'create' => true,
      'delete' => true,
      'psController' => 'AdminSuppliers',
      'psClass' => 'Supplier',
      'restrictions' => array(
        'shop' => array(
          'shop' => '<field:shopId>'
        )
      ),
      'callbacks' => array(
        'afterDelete' => array($this, 'afterDelete')
      ),
      'tables' => array(
        's' => array(
          'table' => 'supplier',
          'create' => array(
            'date_add' => '<param:timestamp>',
            'date_upd' => '<param:timestamp>',
            'active' => 1
          )
        ),
        'ss' => array(
          'table' => 'supplier_shop',
          'primary' => true,
          'require' => array('s'),
          'parameters' => array('shop'),
          'create' => array(
            'id_shop' => '<param:shop>',
            'id_supplier' => '<pk>'
          ),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'ss.id_supplier = s.id_supplier',
              '<bind-param:shop:ss.id_shop>'
            )
          )
        ),
        'sl' => array(
          'table' => 'supplier_lang',
          'require' => array('ss'),
          'parameters' => array('language'),
          'create' => array(
            "id_supplier" => "<pk>",
            "id_lang" => "<param:language>"
          ),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              "sl.id_supplier = ss.id_supplier",
              "<bind-param:language:sl.id_lang>"
            )
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'selectRecord' => 'suppliers',
          'mapping' => array(
            's' => 'id_supplier',
            'ss' => 'id_supplier',
            'sl' => 'id_supplier',
          ),
          'update' => false
        ),
        'shopId' => array(
          'type' => 'number',
          'description' => 'shop id',
          'update' => false,
          'hidden' => true,
          'mapping' => array(
            'ss' => 'id_shop'
          )
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'update' => true,
          'required' => true,
          'mapping' => array(
            's' => 'name'
          )
        ),
        'description' => array(
          'type' => 'string',
          'description' => 'description',
          'update' => true,
          'required' => true,
          'mapping' => array(
            'sl' => 'description'
          )
        ),
        'active' => array(
          'type' => 'boolean',
          'description' => 'is enabled',
          'update' => true,
          'mapping' => array(
            's' => 'active'
          )
        ),
        'metaTitle' => array(
          'type' => 'string',
          'description' => 'meta title',
          'update' => true,
          'mapping' => array(
            'sl' => 'meta_title'
          )
        ),
        'metaDescription' => array(
          'type' => 'string',
          'description' => 'meta description',
          'update' => true,
          'mapping' => array(
            'sl' => 'meta_description'
          )
        ),
        'metaKeywords' => array(
          'type' => 'array[string]',
          'description' => 'meta keywords',
          'update' => true,
          'mapping' => array(
            'sl' => array(
              'field' => 'meta_keywords',
              'read' => 'REPLACE(<field>, ",", CHAR(1))',
              'write' => 'REPLACE(<field>, CHAR(1), ",")'
            )
          )
        ),
        'created' => array(
          'type' => 'datetime',
          'description' => 'date created',
          'mapping' => array(
            's' => 'date_add'
          ),
          'update' => true
        ),
        'updated' => array(
          'type' => 'datetime',
          'description' => 'date updated',
          'mapping' => array(
            's' => 'date_upd'
          ),
          'update' => true
        ),
        'logo' => array(
          'type' => 'string',
          'virtual' => true,
          'description' => 'logo',
          'set' => array($this, 'setImage')
        ),
      ),
      'expressions' => array(
        'description' => array(
          'type' => 'string',
          'expression' => 'clean(<field:description>)',
          'description' => 'description'
        )
      ),
      'links' => array(
        'products' => array(
          'description' => "Supplier's products",
          'collection' => 'products',
          'type' => 'HABTM',
          'sourceFields' => array('id'),
          'targetFields' => array('id'),
          'joinTable' => 'product_supplier',
          'joinFields' => array(
            'sourceFields' => array('id_supplier'),
            'targetFields' => array('id_product')
          ),
          'joinConditions' => array(
            '<join:id_product_attribute> = 0'
          ),
          'create' => true,
          'delete' => true,
        ),
        'combinations' => array(
          'description' => "Supplier's combinations",
          'collection' => 'combinations',
          'type' => 'HABTM',
          'sourceFields' => array('id'),
          'targetFields' => array('productId', 'id'),
          'joinTable' => 'product_supplier',
          'joinFields' => array(
            'sourceFields' => array('id_supplier'),
            'targetFields' => array('id_product', 'id_product_attribute')
          ),
          'joinConditions' => array(
            '<join:id_product_attribute> != 0'
          ),
          'create' => false,
          'delete' => false,
        ),
        'addresses' => array(
          'description' => "Addresses",
          'collection' => 'addresses',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('supplierId'),
          'create' => true,
          'delete' => 'dissoc',
        ),
        'supplyOrders' => array(
          'description' => "Supply orders",
          'collection' => 'supplyOrders',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('supplierId'),
          'create' => false,
          'delete' => false,
        ),
      )
    ));
  }

  public function afterDelete($factory, $pks) {
    $handler = $this->getImageHandler($factory);
    foreach ($pks as $pk) {
      $id = (int)$pk['id'];
      $handler->delete($id);
    }
  }

  public function setImage($id, $source, $factory, $context) {
    $this->getImageHandler($factory)->setImage($id, $source, false);
  }

  private function getImageHandler($factory) {
    if (is_null($this->imageHandler)) {
      $this->imageHandler = new \Datakick\PrestashopImageHandler($factory, 'suppliers');
    }
    return $this->imageHandler;
  }
}
