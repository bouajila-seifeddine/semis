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

class AttributeValue {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'attributeValues',
      'singular' => 'attributeValue',
      'description' => 'Attribute values',
      'key' => array('valueId'),
      'display' => 'displayValue',
      'parameters' => array('shop', 'language'),
      'category' => 'catalog',
      'create' => true,
      'delete' => true,
      'psTab' => 'AdminAttributesGroups',
      'restrictions' => array(
        'shop' => array(
          'shop' => '<field:shopId>'
        )
      ),
      'tables' => array(
        'v' => array(
          'table' => 'attribute'
        ),
        'vs' => array(
          'table' => 'attribute_shop',
          'primary' => true,
          'require' => array('v'),
          'parameters' => array('shop'),
          'create' => array(
            'id_attribute' => '<pk>',
            'id_shop' => '<param:shop>'
          ),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              "vs.id_attribute = v.id_attribute",
              '<bind-param:shop:vs.id_shop>'
            )
          )
        ),
        'vl' => array(
          'table' => 'attribute_lang',
          'require' => array('vs'),
          'parameters' => array('language'),
          'create' => array(
            'id_attribute' => '<pk>',
            'id_lang' => '<param:language>'
          ),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'vl.id_attribute = v.id_attribute',
              '<bind-param:language:vl.id_lang>'
            )
          )
        ),
        'a' => array(
          'table' => 'attribute_group',
          'require' => array('v'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'a.id_attribute_group = v.id_attribute_group'
            )
          )
        ),
        'al' => array(
          'table' => 'attribute_group_lang',
          'require' => array('v'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              "al.id_attribute_group = v.id_attribute_group",
              "<bind-param:language:al.id_lang>"
            )
          )
        )
      ),
      'fields' => array(
        'shopId' => array(
          'type' => 'number',
          'description' => 'shop id',
          'mapping' => array('vs' => 'id_shop'),
          'update' => false,
          'hidden' => true
        ),
        'attributeId' => array(
          'type' => 'number',
          'description' => 'id',
          'required' => true,
          'mapping' => array(
            'v' => 'id_attribute_group'
          ),
          'selectRecord' => 'attributes',
          'update' => false
        ),
        'valueId' => array(
          'type' => 'number',
          'description' => 'value id',
          'selectRecord' => 'attributeValues',
          'mapping' => array(
            'v' => 'id_attribute',
            'vs' => 'id_attribute',
            'vl' => 'id_attribute'
          ),
          'update' => false
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'mapping' => array('al' => 'name'),
          'update' => false
        ),
        'color' => array(
          'type' => 'string',
          'description' => 'color',
          'update' => true,
          'mapping' => array(
            'v' => array(
              'field' => 'color',
              'read' => "IF(<field> REGEXP '^#[0-9a-zA-Z]{3,6}', <field>, null)",
              'write' => "IF(<field> REGEXP '^#[0-9a-zA-Z]{3,6}', <field>, null)"
            )
          )
        ),
        'value' => array(
          'type' => 'string',
          'description' => 'value',
          'update' => false,
          'hidden' => 'true',
          'mapping' => array(
            'vl' => 'name'
          )
        ),
        'displayValue' => array(
          'type' => 'string',
          'description' => 'display value',
          'update' => true,
          'required' => true,
          'mapping' => array(
            'vl' => 'name'
          )
        ),
        'attributeType' => array(
          'type' => 'string',
          'description' => 'attribute type',
          'values' => array(
            'select' => 'Dropdown list',
            'radio' => 'Radio buttons',
            'color' => 'Color or texture'
          ),
          'update' => false,
          'mapping' => array(
            'a' => 'group_type'
          ),
        )
      ),
      'expressions' => array(
        'value' => array(
          'type' => 'string',
          'description' => 'value',
          'expression' => 'if(<field:attributeType> == "color", <field:color>, <field:value>)'
        )
      ),
      'links' => array(
        'attribute' => array(
          'description' => "Attribute",
          'collection' => 'attributes',
          'type' => 'BELONGS_TO',
          'sourceFields' => array('attributeId'),
          'targetFields' => array('id')
        ),
        'combinations' => array(
          'description' => 'Product combinations',
          'collection' => 'combinations',
          'type' => 'HABTM',
          'delete' => true,
          'sourceFields' => array('valueId'),
          'targetFields' => array('id'),
          'joinTable' => 'product_attribute_combination',
          'joinFields' => array(
            'sourceFields' => array('id_attribute'),
            'targetFields' => array('id_product_attribute'),
          )
        )
      )
    ));
  }
}
