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

class Country {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'countries',
      'singular' => 'country',
      'description' => 'Countries',
      'key' => array('id'),
      'category' => 'common',
      'display' => 'name',
      'parameters' => array('shop', 'language', 'defaultCurrency'),
      'psTab' => 'AdminCountries',
      'psController' => 'AdminCountries',
      'psClass' => 'Country',
      'restrictions' => array(
        'shop' => array(
          'shop' => '<field:shopId>'
        )
      ),
      'tables' => array(
        'cs' => array(
          'table' => 'country_shop',
          'conditions' => array(
            '<bind-param:shop:cs.id_shop>'
          )
        ),
        'c' => array(
          'table' => 'country',
          'require' => array('cs'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'cs.id_country = c.id_country',
              '<bind-param:shop:cs.id_shop>'
            )
          )
        ),
        'cl' => array(
          'table' => 'country_lang',
          'require' => array('cs'),
          'join' => array(
            'type' => 'LEFT',
            'conditions' => array(
              'cs.id_country = cl.id_country',
              '<bind-param:language:cl.id_lang>'
            )
          ),
          'parameters' => array('language'),
          'create' => array(
            'id_country' => '<pk>',
            'id_lang' => '<param:language>'
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'sql' => 'c.id_country',
          'selectRecord' => 'countries',
          'require' => array('c'),
          'update' => false
        ),
        'shopId' => array(
          'type' => 'number',
          'description' => 'shop id',
          'sql' => 'cs.id_shop',
          'require' => array('cs'),
          'update' => false,
          'hidden' => true
        ),
        'currencyId' => array(
          'type' => 'number',
          'description' => 'currency id',
          'sql' => 'IF(c.id_currency = 0, <param:defaultCurrency>, c.id_currency)',
          'selectRecord' => 'currencies',
          'require' => array('c'),
          'update' => array(
            'c' => 'id_currency'
          )
        ),
        'zoneId' => array(
          'type' => 'number',
          'description' => 'zone id',
          'sql' => 'c.id_zone',
          'selectRecord' => 'zones',
          'require' => array('c'),
          'update' => array(
            'c' => 'id_zone'
          )
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'name',
          'sql' => 'cl.name',
          'require' => array('cl'),
          'update' => array(
            'cl' => 'name'
          )
        ),
        'isoCode' => array(
          'type' => 'string',
          'description' => 'ISO code',
          'sql' => 'c.iso_code',
          'require' => array('c'),
          'update' => array(
            'c' => 'iso_code'
          )
        ),
        'callPrefix' => array(
          'type' => 'string',
          'description' => 'call prefix',
          'sql' => 'IF(c.call_prefix = 0, NULL, c.call_prefix)',
          'require' => array('c'),
          'update' => array(
            'c' => 'call_prefix'
          )
        ),
        'hasStates' => array(
          'type' => 'boolean',
          'description' => 'contains states',
          'sql' => 'c.contains_states',
          'require' => array('c'),
          'update' => array(
            'c' => 'contains_states'
          )
        ),
        'requireTaxID' => array(
          'type' => 'boolean',
          'description' => 'needs tax id number',
          'sql' => 'c.need_identification_number',
          'require' => array('c'),
          'update' => array(
            'c' => 'need_identification_number'
          )
        ),
        'requireZip' => array(
          'type' => 'boolean',
          'description' => 'needs zip/postal code',
          'sql' => 'c.need_zip_code',
          'require' => array('c'),
          'update' => array(
            'c' => 'need_zip_code'
          )
        ),
        'zipFormat' => array(
          'type' => 'string',
          'description' => 'zip/postal code format',
          'sql' => 'c.zip_code_format',
          'require' => array('c'),
          'update' => array(
            'c' => 'zip_code_format'
          )
        ),
        'displayTaxLabel' => array(
          'type' => 'boolean',
          'description' => 'display tax label',
          'sql' => 'c.display_tax_label',
          'require' => array('c'),
          'update' => array(
            'c' => 'display_tax_label'
          )
        ),
        'active' => array(
          'type' => 'boolean',
          'description' => 'is active',
          'sql' => 'c.active',
          'require' => array('c'),
          'update' => array(
            'c' => 'active'
          )
        ),
      ),
      'links' => array(
        'currency' => array(
          'description' => "Default currency",
          'collection' => 'currencies',
          'type' => 'HAS_ONE',
          'sourceFields' => array('currencyId'),
          'targetFields' => array('id')
        ),
        'zone' => array(
          'description' => "Country zone",
          'collection' => 'zones',
          'type' => 'BELONGS_TO',
          'sourceFields' => array('zoneId'),
          'targetFields' => array('id')
        ),
        'states' => array(
          'description' => "Country states",
          'collection' => 'states',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('countryId')
        ),
        'addresses' => array(
          'description' => "Addresses",
          'collection' => 'addresses',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('countryId')
        )
      )
    ));
  }
}
