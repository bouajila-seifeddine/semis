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

class Attribute {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'attributes',
      'singular' => 'attribute',
      'description' => 'Attributes',
      'key' => array('id'),
      'display' => 'name',
      'parameters' => array('shop', 'language'),
      'category' => 'catalog',
      'psTab' => 'AdminAttributesGroups',
      'create' => true,
      'delete' => true,
      'restrictions' => array(
        'shop' => array(
          'shop' => '<field:shopId>'
        )
      ),
      'tables' => array(
        'a' => array(
          'table' => 'attribute_group'
        ),
        'as' => array(
          'table' => 'attribute_group_shop',
          'require' => array('a'),
          'primary' => true,
          'parameters' => array('shop'),
          'create' => array(
            'id_attribute_group' => '<pk>',
            'id_shop' => '<param:shop>'
          ),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              "as.id_attribute_group = a.id_attribute_group",
              '<bind-param:shop:as.id_shop>'
            )
          )
        ),
        'al' => array(
          'table' => 'attribute_group_lang',
          'require' => array('as'),
          'parameters' => array('language'),
          'create' => array(
            'id_attribute_group' => '<pk>',
            'id_lang' => '<param:language>'
          ),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              "al.id_attribute_group = as.id_attribute_group",
              "<bind-param:language:al.id_lang>"
            )
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'selectRecord' => 'attributes',
          'update' => false,
          'mapping' => array(
            'a' => 'id_attribute_group',
            'as' => 'id_attribute_group',
            'al' => 'id_attribute_group'
          )
        ),
        'shopId' => array(
          'type' => 'number',
          'description' => 'shop id',
          'mapping' => array('as' => 'id_shop'),
          'update' => false,
          'hidden' => true
        ),
        'type' => array(
          'type' => 'string',
          'description' => 'type',
          'values' => array(
            'select' => 'Dropdown list',
            'radio' => 'Radio buttons',
            'color' => 'Color or texture'
          ),
          'update' => true,
          'mapping' => array(
            'a' => 'group_type'
          ),
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'update' => true,
          'required' => true,
          'mapping' => array(
            'al' => 'name'
          )
        ),
        'publicName' => array(
          'type' => 'string',
          'description' => 'public name',
          'update' => true,
          'required' => true,
          'mapping' => array(
            'al' => 'public_name'
          )
        ),
        'position' => array(
          'type' => 'number',
          'description' => 'position',
          'update' => true,
          'mapping' => array(
            'a' => 'position'
          )
        )
      ),
      'expressions' => array(
      ),
      'links' => array(
        'values' => array(
          'description' => "Values",
          'collection' => 'attributeValues',
          'type' => 'HAS_MANY',
          'delete' => true,
          'sourceFields' => array('id'),
          'targetFields' => array('attributeId'),
          'create' => true
        )
      )
    ));
  }
}
