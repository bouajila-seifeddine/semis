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
require_once(DATAKICK_CORE.'factory.php');
require_once(DATAKICK_PRESTASHOP.'functions/product-price.php');
require_once(DATAKICK_PRESTASHOP.'functions/product-url.php');
require_once(DATAKICK_PRESTASHOP.'functions/category-url.php');
require_once(DATAKICK_PRESTASHOP.'functions/cms-category-url.php');
require_once(DATAKICK_PRESTASHOP.'functions/cms-url.php');
require_once(DATAKICK_PRESTASHOP.'functions/manufacturer-url.php');
require_once(DATAKICK_PRESTASHOP.'functions/supplier-url.php');
require_once(DATAKICK_PRESTASHOP.'functions/product-image.php');
require_once(DATAKICK_PRESTASHOP.'functions/product-images.php');
require_once(DATAKICK_PRESTASHOP.'functions/category-image.php');
require_once(DATAKICK_PRESTASHOP.'functions/product-attribute-values.php');
require_once(DATAKICK_PRESTASHOP.'functions/combination-attribute-value.php');
require_once(DATAKICK_PRESTASHOP.'functions/image-path.php');
require_once(DATAKICK_PRESTASHOP.'functions/ordered-products-count.php');
require_once(DATAKICK_PRESTASHOP.'functions/in-category.php');
require_once(DATAKICK_PRESTASHOP.'functions/supplier-reference.php');
require_once(DATAKICK_PRESTASHOP.'functions/convert-currency.php');
require_once(DATAKICK_PRESTASHOP.'functions/product-feature-value.php');
require_once(DATAKICK_PRESTASHOP.'connection.php');
require_once(DATAKICK_PRESTASHOP.'parameter-provider.php');
require_once(DATAKICK_PRESTASHOP.'currency-format.php');
require_once(DATAKICK_PRESTASHOP.'cipher.php');
require_once(DATAKICK_PRESTASHOP.'persistent-config.php');
require_once(DATAKICK_PRESTASHOP.'image-handler.php');
require_once(DATAKICK_PRESTASHOP.'schema/schema.php');
require_once(DATAKICK_PRESTASHOP.'tasks/factory/backup-db.php');
require_once(DATAKICK_PRESTASHOP.'tasks/factory/refresh-currency-rates.php');
require_once(DATAKICK_PRESTASHOP.'tasks/factory/search-index.php');
require_once(DATAKICK_PRESTASHOP.'tasks/factory/layered-block.php');
require_once(DATAKICK_PRESTASHOP.'permissions/permissions.php');
require_once(DATAKICK_PRESTASHOP.'restrictions/shop-restriction-type.php');
require_once(DATAKICK_PRESTASHOP.'user.php');
require_once(DATAKICK_PRESTASHOP.'email-service.php');

class PrestashopFactory extends Factory {
  private static $factory;
  private static $postProcess;
  private static $allowExtendSchema = true;

  private $psContext;

  private $currencyFormat;
  private $cipher;
  private $config;

  public static function withContext(\Context $psContext, $user=null) {
    if (! self::$factory) {
      if (is_null($user))
        $user = new PrestashopUser($psContext->employee);
      self::$factory = new PrestashopFactory($psContext, $user);
    }
    return self::$factory;
  }

  public function __construct(\Context $psContext, User $user) {
    parent::__construct($user);
    $this->psContext = $psContext;
    parent::initialize();
  }

  public static function setAllowExtendSchema($allow) {
    self::$allowExtendSchema = $allow;
  }

  public function getVersion() {
    return '2.1.3';
  }

  public function getPlatformSchemaLoader($dictionary) {
    return new PrestashopSchemaLoader($dictionary, $this, self::$allowExtendSchema);
  }

  public function getExpressions() {
    $expr = parent::getExpressions();
    $expr->register(new ProductPriceFunction());
    $expr->register(new ProductUrlFunction());
    $expr->register(new ProductImageFunction());
    $expr->register(new ProductImagesFunction());
    $expr->register(new ImagePathFunction());
    $expr->register(new CategoryImageFunction());
    $expr->register(new CategoryUrlFunction());
    $expr->register(new CMSCategoryUrlFunction());
    $expr->register(new CMSUrlFunction());
    $expr->register(new ManufacturerUrlFunction());
    $expr->register(new SupplierUrlFunction());
    $expr->register(new InCategoryFunction($this));
    $expr->register(new ProductAttributeValuesFunction($this));
    $expr->register(new CombinationAttributeValueFunction($this));
    $expr->register(new OrderedProductsCount());
    $expr->register(new SupplierReferenceFunction($this));
    $expr->register(new ConvertCurrencyFunction());
    $expr->register(new ProductFeatureValueFunction($this));
    return $expr;
  }

  public function getPlatformEnums() {
    return array(
      'currencies' => $this->getCurrencies(),
      'languages' => $this->getLanguages(),
      'shops' => $this->getShops()
    );
  }

  public function getCurrencies() {
    $currencies = array();
    foreach(\Currency::getCurrencies(false) as $currency) {
      $currencies[$currency['id_currency']] = array(
        'name' => $currency['name'],
        'code' => $currency['iso_code'],
        'symbol' => $currency['sign'],
        'rate' => $currency['conversion_rate']
      );
    }
    return $currencies;
  }

  public function getLanguages() {
    $languages = array();
    foreach(\Language::getLanguages(true) as $lang) {
      $languages[$lang['id_lang']] = array(
        'name' => $lang['name'],
        'code' => $lang['iso_code'],
        'langCode' => $lang['language_code'],
        'dateFormat' => $lang['date_format_lite'],
        'datetimeFormat' => $lang['date_format_full']
      );
    }
    return $languages;
  }

  public function getDefaultValue($arr, $name) {
    $options = $this->getOptions();
    $key = Utils::toCamelCase("default_$name");
    if (isset($options[$key])) {
      $val = $options[$key];
      if (isset($arr[$val])) {
        return $val;
      }
    }
    $def = $this->psContext->{$name}->id;
    if (isset($arr[$def])) {
      return $def;
    }
    $keys = array_keys($arr);
    return $keys ? $keys[0] : null;
  }

  public function getShops() {
    $shops = array();
    $recType = $this->getRecord('shops', true);
    $shops = $recType->loadRecords(array(), array('id', 'name'));
    return array_column($shops, 'name', 'id');
  }

  public function getPlatformParameters() {
    $enums = $this->getEnums();
    $languages = array_map(function($arr) { return $arr['name']; }, $enums['languages']);
    $currencies = array_map(function($arr) { return $arr['name']; }, $enums['currencies']);
    $shops = $enums['shops'];
    $singleShop = count($shops) <= 1;
    $c = $this->psContext;
    return array(
      'language' => array(
        'description' => 'Language',
        'type' => 'number',
        'default' => $this->getDefaultValue($languages, 'language'),
        'useDefault' => true,
        'values' => $languages,
        'selectRecord' => 'languages'
      ),
      'currency' => array(
        'description' => 'Currency',
        'type' => 'number',
        'default' => $this->getDefaultValue($currencies, 'currency'),
        'useDefault' => true,
        'values' => $currencies,
        'selectRecord' => 'currencies'
      ),
      'shop' => array(
        'description' => 'Shop',
        'type' => 'number',
        'default' => $this->getDefaultValue($shops, 'shop'),
        'values' => $shops,
        'selectRecord' => 'shops',
        'useDefault' => true,
        'provided' => $singleShop,
        'hidden' => $singleShop
      ),
      'shopGroup' => array(
        'description' => 'Shop Group',
        'type' => 'number',
        'hidden' => true,
        'derived' => array(
          'shop'
        )
      ),
      'shopUrl' => array(
        'description' => 'Shop URL',
        'type' => 'string',
        'derived' => array(
          'shop'
        )
      ),
      'stockManagement' => array(
        'description' => 'Has Stock Management',
        'type' => 'boolean',
        'hidden' => true,
        'derived' => array(
          'shop'
        )
      ),
      'shareStock' => array(
        'description' => 'Share Stock',
        'type' => 'boolean',
        'hidden' => true,
        'derived' => array(
          'shopGroup'
        )
      ),
      'shareCustomers' => array(
        'description' => 'Share customers',
        'type' => 'boolean',
        'hidden' => true,
        'derived' => array(
          'shopGroup'
        )
      ),
      'shareOrders' => array(
        'description' => 'Share orders',
        'type' => 'boolean',
        'hidden' => true,
        'derived' => array(
          'shopGroup'
        )
      ),
      'allowOrderOutOfStock' => array(
        'description' => 'Allow ordering out of stock products',
        'type' => 'boolean',
        'hidden' => true,
        'derived' => array(
          'shop'
        )
      ),
      'defaultCurrency' => array(
        'description' => 'Default Currency',
        'type' => 'number',
        'derived' => array(
          'shop'
        )
      )
    );
  }

  public function createConnection() {
    return new PrestashopConnection($this);
  }

  public function prefixTable($table) {
    return _DB_PREFIX_ . $table;
  }


  public function getParameterProvider() {
    return new PrestashopParameterProvider($this->psContext, $this);
  }

  public function getCurrencyFormatUtils() {
    if (! $this->currencyFormat) {
      $enums = $this->getEnums();
      $this->currencyFormat = new PrestashopCurrencyFormat($enums['currencies']);
    }
    return $this->currencyFormat;
  }

  public function getCipher() {
    if (! $this->cipher) {
      $this->cipher = new PrestashopCipher();
    }
    return $this->cipher;
  }

  private function verifyCronjobActivation() {
    $conn = $this->getConnection();
    try {
      $sql = '
      SELECT c.active
      FROM '._DB_PREFIX_.'cronjobs c
      JOIN '._DB_PREFIX_.'module m
      ON (c.id_module = m.id_module)
      WHERE m.name = "datakick"';
      $res = $conn->query($sql)->fetch();
      if ($res) {
        return isset($res['active']) && !!$res['active'];
      } else {
        return false;
      }
    } catch (\Exeption $e) {
      return false;
    }
  }

  public function getCrons() {
    $moduleDir = $this->getModuleDir();
    $url = $this->getModuleUrl();
    $config = $this->getPersistentConfig();
    $cronjobsInstalled = \Module::isInstalled('cronjobs');
    $cronjobsActivated = false;
    if ($cronjobsInstalled) {
      $cronjobsActivated = $this->verifyCronjobActivation();
    }
    $conn = $this->getConnection();
    $sql = 'SELECT cron, active, UNIX_TIMESTAMP(last) `ts` FROM '.$this->getServiceTable('cron-type');
    $ret = array();
    $res = $conn->query($sql);
    while($row = $res->fetch()) {
      $type = $row['cron'];
      $last = $row['ts'];
      $info = array(
        'last' => $last ? (int)$last : null,
        'active' => (bool)$row['active']
      );
      if ($type == 'cron') {
        $info['path'] = $moduleDir.'cron.php';
      } else if ($type == 'webcron') {
        $info['url'] = $url . 'cron.php?token='.$config->get('webcronToken');
      } else if ($type == 'cronjobs') {
        $info['installed'] = $cronjobsInstalled;
        $info['activated'] = $cronjobsActivated;
      } else {
        throw new \Exception('Unknown cron type: '.$type);
      }
      $ret[$type] = $info;
    }
    return $ret;
  }

  public function getPersistentConfig() {
    if (! $this->config) {
      $this->config = new PrestashopConfiguration();
    }
    return $this->config;
  }

  public function clearCache() {
    try {
      \Tools::clearSmartyCache();
      \Tools::clearXMLCache();
      \Media::clearCache();
      \Tools::generateIndex();
    } catch (\Exception $e) {}
  }

  public function getRootDir() {
    return rtrim(_PS_ROOT_DIR_, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
  }

  public function getModuleDir() {
    return $this->getRootDir() . 'modules' . DIRECTORY_SEPARATOR . 'datakick' . DIRECTORY_SEPARATOR;
  }

  public function getBaseURL() {
    return $this->psContext->shop->getBaseURL();
  }

  public function getModuleUrl() {
    return $this->getBaseURL() . 'modules/datakick/';
  }

  public function getResumeUrl($executionId) {
    $config = $this->getPersistentConfig();
    $token = $config->get('webcronToken');
    return $this->getModuleUrl() . "resume.php?token=$token&execution-id=$executionId";
  }

  public function getPlatform() {
    return 'prestashop';
  }

  public function getPlatformVersion() {
    return _PS_VERSION_;
  }

  public function getSiteName() {
    return \Configuration::get('PS_SHOP_NAME');
  }

  public function getEmail() {
    return \Configuration::get('PS_SHOP_EMAIL');
  }

  public function getPermissions($userId, $roleId) {
    $perms = new PrestashopPermissions($userId, $roleId);
    $perms->setFactory($this);
    return $perms;
  }

  public function includePlatformTasks($tasks) {
    $tasks->register('ps-backup-db', new PrestashopBackupDbFactory());
    $tasks->register('ps-currency-rates', new PrestashopRefreshCurrencyRatesTaskFactory());
    $tasks->register('ps-search-index', new PrestashopSearchIndexTaskFactory());
    if (\Module::isInstalled('blocklayered')) {
      $tasks->register('ps-layered-nav-block', new PrestashopLayeredBlockTaskFactory());
    }
  }

  public function includePlatformRestrictionTypes($registry) {
    $registry->register('shop', new PrestashopShopRestrictionType($this->getUser()));
  }

  public function loadUser($userId) {
    return new PrestashopUser(new \Employee($userId));
  }

  public function getPlatformEmailService() {
    return new PrestashopEmailService($this->psContext->language->id);
  }

  public function getPlatformCollectionFields() {
    return array('psTab', 'psClass', 'psController', 'psModule', 'psDrilldownParams');
  }

  public function moduleUpdate($fromVersion) {
    $datakick = \Module::getInstanceByName('datakick');
    $datakick->verifyInstallation();
  }
}
