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

class Feature {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'features',
      'singular' => 'feature',
      'description' => 'Features',
      'key' => array('id'),
      'display' => 'name',
      'parameters' => array('shop', 'language'),
      'category' => 'catalog',
      'psTab' => 'AdminFeatures',
      'create' => true,
      'delete' => true,
      'restrictions' => array(
        'shop' => array(
          'shop' => '<field:shopId>'
        )
      ),
      'tables' => array(
        'f' => array(
          'table' => 'feature'
        ),
        'fs' => array(
          'table' => 'feature_shop',
          'require' => array('f'),
          'primary' => true,
          'parameters' => array('shop'),
          'create' => array(
            'id_feature' => '<pk>',
            'id_shop' => '<param:shop>'
          ),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              "fs.id_feature = f.id_feature",
              '<bind-param:shop:fs.id_shop>'
            )
          )
        ),
        'fl' => array(
          'table' => 'feature_lang',
          'require' => array('fs'),
          'parameters' => array('language'),
          'create' => array(
            'id_feature' => '<pk>',
            'id_lang' => '<param:language>'
          ),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              "fl.id_feature = fs.id_feature",
              "<bind-param:language:fl.id_lang>"
            )
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'selectRecord' => 'features',
          'update' => false,
          'mapping' => array(
            'f' => 'id_feature',
            'fs' => 'id_feature',
            'fl' => 'id_feature'
          )
        ),
        'shopId' => array(
          'type' => 'number',
          'description' => 'shop id',
          'update' => false,
          'hidden' => true,
          'mapping' => array(
            'fs' => 'id_shop'
          )
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'required' => true,
          'update' => true,
          'mapping' => array(
            'fl' => 'name'
          )
        ),
        'position' => array(
          'type' => 'number',
          'description' => 'position',
          'update' => true,
          'mapping' => array(
            'f' => 'position'
          )
        )
      ),
      'expressions' => array(
      ),
      'links' => array(
        'values' => array(
          'description' => "Feature values",
          'collection' => 'featureValues',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('featureId'),
          'create' => true,
          'delete' => true
        )
      )
    ));
  }
}
