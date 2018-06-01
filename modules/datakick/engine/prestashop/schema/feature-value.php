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

class FeatureValue {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'featureValues',
      'singular' => 'featureValue',
      'description' => 'Feature values',
      'key' => array('valueId'),
      'display' => 'value',
      'parameters' => array('language'),
      'category' => 'catalog',
      'psTab' => 'AdminFeatures',
      'create' => true,
      'delete' => true,
      'tables' => array(
        'v' => array(
          'table' => 'feature_value',
        ),
        'vl' => array(
          'table' => 'feature_value_lang',
          'require' => array('v'),
          'parameters' => array('language'),
          'create' => array(
            'id_feature_value' => '<pk>',
            'id_lang' => '<param:language>'
          ),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'vl.id_feature_value = v.id_feature_value',
              '<bind-param:language:vl.id_lang>'
            )
          )
        ),
        'fl' => array(
          'table' => 'feature_lang',
          'require' => array('v'),
          'parameters' => array('language'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              "fl.id_feature = v.id_feature",
              "<bind-param:language:fl.id_lang>"
            )
          )
        )
      ),
      'fields' => array(
        'featureId' => array(
          'type' => 'number',
          'description' => 'feature id',
          'mapping' => array('v' => 'id_feature'),
          'selectRecord' => 'features',
          'update' => true,
          'required' => true
        ),
        'valueId' => array(
          'type' => 'number',
          'description' => 'value id',
          'selectRecord' => 'featureValues',
          'mapping' => array('v' => 'id_feature_value'),
          'update' => false
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'mapping' => array('fl' => 'name'),
          'update' => false,
        ),
        'value' => array(
          'type' => 'string',
          'description' => 'value',
          'update' => true,
          'required' => true,
          'mapping' => array(
            'vl' => 'value'
          )
        ),
        'isCustom' => array(
          'type' => 'boolean',
          'description' => 'is custom',
          'update' => true,
          'mapping' => array(
            'v' => 'custom'
          )
        )
      ),
      'expressions' => array(
      ),
      'links' => array(
        'feature' => array(
          'description' => "Feature",
          'collection' => 'features',
          'type' => 'BELONGS_TO',
          'sourceFields' => array('featureId'),
          'targetFields' => array('id')
        ),
        'products' => array(
          'description' => 'Products with feature value',
          'collection' => 'products',
          'type' => 'HABTM',
          'sourceFields' => array('featureId', 'valueId'),
          'targetFields' => array('id'),
          'joinTable' => 'feature_product',
          'create' => true,
          'delete' => true,
          'joinFields' => array(
            'sourceFields' => array('id_feature', 'id_feature_value'),
            'targetFields' => array('id_product')
          )
        ),
        'combinations' => array(
          'description' => 'Combinations with feature value',
          'collection' => 'combinations',
          'type' => 'HABTM',
          'sourceFields' => array('featureId', 'valueId'),
          'targetFields' => array('productId'),
          'joinTable' => 'feature_product',
          'create' => false,
          'delete' => false,
          'joinFields' => array(
            'sourceFields' => array('id_feature', 'id_feature_value'),
            'targetFields' => array('id_product')
          )
        )
      )
    ));
  }
}
