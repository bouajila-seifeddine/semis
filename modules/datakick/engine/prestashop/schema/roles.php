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

class Roles {
  public function register($dictionary) {
    $dictionary->registerCollection(array(
      'id' => 'roles',
      'singular' => 'role',
      'description' => 'Roles',
      'key' => array('id'),
      'display' => 'name',
      'category' => 'permissions',
      'psTab' => 'AdminProfiles',
      'psController' => 'AdminProfiles',
      'psClass' => 'Profile',
      'parameters' => array('language'),
      'role' => 'role',
      'tables' => array(
        'p' => array(
          'table' => 'profile'
        ),
        'pl' => array(
          'table' => 'profile_lang',
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'pl.id_profile = p.id_profile',
              '<bind-param:language:pl.id_lang>'
            )
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'sql' => 'p.id_profile',
          'require' => array('p'),
          'selectRecord' => 'roles',
          'update' => false
        ),
        'name' => array(
          'type' => 'string',
          'description' => 'Role name',
          'sql' => 'pl.name',
          'require' => array('pl'),
          'update' => array(
            'pl' => 'name'
          )
        )
      ),
      'expressions' => array(
      ),
      'links' => array(
        'employees' => array(
          'description' => "Employees with role",
          'collection' => 'employees',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('roleId')
        )
      )
    ));
  }
}
