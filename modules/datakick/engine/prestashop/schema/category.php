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

class Category {
  private $imageHandler = null;

  public function register($dictionary) {
    $rootCategory = (int)\Configuration::get('PS_ROOT_CATEGORY');
    $homeCategory = (int)\Configuration::get('PS_HOME_CATEGORY');

    $dictionary->registerCollection(array(
      'id' => 'categories',
      'singular' => 'category',
      'description' => 'Categories',
      'priority' => 200,
      'key' => array('id'),
      'display' => 'path',
      'hierarchy' => array(
        'parent' => 'parent',
        'left' => 'nleft',
        'right' => 'nright'
      ),
      'create' => true,
      'delete' => array(
        'value' => true,
        'conditions' => array(
          'id' => "<field> NOT IN ($rootCategory, $homeCategory)"
        )
      ),
      'category' => 'catalog',
      'psTab' => 'AdminCategories',
      'psController' => 'AdminCategories',
      'psClass' => 'Category',
      'parameters' => array('shop', 'language'),
      'restrictions' => array(
        'shop' => array(
          'shop' => '<field:shopId>'
        )
      ),
      'callbacks' => array(
        'beforeCreate' => array($this, 'beforeCreate'),
        'afterDelete' => array($this, 'afterDelete'),
        'afterBatch' => array($this, 'afterBatch')
      ),
      'tables' => array(
        'c' => array(
          'table' => 'category',
          'create' => array(
            'id_parent' => $rootCategory,
            'active' => true,
            'is_root_category' => false,
            'date_add' => '<param:timestamp>',
            'date_upd' => '<param:timestamp>',
          )
        ),
        'cs' => array(
          'table' => 'category_shop',
          'primary' => true,
          'parameters' => array('shop'),
          'require' => array('c'),
          'create' => array(
            'id_shop' => '<param:shop>',
            'id_category' => '<pk>'
          ),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'cs.id_category = c.id_category',
              '<bind-param:shop:cs.id_shop>'
            )
          ),
        ),
        'cl' => array(
          'table' => 'category_lang',
          'require' => array('cs'),
          'parameters' => array('shop', 'language'),
          'create' => array(
            'id_shop' => '<param:shop>',
            'id_category' => '<pk>',
            'id_lang' => '<param:language>',
            'link_rewrite' => "''"
          ),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              "cl.id_category = cs.id_category",
              "cl.id_shop = cs.id_shop",
              "<bind-param:language:cl.id_lang>"
            )
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'selectRecord' => 'categories',
          'update' => true,
          'mapping' => array(
            'c' => 'id_category',
            'cs' => 'id_category',
            'cl' => 'id_category'
          )
        ),
        'shopId' => array(
          'type' => 'number',
          'description' => 'shop id',
          'update' => false,
          'mapping' => array(
            'cs' => 'id_shop',
            'cl' => 'id_shop'
          )
        ),
        'languageId' => array(
          'type' => 'number',
          'description' => 'language id',
          'hidden' => true,
          'update' => false,
          'mapping' => array(
            'cl' => 'id_lang',
          )
        ),
        'parent' => array(
          'type' => 'number',
          'description' => 'parent id',
          'selectRecord' => 'categories',
          'update' => true,
          'afterUpdate' => array($this, 'afterBatch'),
          'mapping' => array(
            'c' => 'id_parent'
          )
        ),
        'nleft' => array(
          'type' => 'number',
          'description' => 'nested set: left',
          'hidden' => true,
          'update' => false,
          'mapping' => array(
            'c' => 'nleft'
          )
        ),
        'nright' => array(
          'type' => 'number',
          'description' => 'nested set: right',
          'hidden' => true,
          'update' => false,
          'mapping' => array(
            'c' => 'nright'
          )
        ),
        'active' => array(
          'type' =>  'boolean',
          'description' => 'active',
          'update' => true,
          'mapping' => array(
            'c' => 'active'
          )
        ),
        'isRoot' => array(
          'type' =>  'boolean',
          'description' => 'root category',
          'update' => true,
          'mapping' => array(
            'c' => 'is_root_category'
          )
        ),
        'depth' => array(
          'type' =>  'number',
          'description' => 'depth',
          'update' => false,
          'mapping' => array(
            'c' => 'level_depth'
          )
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'required' => true,
          'update' => true,
          'mapping' => array(
            'cl' => 'name'
          ),
        ),
        'description' => array(
          'type' => 'string',
          'description' => 'description',
          'update' => true,
          'mapping' => array(
            'cl' => 'description'
          )
        ),
        'path' => array(
          'type' => 'array[string]',
          'description' => 'path',
          'sql' => '(
            SELECT
            REPLACE(GROUP_CONCAT(cl2.name ORDER BY c2.nleft SEPARATOR "@SEP@"), "@SEP@", CHAR(1))
            FROM '._DB_PREFIX_.'category c2 INNER JOIN '._DB_PREFIX_.'category_lang cl2 ON (cl2.id_category = c2.id_category AND <bind-param:shop:cl2.id_shop> AND <bind-param:language:cl2.id_lang>)
            WHERE c2.nleft <= c.nleft
            AND c2.nright >= c.nright
          )',
          'require' => array('c'),
          'update' => false
        ),
        'friendlyUrl' => array(
          'type' => 'string',
          'description' => 'friendly URL',
          'update' => true,
          'mapping' => array(
            'cl' => 'link_rewrite'
          ),
        ),
        'metaTitle' => array(
          'type' => 'string',
          'description' => 'meta title',
          'update' => true,
          'mapping' => array(
            'cl' => 'meta_title'
          )
        ),
        'metaDescription' => array(
          'type' => 'string',
          'description' => 'meta description',
          'update' => true,
          'mapping' => array(
            'cl' => 'meta_description'
          )
        ),
        'metaKeywords' => array(
          'type' => 'array[string]',
          'description' => 'meta keywords',
          'update' => false,
          'mapping' => array(
            'cl' => array(
              'field' => 'meta_keywords',
              'write' => 'REPLACE(<field>, ",", CHAR(1))',
            )
          )
        ),
        'hasSubcategories' => array(
          'type' => 'boolean',
          'description' => 'has subcategories',
          'sql' => 'c.nleft < (c.nright-1)',
          'require' => array('c'),
          'update' => false
        ),
        'created' => array(
          'type' => 'datetime',
          'description' => 'date created',
          'update' => false,
          'mapping' => array(
            'c' => 'date_add'
          )
        ),
        'image' => array(
          'type' => 'string',
          'virtual' => true,
          'description' => 'image',
          'set' => array($this, 'setImage')
        ),
        'updated' => array(
          'type' => 'datetime',
          'description' => 'date updated',
          'update' => false,
          'mapping' => array(
            'c' => 'date_upd'
          )
        )
      ),
      'expressions' => array(
        'image' => array(
          'type' => 'string',
          'expression' => 'categoryImage(<field:id>, <field:friendlyUrl>)',
          'description' => 'image'
        ),
        'description' => array(
          'type' => 'string',
          'expression' => 'clean(<field:description>)',
          'description' => 'description'
        ),
        'url' => array(
          'type' => 'string',
          'expression' => 'categoryUrl(<field:id>)',
          'description' => 'url'
        ),
        'path' => array(
          'type' => 'string',
          'expression' => 'toString(<field:path>)',
          'description' => 'path'
        )
      ),
      'links' => array(
        'categoryDefaultProduct' => array(
          'description' => 'Products using category as default',
          'collection' => 'products',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('categoryId'),
          'create' => true,
          'delete' => 'dissoc',
        ),
        'categoryProduct' => array(
          'description' => 'Products in category',
          'collection' => 'products',
          'type' => 'HABTM',
          'sourceFields' => array('id'),
          'targetFields' => array('id'),
          'joinTable' => 'category_product',
          'joinFields' => array(
            'sourceFields' => array('id_category'),
            'targetFields' => array('id_product')
          ),
          'create' => true,
          'delete' => true,
        ),
        'categoryDefaultCombination' => array(
          'description' => 'Combinations using category as default',
          'collection' => 'combinations',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('categoryId'),
          'delete' => false,
          'create' => false,
        ),
        'categoryCombination' => array(
          'description' => 'Combinations in category',
          'collection' => 'combinations',
          'type' => 'HABTM',
          'sourceFields' => array('id'),
          'targetFields' => array('productId'),
          'joinTable' => 'category_product',
          'joinFields' => array(
            'sourceFields' => array('id_category'),
            'targetFields' => array('id_product')
          ),
          'create' => false,
          'delete' => false,
        ),
      )
    ));
  }

  public function afterBatch($factory) {
    \Category::regenerateEntireNtree();
    $conn = $factory->getConnection();
    $cat = _DB_PREFIX_ . "category";
    $lev = "(COALESCE(c2.level_depth, -1)+1)";
    $sql = "UPDATE $cat c1 LEFT JOIN $cat c2 ON (c1.id_parent = c2.id_category) SET c1.level_depth = $lev WHERE c1.level_depth != $lev";
    $conn->execute($sql);
  }

  public function afterDelete($factory, $pks) {
    $this->afterBatch($factory);
    $handler = $this->getImageHandler($factory);
    foreach ($pks as $pk) {
      $id = (int)$pk['id'];
      $handler->delete($id);
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

  public function setImage($categoryId, $source, $factory, $context) {
    $this->getImageHandler($factory)->setImage($categoryId, $source, false);
  }

  private function getImageHandler($factory) {
    if (is_null($this->imageHandler)) {
      $this->imageHandler = new \Datakick\PrestashopImageHandler($factory, 'categories');
    }
    return $this->imageHandler;
  }

}
