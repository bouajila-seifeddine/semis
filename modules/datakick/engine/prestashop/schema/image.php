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

class Image {
  private $imageHandler = null;

  public function register($dictionary) {
    $useImageShop = version_compare(_PS_VERSION_, '1.6.1', '>=');

    $idProductTable = $useImageShop ? 'is' : 'i';
    $mapping = $useImageShop ? array('is' => 'id_product', 'i' => 'id_product') : array('i' => 'id_product');
    $req = array_keys($mapping);

    $dictionary->registerCollection(array(
      'id' => 'images',
      'singular' => 'image',
      'description' => 'Images',
      'key' => array('id'),
      'display' => 'display',
      'parameters' => array('shop', 'language'),
      'category' => 'catalog',
      'create' => true,
      'delete' => true,
      'psTab' => 'AdminProducts',
      'callbacks' => array(
        'beforeCreate' => array($this, 'beforeCreate'),
        'beforeDelete' => array($this, 'beforeDelete'),
      ),
      'restrictions' => array(
        'shop' => array(
          'shop' => '<field:shopId>'
        )
      ),
      'tables' => array(
        'i' => array(
          'table' => 'image'
        ),
        'is' => array(
          'table' => 'image_shop',
          'primary' => true,
          'parameters' => array('shop'),
          'create' => array(
            'id_shop' => '<param:shop>',
            'id_image' => '<pk>'
          ),
          'require' => array('i'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'is.id_image = i.id_image',
              '<bind-param:shop:is.id_shop>'
            )
          )
        ),
        'il' => array(
          'table' => 'image_lang',
          'require' => array('i'),
          'parameters' => array('language'),
          'create' => array(
            'id_image' => '<pk>',
            'id_lang' => '<param:language>',
          ),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              "il.id_image = is.id_image",
              "<bind-param:language:il.id_lang>"
            )
          )
        ),
        'pl' => array(
          'table' => 'product_lang',
          'require' => array('is', 'i'),
          'parameters' => array('shop', 'language'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              "pl.id_product = $idProductTable.id_product",
              "pl.id_shop = is.id_shop",
              "<bind-param:language:pl.id_lang>"
            )
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'selectRecord' => 'images',
          'update' => false,
          'mapping' => array(
            'i' => 'id_image',
            'is' => 'id_image',
            'il' => 'id_image'
          )
        ),
        'shopId' => array(
          'type' => 'number',
          'description' => 'shop id',
          'sql' => 'is.id_shop',
          'parameter' => 'shop',
          'require' => array('is'),
          'update' => false,
          'hidden' => true
        ),
        'legend' => array(
          'type' => 'string',
          'description' => 'legend',
          'update' => true,
          'mapping' => array(
            'il' => 'legend'
          ),
          'required' => true,
        ),
        'position' => array(
          'type' => 'number',
          'description' => 'position',
          'update' => true,
          'mapping' => array(
            'i' => 'position'
          )
        ),
        'isCover' => array(
          'type' => 'boolean',
          'description' => 'is cover',
          'update' => true,
          'mapping' => array(
            'is' => array(
              'field' => 'cover',
              'write' => 'IF(<field>=0, null, <field>)',
              'read' => 'COALESCE(<field>, false)'
            ),
            'i' => array(
              'field' => 'cover',
              'write' => 'IF(<field>=0, null, <field>)',
              'read' => 'COALESCE(<field>, false)'
            )
          )
        ),
        'productId' => array(
          'type' => 'number',
          'description' => 'product id',
          'selectRecord' => 'products',
          'update' => true,
          'mapping' => $mapping,
          'required' => true,
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'sql' => 'pl.name',
          'require' => array('pl'),
          'update' => false,
        ),
        'friendlyUrl' => array(
          'type' => 'string',
          'description' => 'friendly URL',
          'sql' => 'pl.link_rewrite',
          'require' => array('pl'),
          'update' => false,
        ),
        'image' => array(
          'type' => 'string',
          'virtual' => true,
          'description' => 'image',
          'set' => array($this, 'setImage'),
          'required' => true,
        )
      ),
      'expressions' => array(
        'url' => array(
          'type' => 'string',
          'description' => 'url',
          'expression' => 'productImage(<field:id>, <field:friendlyUrl>)',
        ),
        'filepath' => array(
          'type' => 'string',
          'description' => 'file path',
          'expression' => 'imagePath(<field:id>)'
        ),
        'display' => array(
          'type' => 'string',
          'description' => 'image',
          'expression' => '<field:id> + " - " + coalesce(<field:legend>, "No legend")'
        )
      ),
      'links' => array(
        'products' => array(
          'description' => "Product",
          'collection' => 'products',
          'type' => 'BELONGS_TO',
          'sourceFields' => array('productId'),
          'targetFields' => array('id')
        ),
        'combinations' => array(
          'description' => 'Product combinations',
          'collection' => 'combinations',
          'type' => 'HABTM',
          'delete' => true,
          'sourceFields' => array('id'),
          'targetFields' => array('id'),
          'joinTable' => 'product_attribute_image',
          'joinFields' => array(
            'sourceFields' => array('id_image'),
            'targetFields' => array('id_product_attribute')
          )
        )
      )
    ));
  }

  public function beforeCreate(&$values, $factory) {
    if (isset($values['productId'])) {
      $image = _DB_PREFIX_ . "image";
      $imageShop = _DB_PREFIX_ . "image_shop";
      $productId = (int)($values['productId'][0]['value']);

      $conn = $factory->getConnection();
      // fix image position
      if (! isset($values['position'])) {
        $max = $conn->singleSelect("SELECT MAX(position) FROM $image WHERE id_product = $productId", 0);
        $values['position'] = $max + 1;
      }

      // fix cover - we need to unset current cover befor importing new one
      if (isset($values['isCover'])) {
        $val;
        $shops = array();
        foreach ($values['isCover'] as $arr) {
          $val = $arr['value'];
          $shops[] = $arr['shop'];
        }
        if ($val) {
          $shops = implode(', ', $shops);
          $conn->execute("UPDATE $image SET cover=NULL WHERE id_product = $productId");
          $conn->execute("UPDATE $imageShop SET cover=NULL WHERE id_product = $productId AND id_shop IN ( $shops )");
        }
      }
    }
  }

  public function beforeDelete($factory, $pks) {
    $ids = array();
    $handler = $this->getImageHandler($factory);
    foreach ($pks as $pk) {
      $id = (int)$pk['id'];
      $ids[] = $id;
      $handler->delete($id);
    }
    if ($ids) {
      $ids = implode(', ', $ids);
      $sql = "SELECT DISTINCT id_product FROM " . _DB_PREFIX_ . "image_shop WHERE id_image IN ($ids)";
      $conn = $factory->getConnection();
      $res = $conn->query($sql);
      if ($res) {
        while ($row = $res->fetch()) {
          $id = (int)$row['id_product'];
          $handler->deleteTemp($id);
        }
      }
    }
  }

  public function setImage($imageId, $source, $factory) {
    $this->getImageHandler($factory)->setImage($imageId, $source);
  }

  private function getImageDir($factory, $imageId) {
    return $this->getImageHandler($factory)->getProductImageDir($imageId);
  }

  private function getImageHandler($factory) {
    if (is_null($this->imageHandler)) {
      $this->imageHandler = new \Datakick\PrestashopImageHandler($factory, 'products');
    }
    return $this->imageHandler;
  }
}
