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

class Tag {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'tags',
      'singular' => 'tag',
      'description' => 'Tags',
      'key' => array('id'),
      'display' => 'name',
      'parameters' => array(),
      'category' => 'catalog',
      'psTab' => 'AdminTags',
      'psController' => 'AdminTags',
      'psClass' => 'Tag',
      'create' => true,
      'delete' => true,
      'tables' => array(
        'tag' => array(
          'table' => 'tag'
        ),
        'lang' => array(
          'table' => 'lang',
          'require' => array('tag'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              "tag.id_lang = lang.id_lang",
            )
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'selectRecord' => 'tags',
          'update' => false,
          'mapping' => array(
            'tag' => 'id_tag'
          )
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'update' => true,
          'mapping' => array(
            'tag' => 'name'
          ),
          'required' => true
        ),
        'languageId' => array(
          'type' => 'number',
          'description' => 'language id',
          'update' => true,
          'selectRecord' => 'languages',
          'mapping' => array(
            'tag' => 'id_lang'
          ),
          'required' => true
        ),
        'languageName' => array(
          'type' => 'string',
          'description' => 'language name',
          'update' => false,
          'mapping' => array(
            'lang' => 'name'
          )
        )
      ),
      'expressions' => array(),
      'links' => array(
        'products' => array(
          'description' => "Tagged products",
          'collection' => 'products',
          'type' => 'HABTM',
          'sourceFields' => array('id', 'languageId'),
          'targetFields' => array('id'),
          'joinTable' => 'product_tag',
          'joinFields' => array(
            'sourceFields' => array('id_tag', 'id_lang'),
            'targetFields' => array('id_product')
          ),
          'create' => true,
          'delete' => true,
        ),
        'combinations' => array(
          'description' => "Tagged combinations",
          'collection' => 'combinations',
          'type' => 'HABTM',
          'sourceFields' => array('id', 'languageId'),
          'targetFields' => array('productId'),
          'joinTable' => 'product_tag',
          'delete' => false,
          'create' => false,
          'joinFields' => array(
            'sourceFields' => array('id_tag', 'id_lang'),
            'targetFields' => array('id_product')
          ),
        )
      )
    ));
  }
}
