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

class CMSCategory {
  public function register($factory) {
    $factory->registerCollection(array(
      'id' => 'cmsCategories',
      'singular' => 'cmsCategory',
      'description' => 'CMS Categories',
      'key' => array('id'),
      'display' => 'name',
      'category' => 'cms',
      'psTab' => 'AdminCmsContent',
      'psController' => 'AdminCmsContent',
      'psClass' => 'CMSCategory',
      'parameters' => array('shop', 'language'),
      'restrictions' => array(
        'shop' => array(
          'shop' => '<field:shopId>'
        )
      ),
      'tables' => array(
        'cs' => array(
          'table' => 'cms_category_shop',
          'conditions' => array(
            '<bind-param:shop:cs.id_shop>'
          )
        ),
        'c' => array(
          'table' => 'cms_category',
          'require' => array('cs'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'cs.id_cms_category = c.id_cms_category'
            )
          )
        ),
        'cl' => array(
          'table' => 'cms_category_lang',
          'require' => array('cs'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              "cl.id_cms_category = cs.id_cms_category",
              "cl.id_shop = cs.id_shop",
              "<bind-param:language:cl.id_lang>"
            )
          )
        )
      ),
      'fields' => array(
        'id' => array(
          'type' => 'number',
          'description' => 'id',
          'sql' => 'cs.id_cms_category',
          'require' => array('cs'),
          'require' => array('cs'),
          'selectRecord' => 'cmsCategories',
          'update' => false
        ),
        'shopId' => array(
          'type' => 'number',
          'description' => 'shop id',
          'sql' => 'cs.id_shop',
          'require' => array('cs'),
          'selectRecord' => 'shops',
          'update' => false
        ),
        'active' => array(
          'type' =>  'boolean',
          'description' => 'active',
          'sql' => 'c.active',
          'require' => array('c'),
          'update' => array(
            'c' => 'active'
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
        'description' => array(
          'type' => 'string',
          'description' => 'description',
          'sql' => 'cl.description',
          'require' => array('cl'),
          'update' => array(
            'cl' => 'description'
          )
        ),
        'friendlyUrl' => array(
          'type' => 'string',
          'description' => 'friendly URL',
          'sql' => 'cl.link_rewrite',
          'require' => array('cl'),
          'update' => array(
            'cl' => 'link_rewrite'
          )
        ),
        'metaTitle' => array(
          'type' => 'string',
          'description' => 'meta title',
          'sql' => 'cl.meta_title',
          'require' => array('cl'),
          'update' => array(
            'cl' => 'meta_title'
          )
        ),
        'metaDescription' => array(
          'type' => 'string',
          'description' => 'meta description',
          'sql' => 'cl.meta_description',
          'require' => array('cl'),
          'update' => array(
            'cl' => 'meta_description'
          )
        ),
        'metaKeywords' => array(
          'type' => 'array[string]',
          'description' => 'meta keywords',
          'sql' => 'REPLACE(cl.meta_keywords, ",", CHAR(1))',
          'require' => array('cl'),
          'update' => array()
        ),
        'level' => array(
          'type' => 'number',
          'description' => 'level',
          'sql' => 'c.level_depth',
          'require' => array('c'),
          'update' => false
        ),
        'position' => array(
          'type' => 'number',
          'description' => 'position',
          'sql' => 'c.position',
          'require' => array('c'),
          'update' => array(
            'c' => 'position'
          )
        ),
        'created' => array(
          'type' => 'datetime',
          'description' => 'date created',
          'sql' => 'c.date_add',
          'require' => array('c'),
          'update' => array(
            'c' => 'date_add'
          )
        ),
        'updated' => array(
          'type' => 'datetime',
          'description' => 'date updated',
          'sql' => 'c.date_upd',
          'require' => array('c'),
          'update' => array(
            'c' => 'date_upd'
          )
        )
      ),
      'expressions' => array(
        'description' => array(
          'type' => 'string',
          'expression' => 'clean(<field:description>)',
          'description' => 'description'
        ),
        'url' => array(
          'type' => 'string',
          'expression' => 'cmsCategoryUrl(<field:id>)',
          'description' => 'url'
        )
      ),
      'links' => array(
        'cmsPages' => array(
          'description' => 'CMS Pages',
          'collection' => 'cms',
          'type' => 'HAS_MANY',
          'sourceFields' => array('id'),
          'targetFields' => array('cmsCategoryId')
        )
      )
    ));
  }
}
