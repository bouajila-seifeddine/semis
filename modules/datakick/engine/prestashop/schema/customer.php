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

class Customer {
  public function register($dictionary) {
    $db = \DB::getInstance();
    $hideB2B = $db->getValue("SELECT IFNULL((SELECT 0 FROM "._DB_PREFIX_."configuration where name = 'PS_B2B_ENABLE' and value='1'), 1)");

    $dictionary->registerCollection(array(
      'id' => 'customers',
      'singular' => 'customer',
      'description' => 'Customers',
      'parameters' => array('shop', 'language', 'shopGroup', 'shareCustomers', 'defaultCurrency'),
      'key' => array('id'),
      'display' => 'fullname',
      'category' => 'relationships',
      'priority' => 300,
      'psTab' => 'AdminCustomers',
      'psController' => 'AdminCustomers',
      'psClass' => 'Customer',
      'create' => true,
      'delete' => true,
      'restrictions' => array(
        'shop' => array(
          'shop' => '<field:shopId>'
        )
      ),
      'tables' => array(
        'c' => array(
          'table' => 'customer',
          'conditions' => array(
            'if(<bind-param:shareCustomers:1>, <bind-param:shopGroup:c.id_shop_group>, <bind-param:shop:c.id_shop>)'
          ),
          'create' => array(
            'id_gender' => 0,
            'active' => 1,
            'date_add' => '<param:timestamp>',
            'date_upd' => '<param:timestamp>'
          )
        ),
        'gl' => array(
          'table' => 'gender_lang',
          'require' => array('c'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'gl.id_gender = c.id_gender',
              '<bind-param:language:gl.id_lang>'
            )
          )
        ),
        'r' => array(
          'table' => 'risk',
          'require' => array('c'),
          'join' => array(
            'type' => 'LEFT',
            'conditions' => array(
              'c.id_risk = r.id_risk'
            )
          )
        ),
        'rl' => array(
          'table' => 'risk_lang',
          'require' => array('r'),
          'join' => array(
            'type' => 'LEFT',
            'conditions' => array(
              'r.id_risk = rl.id_risk',
              '<bind-param:language:rl.id_lang>'
            )
          )
        ),
        's' => array(
          'table' => 'shop',
          'require' => array('c'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              's.id_shop = c.id_shop'
            )
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'mapping' => array(
            'c' => 'id_customer',
          ),
          'selectRecord' => 'customers',
          'update' => false
        ),
        'shopId' => array(
          'type' => 'number',
          'description' => 'shop id',
          'mapping' => array(
            'c' => 'id_shop'
          ),
          'hidden' => true,
          'update' => false
        ),
        'shop' => array(
          'type' => 'string',
          'description' => 'shop',
          'mapping' => array(
            's' => 'name'
          ),
          'hidden' => ! SchemaUtils::isMultiShop(),
          'update' => false
        ),
        'defaultGroupId' => array(
          'type' => 'number',
          'description' => 'default group id',
          'selectRecord' => 'customerGroups',
          'mapping' => array(
            'c' => 'id_default_group'
          ),
          'update' => true
        ),
        'fullname' => array(
          'type' => 'string',
          'description' => 'name',
          'sql' => "TRIM(CONCAT(c.firstname, ' ', c.lastname))",
          'require' => array('c'),
          'update' => false
        ),
        'title' => array(
          'type' => 'string',
          'description' => 'title',
          'mapping' => array('gl' => 'name'),
          'update' => false
        ),
        'firstname' => array(
          'type' => 'string',
          'description' => 'first name',
          'update' => true,
          'required' => true,
          'mapping' => array(
            'c' => array(
              'field' => 'firstname',
              'read' => 'TRIM(<field>)',
              'write' => 'TRIM(<field>)'
            )
          )
        ),
        'lastname' => array(
          'type' => 'string',
          'description' => 'last name',
          'update' => true,
          'required' => true,
          'mapping' => array(
            'c' => array(
              'field' => 'lastname',
              'read' => 'TRIM(<field>)',
              'write' => 'TRIM(<field>)'
            )
          )
        ),
        'email' => array(
          'type' => 'string',
          'description' => 'email',
          'mapping' => array(
            'c' => 'email'
          ),
          'required' => true,
          'update' => true,
        ),
        'password' => array(
          'type' => 'string',
          'description' => 'password',
          'mapping' => array(
            'c' => 'passwd'
          ),
          'required' => true,
          'update' => true,
        ),
        'birthday' => array(
          'type' => 'datetime',
          'description' => 'birthday',
          'update' => true,
          'mapping' => array(
            'c' => array(
              'field' => 'birthday',
              'read' => "IF(<field> < '1900-01-01', NULL, <field>)",
              'write' => "<field>"
            )
          )
        ),
        'active' => array(
          'type' => 'boolean',
          'description' => 'is enabled',
          'mapping' => array(
            'c' => 'active'
          ),
          'update' => true
        ),
        'deleted' => array(
          'type' => 'boolean',
          'description' => 'is deleted',
          'mapping' => array(
            'c' => 'deleted'
          ),
          'update' => true
        ),
        'newsletter' => array(
          'type' => 'boolean',
          'description' => 'newsletter',
          'mapping' => array(
            'c' => 'newsletter'
          ),
          'update' => true
        ),
        'optIn' => array(
          'type' => 'boolean',
          'description' => 'Opt-in',
          'mapping' => array(
            'c' => 'optin'
          ),
          'update' => true
        ),
        'gender' => array(
          'type' => 'string',
          'description' => 'gender',
          'update' => true,
          'mapping' => array(
            'c' => array(
              'field' => 'id_gender',
              'read' => "(SELECT IF(g.type=0, 'M', IF(g.type=1, 'F', IF(g.type=2, 'N', ''))) FROM "._DB_PREFIX_."gender g WHERE g.id_gender = <field>)",
              'write' => "COALESCE((SELECT id_gender FROM "._DB_PREFIX_."gender WHERE type=IF(<field>='M', 0, IF(<field>='F', 1, -1))), 0)"
            )
          )
        ),
        'created' => array(
          'type' => 'datetime',
          'description' => 'date created',
          'mapping' => array(
            'c' => 'date_add'
          ),
          'update' => true
        ),
        'updated' => array(
          'type' => 'datetime',
          'description' => 'date updated',
          'mapping' => array(
            'c' => 'date_upd'
          ),
          'update' => true
        ),
        'lastVisit' => array(
          'type' => 'datetime',
          'description' => 'last visit',
          'sql' => '(SELECT con.date_add FROM '._DB_PREFIX_.'guest guest LEFT JOIN '._DB_PREFIX_.'connections con ON con.id_guest = guest.id_guest WHERE guest.id_customer = c.id_customer ORDER BY con.date_add DESC LIMIT 1)',
          'require' => array('c'),
          'update' => false
        ),
        // B2B feature on
        'company' => array(
          'type' => 'string',
          'description' => 'company',
          'hidden' => $hideB2B,
          'update' => true,
          'mapping' => array(
            'c' => 'company'
          )
        ),
        'siret' => array(
          'type' => 'string',
          'description' => 'SIRET',
          'hidden' => $hideB2B,
          'update' => true,
          'mapping' => array(
            'c' => 'siret'
          )
        ),
        'ape' => array(
          'type' => 'string',
          'description' => 'APE',
          'hidden' => $hideB2B,
          'update' => true,
          'mapping' => array(
            'c' => 'ape'
          )
        ),
        'website' => array(
          'type' => 'string',
          'description' => 'website',
          'hidden' => $hideB2B,
          'update' => true,
          'mapping' => array(
            'c' => 'website'
          )
        ),
        'allowOutstanding' => array(
          'type' => 'currency',
          'description' => 'allowed outstanding amount',
          'hidden' => $hideB2B,
          'fixedCurrency' => true,
          'update' => true,
          'mapping' => array(
            'c' => array(
              'field' => array(
                'value' => 'outstanding_allow_amount',
                'currency' => '<param:defaultCurrency>'
              ),
            )
          )
        ),
        'paymentDays' => array(
          'type' => 'number',
          'description' => 'max payment days',
          'hidden' => $hideB2B,
          'update' => true,
          'mapping' => array(
            'c' => 'max_payment_days'
          )
        ),
        'risk' => array(
          'type' => 'string',
          'description' => 'risk',
          'sql' => 'coalesce(rl.name, r.percent)',
          'require' => array('rl', 'r'),
          'hidden' => $hideB2B,
          'update' => false
        ),
        'riskPercent' => array(
          'type' => 'number',
          'description' => 'risk %',
          'sql' => 'r.percent',
          'require' => array('r'),
          'hidden' => $hideB2B,
          'update' => false
        ),
      ),
      'expressions' => array(
        'age' => array(
          'type' => 'number',
          'expression' => 'floor(dateDiff(now(), <field:birthday>) / 365)',
          'description' => 'age'
        )
      ),
      'links' => array(
        'defaultGroup' => array(
          'description' => "Default group",
          'collection' => 'customerGroups',
          'type' => 'HAS_ONE',
          'sourceFields' => array('defaultGroupId'),
          'targetFields' => array('id')
        ),
        'groups' => array(
          'description' => "Customer groups",
          'collection' => 'customerGroups',
          'type' => 'HABTM',
          'sourceFields' => array('id'),
          'targetFields' => array('id'),
          'joinTable' => 'customer_group',
          'joinFields' => array(
            'sourceFields' => array('id_customer'),
            'targetFields' => array('id_group'),
          ),
          'create' => true,
          'delete' => 'dissoc'
        ),
        'addresses' => array(
          'description' => "Addresses",
          'collection' => 'addresses',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('customerId'),
          'create' => true,
          'delete' => true
        ),
        'orders' => array(
          'description' => "Orders",
          'collection' => 'orders',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('customerId'),
          'create' => true,
          'delete' => false
        ),
        'carts' => array(
          'description' => "Carts",
          'collection' => 'carts',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('customerId'),
          'create' => false,
          'delete' => false
        )
      )
    ));
  }
}
