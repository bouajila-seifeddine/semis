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

class Employees {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'employees',
      'singular' => 'employee',
      'description' => 'Employees',
      'key' => array('id'),
      'display' => 'name',
      'category' => 'permissions',
      'psTab' => 'AdminEmployees',
      'psController' => 'AdminEmployees',
      'psClass' => 'Employee',
      'role' => 'user',
      'restrictions' => array(
        'user' => array(
          'user' => '<field:id>',
          'public' => '1',
        )
      ),
      'parameters' => array(),
      'tables' => array(
        'e' => array(
          'table' => 'employee'
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'sql' => 'e.id_employee',
          'require' => array('e'),
          'selectRecord' => 'employees',
          'update' => false
        ),
        'roleId' => array(
          'type' => 'number',
          'description' => 'role id',
          'sql' => 'e.id_profile',
          'require' => array('e'),
          'selectRecord' => array(
            'role' => 'role'
          ),
          'update' => false
        ),
        'languageId' => array(
          'type' => 'number',
          'description' => 'language id',
          'sql' => 'e.id_lang',
          'require' => array('e'),
          'selectRecord' => 'languages',
          'update' => array(
            'e' => 'id_lang'
          ),
        ),
        'defaultTabId' => array(
          'type' => 'number',
          'description' => 'default tab id',
          'sql' => 'e.default_tab',
          'require' => array('e'),
          'update' => false,
          'hidden' => true
        ),
        'firstname' => array(
          'type' => 'string',
          'description' => 'first name',
          'sql' => 'e.firstname',
          'require' => array('e'),
          'update' => array(
            'e' => 'firstname'
          )
        ),
        'lastname' => array(
          'type' => 'string',
          'description' => 'last name',
          'sql' => 'e.lastname',
          'require' => array('e'),
          'update' => array(
            'e' => 'lastname'
          )
        ),
        'enabled' => array(
          'type' => 'boolean',
          'description' => 'is enabled',
          'sql' => 'e.active',
          'require' => array('e'),
          'update' => array(
            'e' => 'active'
          )
        ),
        'email' => array(
          'type' => 'string',
          'description' => 'email',
          'sql' => 'e.email',
          'require' => array('e'),
          'update' => array(
            'e' => 'email'
          )
        ),
        'password' => array(
          'type' => 'string',
          'description' => 'password [hashed]',
          'sql' => 'e.passwd',
          'require' => array('e'),
          'update' => array(
            'e' => 'passwd'
          )
        ),
        'passwordChanged' => array(
          'type' => 'datetime',
          'description' => 'password changed',
          'sql' => 'e.last_passwd_gen',
          'require' => array('e'),
          'update' => array(
            'e' => 'last_passwd_gen'
          )
        ),
        'lastSeen' => array(
          'type' => 'datetime',
          'description' => 'last seen',
          'sql' => 'e.last_connection_date',
          'require' => array('e'),
          'update' => false
        ),
        'theme' => array(
          'type' => 'string',
          'description' => 'back office theme',
          'sql' => 'e.bo_theme',
          'require' => array('e'),
          'update' => array(
            'e' => 'bo_theme'
          )
        ),
        'menuOrientation' => array(
          'type' => 'string',
          'description' => 'menu orientation',
          'values' => array(
            'top' => 'Top',
            'left' => 'Left'
          ),
          'sql' => 'IF(e.bo_menu=0, "top", "left")',
          'require' => array('e'),
          'update' => array(
            'e' => array(
              'field' => 'bo_menu',
              'write' => "IF(<field>='top', 0, 1)",
              'read' => "IF(<field>=0, 'top', 'left')"
            )
          )
        ),
      ),
      'expressions' => array(
        'name' => array(
          'type' => 'string',
          'expression' => '<field:firstname> + " " + <field:lastname>',
          'description' => 'name'
        )
      ),
      'links' => array(
        'role' => array(
          'description' => "Role",
          'collection' => 'roles',
          'type' => 'BELONGS_TO',
          'sourceFields' => array('roleId'),
          'targetFields' => array('id')
        ),
        'language' => array(
          'description' => "Default language",
          'collection' => 'languages',
          'type' => 'HAS_ONE',
          'sourceFields' => array('languageId'),
          'targetFields' => array('id')
        ),
        'warehouse' => array(
          'description' => "Warehouse manager",
          'collection' => 'warehouses',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('employeeId')
        ),
        'stockMovements' => array(
          'description' => "Stock movements",
          'collection' => 'stockMovements',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('employeeId')
        )
      )
    ));
  }
}
