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

class Manufacturer {
  private $imageHandler;

  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'manufacturers',
      'singular' => 'manufacturer',
      'description' => 'Manufacturers',
      'key' => array('id'),
      'display' => 'name',
      'priority' => 500,
      'parameters' => array('shop', 'language'),
      'category' => 'relationships',
      'psTab' => 'AdminManufacturers',
      'psController' => 'AdminManufacturers',
      'psClass' => 'Manufacturer',
      'create' => true,
      'delete' => true,
      'restrictions' => array(
        'shop' => array(
          'shop' => '<field:shopId>'
        )
      ),
      'callbacks' => array(
        'afterDelete' => array($this, 'afterDelete')
      ),
      'tables' => array(
        'm' => array(
          'primary' => true,
          'table' => 'manufacturer',
          'create' => array(
            'date_add' => '<param:timestamp>',
            'date_upd' => '<param:timestamp>',
            'active' => 1
          )
        ),
        'ms' => array(
          'primary' => true,
          'table' => 'manufacturer_shop',
          'require' => array('m'),
          'parameters' => array('shop'),
          'create' => array(
            'id_shop' => '<param:shop>',
            'id_manufacturer' => '<pk>'
          ),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'ms.id_manufacturer = m.id_manufacturer',
              '<bind-param:shop:ms.id_shop>'
            )
          )
        ),
        'ml' => array(
          'table' => 'manufacturer_lang',
          'require' => array('ms'),
          'parameters' => array('language'),
          'create' => array(
            'id_manufacturer' => '<pk>',
            'id_lang' => '<param:language>'
          ),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              "ml.id_manufacturer = ms.id_manufacturer",
              "<bind-param:language:ml.id_lang>"
            )
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'selectRecord' => 'manufacturers',
          'update' => false,
          'mapping' => array(
            'm' => 'id_manufacturer',
            'ms' => 'id_manufacturer',
            'ml' => 'id_manufacturer'
          )
        ),
        'shopId' => array(
          'type' => 'number',
          'description' => 'shop id',
          'update' => false,
          'hidden' => true,
          'mapping' => array(
            'ms' => 'id_shop'
          )
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'update' => true,
          'required' => true,
          'mapping' => array(
            'm' => 'name'
          )
        ),
        'description' => array(
          'type' => 'string',
          'description' => 'description',
          'update' => true,
          'required' => true,
          'mapping' => array(
            'ml' => 'short_description'
          )
        ),
        'longDescription' => array(
          'type' => 'string',
          'description' => 'long description',
          'update' => true,
          'mapping' => array(
            'ml' => 'description'
          )
        ),
        'active' => array(
          'type' => 'boolean',
          'description' => 'is enabled',
          'update' => true,
          'mapping' => array(
            'm' => 'active'
          )
        ),
        'metaTitle' => array(
          'type' => 'string',
          'description' => 'meta title',
          'update' => true,
          'mapping' => array(
            'ml' => 'meta_title'
          )
        ),
        'metaDescription' => array(
          'type' => 'string',
          'description' => 'meta description',
          'update' => true,
          'mapping' => array(
            'ml' => 'meta_description'
          )
        ),
        'metaKeywords' => array(
          'type' => 'array[string]',
          'description' => 'meta keywords',
          'update' => true,
          'mapping' => array(
            'ml' => array(
              'field' => 'meta_keywords',
              'read' => 'REPLACE(<field>, ",", CHAR(1))',
              'write' => 'REPLACE(<field>, CHAR(1), ",")'
            )
          ),
        ),
        'created' => array(
          'type' => 'datetime',
          'description' => 'date created',
          'update' => true,
          'mapping' => array(
            'm' => 'date_add'
          )
        ),
        'updated' => array(
          'type' => 'datetime',
          'description' => 'date updated',
          'update' => true,
          'mapping' => array(
            'm' => 'date_upd'
          )
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
          'description' => "Manufacturer's products",
          'collection' => 'products',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('manufacturerId'),
          'create' => false,
          'delete' => 'dissoc'
        ),
        'combinations' => array(
          'description' => "Manufacturer's product combinations",
          'collection' => 'combinations',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('manufacturerId'),
          'create' => false,
          'delete' => 'dissoc'
        ),
        'addresses' => array(
          'description' => "Addresses",
          'collection' => 'addresses',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('manufacturerId'),
          'conditions' => array(
            '<target:manufacturerId> != 0'
          ),
          'create' => true,
          'delete' => 'dissoc'
        )
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
      $this->imageHandler = new \Datakick\PrestashopImageHandler($factory, 'manufacturers');
    }
    return $this->imageHandler;
  }
}
