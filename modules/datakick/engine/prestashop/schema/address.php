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

class Address {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'addresses',
      'singular' => 'address',
      'description' => 'Addresses',
      'parameters' => array(),
      'key' => array('id'),
      'display' => 'alias',
      'category' => 'relationships',
      'psTab' => 'AdminAddresses',
      'psController' => 'AdminAddresses',
      'psClass' => 'Address',
      'create' => true,
      'delete' => true,
      'tables' => array(
        'a' => array(
          'table' => 'address',
          'create' => array(
            'date_add' => '<param:timestamp>',
            'date_upd' => '<param:timestamp>'
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'mapping' => array('a' => 'id_address'),
          'selectRecord' => 'addresses',
          'update' => false
        ),
        'countryId' => array(
          'type' => 'number',
          'description' => 'country id',
          'selectRecord' => 'countries',
          'update' => true,
          'required' => true,
          'mapping' => array(
            'a' => 'id_country'
          )
        ),
        'customerId' => array(
          'type' => 'number',
          'description' => 'customer id',
          'selectRecord' => 'customers',
          'update' => true,
          'mapping' => array(
            'a' => 'id_customer'
          )
        ),
        'manufacturerId' => array(
          'type' => 'number',
          'description' => 'manufacturer id',
          'update' => true,
          'selectRecord' => 'manufacturers',
          'mapping' => array(
            'a' => 'id_manufacturer'
          )
        ),
        'supplierId' => array(
          'type' => 'number',
          'description' => 'supplier id',
          'update' => true,
          'selectRecord' => 'suppliers',
          'mapping' => array(
            'a' => 'id_supplier'
          )
        ),
        'warehouseId' => array(
          'type' => 'number',
          'description' => 'warehouse id',
          'selectRecord' => 'warehouses',
          'update' => true,
          'mapping' => array(
            'a' => 'id_warehouse'
          )
        ),
        'type' => array(
          'type' => 'string',
          'description' => 'type',
          'sql' => 'IF(a.id_customer > 0, "customer", IF(a.id_manufacturer > 0, "manufacturer", IF(a.id_supplier > 0, "supplier", "warehouse")))',
          'require' => array('a'),
          'values' => array(
            'customer' => "Customer's address",
            'manufacturer' => "Manufacturer's address",
            'supplier' => "Supplier's address",
            'warehouse' => "Warehouse address"
          ),
          'update' => false
        ),
        'stateId' => array(
          'type' => 'number',
          'description' => 'state id',
          'update' => true,
          'selectRecord' => 'states',
          'mapping' => array(
            'a' => 'id_state'
          )
        ),
        'alias' => array(
          'type' => 'string',
          'description' => 'alias',
          'update' => true,
          'required' => true,
          'mapping' => array(
            'a' => 'alias'
          )
        ),
        'firstname' => array(
          'type' => 'string',
          'description' => 'first name',
          'update' => true,
          'required' => true,
          'mapping' => array(
            'a' => 'firstname'
          )
        ),
        'lastname' => array(
          'type' => 'string',
          'description' => 'last name',
          'update' => true,
          'required' => true,
          'mapping' => array(
            'a' => 'lastname'
          )
        ),
        'address1' => array(
          'type' => 'string',
          'description' => 'address line 1',
          'update' => true,
          'required' => true,
          'mapping' => array(
            'a' => 'address1'
          )
        ),
        'address2' => array(
          'type' => 'string',
          'description' => 'address line 2',
          'update' => true,
          'mapping' => array(
            'a' => 'address2'
          )
        ),
        'zipCode' => array(
          'type' => 'string',
          'description' => 'zip / postcode',
          'update' => true,
          'mapping' => array(
            'a' => 'postcode'
          )
        ),
        'city' => array(
          'type' => 'string',
          'description' => 'city',
          'update' => true,
          'required' => true,
          'mapping' => array(
            'a' => 'city'
          )
        ),
        'active' => array(
          'type' => 'boolean',
          'description' => 'is enabled',
          'update' => true,
          'mapping' => array(
            'a' => 'active'
          )
        ),
        'deleted' => array(
          'type' => 'boolean',
          'description' => 'is deleted',
          'update' => true,
          'mapping' => array(
            'a' => 'deleted'
          )
        ),
        'other' => array(
          'type' => 'string',
          'description' => 'other',
          'update' => true,
          'mapping' => array(
            'a' => 'other'
          )
        ),
        'phone' => array(
          'type' => 'string',
          'description' => 'phone',
          'update' => true,
          'mapping' => array(
            'a' => 'phone'
          )
        ),
        'mobile' => array(
          'type' => 'string',
          'description' => 'mobile phone',
          'update' => true,
          'mapping' => array(
            'a' => 'phone_mobile'
          )
        ),
        'taxNumber' => array(
          'type' => 'string',
          'description' => 'tax number',
          'update' => true,
          'mapping' => array(
            'a' => 'vat_number'
          )
        ),
        'idNumber' => array(
          'type' => 'string',
          'description' => 'identification number',
          'update' => true,
          'mapping' => array(
            'a' => 'dni'
          )
        ),
        'created' => array(
          'type' => 'datetime',
          'description' => 'date created',
          'update' => true,
          'mapping' => array(
            'a' => 'date_add'
          )
        ),
        'updated' => array(
          'type' => 'datetime',
          'description' => 'date updated',
          'update' => true,
          'mapping' => array(
            'a' => 'date_upd'
          )
        ),
        'company' => array(
          'type' => 'string',
          'description' => 'company',
          'update' => true,
          'mapping' => array(
            'a' => 'company'
          )
        )
      ),
      'expressions' => array(
      ),
      'links' => array(
        'country' => array(
          'description' => "Country",
          'collection' => 'countries',
          'type' => 'BELONGS_TO',
          'sourceFields' => array('countryId'),
          'targetFields' => array('id')
        ),
        'state' => array(
          'description' => "State",
          'collection' => 'states',
          'type' => 'HAS_ONE',
          'sourceFields' => array('stateId'),
          'targetFields' => array('id')
        ),
        'customer' => array(
          'description' => "Customer",
          'collection' => 'customers',
          'type' => 'HAS_ONE',
          'sourceFields' => array('customerId'),
          'targetFields' => array('id')
        ),
        'manufacturer' => array(
          'description' => "Manufacturer",
          'collection' => 'manufacturers',
          'type' => 'HAS_ONE',
          'sourceFields' => array('manufacturerId'),
          'targetFields' => array('id')
        ),
        'warehouse' => array(
          'description' => "Warehouse",
          'collection' => 'warehouses',
          'type' => 'HAS_ONE',
          'sourceFields' => array('warehouseId'),
          'targetFields' => array('id')
        ),
        'supplier' => array(
          'description' => "Supplier",
          'collection' => 'suppliers',
          'type' => 'HAS_ONE',
          'sourceFields' => array('supplierId'),
          'targetFields' => array('id')
        ),
        'orders' => array(
          'description' => "Orders",
          'collection' => 'orders',
          'type' => 'HAS_MANY',
          'delete' => false,
          'joins' => array(
            array(
              'sourceFields' => array('id'),
              'targetFields' => array('deliveryAddressId')
            ),
            array(
              'sourceFields' => array('id'),
              'targetFields' => array('invoiceAddressId')
            )
          )
        )
      )
    ));
  }
}
