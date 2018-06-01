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

class CMS {
  public function register($factory) {
    $factory->registerCollection(array(
      'id' => 'cms',
      'singular' => 'cms',
      'description' => 'CMS Pages',
      'key' => array('id'),
      'display' => 'metaTitle',
      'category' => 'cms',
      'psTab' => 'AdminCmsContent',
      'psController' => 'AdminCmsContent',
      'psClass' => 'CMS',
      'parameters' => array('shop', 'language'),
      'restrictions' => array(
        'shop' => array(
          'shop' => '<field:shopId>'
        )
      ),
      'tables' => array(
        'cs' => array(
          'table' => 'cms_shop',
          'conditions' => array(
            '<bind-param:shop:cs.id_shop>'
          )
        ),
        'c' => array(
          'table' => 'cms',
          'require' => array('cs'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              'cs.id_cms = c.id_cms'
            )
          )
        ),
        'cl' => array(
          'table' => 'cms_lang',
          'require' => array('cs'),
          'join' => array(
            'type' => 'INNER',
            'conditions' => array(
              "cl.id_cms = cs.id_cms",
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
          'sql' => 'cs.id_cms',
          'require' => array('cs'),
          'require' => array('cs'),
          'selectRecord' => 'cms',
          'update' => false
        ),
        'cmsCategoryId' => array(
          'type' => 'number',
          'description' => 'CMS category id',
          'sql' => 'c.id_cms_category',
          'selectRecord' => 'cmsCategories',
          'require' => array('c'),
          'update' => false
        ),
        'shopId' => array(
          'type' => 'number',
          'description' => 'shop id',
          'sql' => 'cs.id_shop',
          'selectRecord' => 'shops',
          'require' => array('cs'),
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
        'indexation' => array(
          'type' =>  'boolean',
          'description' => 'indexation',
          'sql' => 'c.indexation',
          'require' => array('c'),
          'update' => array(
            'c' => 'indexation'
          )
        ),
        'content' => array(
          'type' => 'string',
          'description' => 'content',
          'sql' => 'cl.content',
          'require' => array('cl'),
          'update' => array(
            'cl' => 'content'
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
        'position' => array(
          'type' => 'number',
          'description' => 'position',
          'sql' => 'c.position',
          'require' => array('c'),
          'update' => array(
            'c' => 'position'
          )
        )
      ),
      'expressions' => array(
        'content' => array(
          'type' => 'string',
          'expression' => 'clean(<field:content>)',
          'description' => 'content'
        ),
        'url' => array(
          'type' => 'string',
          'expression' => 'cmsUrl(<field:id>)',
          'description' => 'url'
        )
      ),
      'links' => array(
        'cmsCategory' => array(
          'description' => 'CMS Category',
          'collection' => 'cmsCategories',
          'type' => 'BELONGS_TO',
          'sourceFields' => array('cmsCategoryId'),
          'targetFields' => array('id')
        )
      )
    ));
  }
}
