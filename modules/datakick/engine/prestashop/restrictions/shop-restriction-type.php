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

class PrestashopShopRestrictionType implements RestrictionType {
  private $user;
  private $shops;

  public function __construct($user) {
    $this->user = $user;
  }

  public function fields() {
    return array('shop');
  }

  public function getDefaultReadLevel() {
    return "employee";
  }

  public function getDefaultWriteLevel() {
    return "employee";
  }

  public function getIcon() {
    return "places";
  }

  public function getName() {
    return "Shop restriction";
  }

  public function getDescription() {
    return "Restrict access to records according to their shop association";
  }

  public function getLevels() {
    $ret = array(
      'employee' => "Shops associated with employee",
      'all' => "All shops",
      'none' => "No shops"
    );
    if (\Shop::isFeatureActive()) {
      foreach (\ShopGroup::getShopGroups() as $shop) {
        $id = $shop->id;
        $ret["group:$id"] = "All shops inside group: {$shop->name}";
      }
    }
    return $ret;
  }

  public function create($level) {
    if ($level === 'all')
      return AllowRestriction::instance();
    if ($level === 'employee') {
      $shops = $this->getEmployeeShops();
      if ($shops) {
        return new InRestriction('shop', $shops);
      }
    }
    if (preg_match('/^group:([0-9]+)$/', $level, $matches)) {
      $shops = $this->getShopGroups($matches[1]);
      if ($shops) {
        return new InRestriction('shop', $shops);
      }
    }
    return DenyRestriction::instance();
  }

  private function getEmployeeShops() {
    if (is_null($this->shops)) {
      if ($this->user instanceof PrestashopUser) {
        $this->shops = $this->user->getEmployee()->getAssociatedShops();
      } else {
        $this->shops = array();
      }
    }
    return $this->shops;
  }

  private function getShopGroups($groupId) {
    return array_column(\ShopGroup::getShopsFromGroup($groupId), 'id_shop');
  }
}
