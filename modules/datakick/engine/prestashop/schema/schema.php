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
namespace Datakick;

require_once(dirname(__FILE__) . '/schema-utils.php');
require_once(dirname(__FILE__) . '/shop.php');
require_once(dirname(__FILE__) . '/address.php');
require_once(dirname(__FILE__) . '/attribute-value.php');
require_once(dirname(__FILE__) . '/attribute.php');
require_once(dirname(__FILE__) . '/carrier.php');
require_once(dirname(__FILE__) . '/cart.php');
require_once(dirname(__FILE__) . '/category.php');
require_once(dirname(__FILE__) . '/combination.php');
require_once(dirname(__FILE__) . '/country.php');
require_once(dirname(__FILE__) . '/currency.php');
require_once(dirname(__FILE__) . '/customer.php');
require_once(dirname(__FILE__) . '/feature-value.php');
require_once(dirname(__FILE__) . '/feature.php');
require_once(dirname(__FILE__) . '/group.php');
require_once(dirname(__FILE__) . '/image.php');
require_once(dirname(__FILE__) . '/manufacturer.php');
require_once(dirname(__FILE__) . '/order.php');
require_once(dirname(__FILE__) . '/ordered_product.php');
require_once(dirname(__FILE__) . '/page-view.php');
require_once(dirname(__FILE__) . '/product.php');
require_once(dirname(__FILE__) . '/session.php');
require_once(dirname(__FILE__) . '/state.php');
require_once(dirname(__FILE__) . '/supplier.php');
require_once(dirname(__FILE__) . '/tag.php');
require_once(dirname(__FILE__) . '/visitor.php');
require_once(dirname(__FILE__) . '/zone.php');
require_once(dirname(__FILE__) . '/roles.php');
require_once(dirname(__FILE__) . '/employees.php');
require_once(dirname(__FILE__) . '/languages.php');
require_once(dirname(__FILE__) . '/warehouse.php');
require_once(dirname(__FILE__) . '/stock.php');
require_once(dirname(__FILE__) . '/stock-movement.php');
require_once(dirname(__FILE__) . '/supply-order.php');
require_once(dirname(__FILE__) . '/supply-order-detail.php');
require_once(dirname(__FILE__) . '/tax-rules.php');
require_once(dirname(__FILE__) . '/cms-category.php');
require_once(dirname(__FILE__) . '/cms.php');

class PrestashopSchemaLoader extends SchemaLoader {

  public function __construct($dictionary, Factory $factory, $allowExtendSchema) {
    parent::__construct($dictionary, $factory);
    $this->allowExtendSchema = $allowExtendSchema;
  }

  public function load() {
    $this->loadSchema(new Schema\Prestashop\Shops());
    $this->loadSchema(new Schema\Prestashop\Languages());
    $this->loadSchema(new Schema\Prestashop\Roles());
    $this->loadSchema(new Schema\Prestashop\Employees());
    $this->loadSchema(new Schema\Prestashop\Address());
    $this->loadSchema(new Schema\Prestashop\AttributeValue());
    $this->loadSchema(new Schema\Prestashop\Attribute());
    $this->loadSchema(new Schema\Prestashop\Carrier());
    $this->loadSchema(new Schema\Prestashop\Cart());
    $this->loadSchema(new Schema\Prestashop\Category());
    $this->loadSchema(new Schema\Prestashop\Combination());
    $this->loadSchema(new Schema\Prestashop\Country());
    $this->loadSchema(new Schema\Prestashop\Currency());
    $this->loadSchema(new Schema\Prestashop\Customer());
    $this->loadSchema(new Schema\Prestashop\FeatureValue());
    $this->loadSchema(new Schema\Prestashop\Feature());
    $this->loadSchema(new Schema\Prestashop\Group());
    $this->loadSchema(new Schema\Prestashop\Image());
    $this->loadSchema(new Schema\Prestashop\Manufacturer());
    $this->loadSchema(new Schema\Prestashop\Order());
    $this->loadSchema(new Schema\Prestashop\OrderedProduct());
    $this->loadSchema(new Schema\Prestashop\PageView());
    $this->loadSchema(new Schema\Prestashop\Product());
    $this->loadSchema(new Schema\Prestashop\Session());
    $this->loadSchema(new Schema\Prestashop\State());
    $this->loadSchema(new Schema\Prestashop\Supplier());
    $this->loadSchema(new Schema\Prestashop\Tag());
    $this->loadSchema(new Schema\Prestashop\Visitor());
    $this->loadSchema(new Schema\Prestashop\Zone());
    $this->loadSchema(new Schema\Prestashop\Warehouse());
    $this->loadSchema(new Schema\Prestashop\Stock());
    $this->loadSchema(new Schema\Prestashop\StockMovement());
    $this->loadSchema(new Schema\Prestashop\SupplyOrder());
    $this->loadSchema(new Schema\Prestashop\SupplyOrderDetail());
    $this->loadSchema(new Schema\Prestashop\TaxRules());
    $this->loadSchema(new Schema\Prestashop\CMSCategory());
    $this->loadSchema(new Schema\Prestashop\CMS());

    if ($this->allowExtendSchema) {
      $modules = \Hook::exec('datakickExtend', array('version' => $this->getFactory()->getVersion()), null, true, false);
      if ($modules) {
        foreach ($modules as $key=>$def) {
          if (is_array($def)) {
            foreach ($def as $collection=>$col) {
              if (! isset($col['id'])) {
                $col['id'] = $collection;
              }
              $col['psModule'] = $key;
              $this->registerCollection($col);
            }
          }
        }
      }
    }
  }
}
