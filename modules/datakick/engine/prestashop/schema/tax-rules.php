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

class TaxRules {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'taxRules',
      'singular' => 'TaxRule',
      'description' => 'Tax Rules',
      'key' => array('id'),
      'category' => 'taxes',
      'display' => 'name',
      'parameters' => array('shop'),
      'psTab' => 'AdminTaxRulesGroup',
      'psController' => 'AdminTaxRulesGroup',
      'psClass' => 'TaxRulesGroup',
      'restrictions' => array(
        'shop' => array(
          'shop' => '<field:shopId>'
        )
      ),
      'tables' => array(
        'g' => array(
          'table' => 'tax_rules_group',
        ),
        'gs' => array(
          'table' => 'tax_rules_group_shop',
          'primary' => true,
          'require' => array('g'),
          'parameters' => array('shop'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              '<bind-param:shop:gs.id_shop>',
              'gs.id_tax_rules_group = g.id_tax_rules_group'
            )
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'selectRecord' => 'taxRules',
          'update' => false,
          'mapping' => array(
            'g' => 'id_tax_rules_group',
            'gs' => 'id_tax_rules_group',
          )
        ),
        'shopId' => array(
          'type' => 'number',
          'description' => 'shop id',
          'update' => false,
          'hidden' => true,
          'mapping' => array(
            'gs' => 'id_shop',
          )
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'update' => true,
          'mapping' => array(
            'g' => 'name'
          )
        ),
        'active' => array(
          'type' => 'boolean',
          'description' => 'is active',
          'update' => true,
          'mapping' => array(
            'g' => 'active'
          )
        ),
        'deleted' => array(
          'type' => 'boolean',
          'description' => 'is deleted',
          'update' => false,
          'mapping' => array(
            'g' => 'deleted'
          )
        ),
      ),
      'links' => array(
        'products' => array(
          'description' => "Products",
          'collection' => 'products',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('taxRuleId')
        )
      )
    ));
  }
}
