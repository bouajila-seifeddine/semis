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

class PrestashopParameterProvider extends ParameterProvider {
  private $psContext;

  public function __construct($psContext, $factory) {
    parent::__construct($factory);
    $this->psContext = $psContext;
  }

  public function getParameter($id, $definition) {
    if ($id === 'shop') {
      $shops = $this->getFactory()->getShops();
      $shopId = $this->psContext->shop->id;
      if (isset($shops[$shopId])) {
        return $shopId;
      }
      $keys = array_keys($shops);
      return $keys ? $keys[0] : -1;
    }
    return parent::getParameter($id, $definition);
  }

  public function deriveParameter($id, $definition, $dependencies) {
    if ($id === 'defaultCurrency') {
      $shopId = $dependencies[0];
      $shop = new \Shop($shopId);
      return \Configuration::get('PS_CURRENCY_DEFAULT', null, $shop->id_shop_group, $shopId);
    }
    if ($id === 'stockManagement') {
      $shopId = $dependencies[0];
      $shop = new \Shop($shopId);
      return (bool)\Configuration::get('PS_STOCK_MANAGEMENT', null, $shop->id_shop_group, $shopId);
    }
    if ($id === 'allowOrderOutOfStock') {
      $shopId = $dependencies[0];
      $shop = new \Shop($shopId);
      return (bool)\Configuration::get('PS_ORDER_OUT_OF_STOCK', null, $shop->id_shop_group, $shopId);
    }
    if ($id === 'shopGroup') {
      $shopId = $dependencies[0];
      $shop = new \Shop($shopId);
      return $shop->id_shop_group;
    }
    if ($id === 'shopUrl') {
      $shopId = $dependencies[0];
      $shop = new \Shop($shopId);
      return $shop->getBaseURL(true);
    }
    if ($id === 'shareStock') {
      $shopGroupId = $dependencies[0];
      $shopGroup = new \ShopGroup($shopGroupId);
      $share = $shopGroup->share_stock;
      return $share;
    }
    if ($id === 'shareCustomers') {
      $shopGroupId = $dependencies[0];
      $shopGroup = new \ShopGroup($shopGroupId);
      $share = $shopGroup->share_customer;
      return $share;
    }
    if ($id === 'shareOrders') {
      $shopGroupId = $dependencies[0];
      $shopGroup = new \ShopGroup($shopGroupId);
      $share = $shopGroup->share_order;
      return $share;
    }
    return parent::deriveParameter($id, $definition, $dependencies);
  }
}
