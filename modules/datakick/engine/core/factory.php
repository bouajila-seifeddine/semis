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
require_once(dirname(__FILE__).'/polyfill.php');
require_once(dirname(__FILE__).'/classes/classes.php');
require_once(dirname(__FILE__).'/expressions.php');
require_once(dirname(__FILE__).'/tasks.php');
require_once(dirname(__FILE__).'/services.php');
require_once(dirname(__FILE__).'/context.php');
require_once(dirname(__FILE__).'/places.php');
require_once(dirname(__FILE__).'/registry.php');
require_once(dirname(__FILE__).'/db/db.php');
require_once(dirname(__FILE__).'/parameter-provider.php');
require_once(dirname(__FILE__).'/connection.php');
require_once(dirname(__FILE__).'/cipher.php');
require_once(dirname(__FILE__).'/scheduler.php');
require_once(dirname(__FILE__).'/customization.php');
require_once(dirname(__FILE__).'/sql/migration-manager.php');
require_once(dirname(__FILE__).'/sql/migration-manager.php');
require_once(dirname(__FILE__).'/config.php');
require_once(dirname(__FILE__).'/utils.php');
require_once(dirname(__FILE__).'/schema-loader.php');
require_once(dirname(__FILE__).'/schema/schema.php');
require_once(dirname(__FILE__).'/permissions/permissions.php');
require_once(dirname(__FILE__).'/restrictions/restrictions.php');
require_once(dirname(__FILE__).'/user.php');
require_once(dirname(__FILE__).'/email-service.php');
require_once(dirname(__FILE__).'/email.php');
require_once(dirname(__FILE__).'/dictionary/dictionary.php');
require_once(dirname(__FILE__).'/dictionary/collection.php');
require_once(dirname(__FILE__).'/dictionary/field.php');
require_once(dirname(__FILE__).'/dictionary/join-field.php');
require_once(dirname(__FILE__).'/dictionary/link.php');

abstract class Factory {
  private $dictionary;
  private $tasks;
  private $user;
  private $options;
  private $enums;
  private $parameters;
  private $restrictionTypes;
  private $emailService;

  private $shutdown;
  private $expressions;
  private $services;
  private $scheduler;
  private $customization;
  private $debugMode = false;
  private $siteInfo;
  private $connection;
  private $tempTables;
  private $places;
  private $migrationManager;
  private $assetManager;

  protected function __construct(User $user) {
    $this->setUser($user);
    Types::setFormatCurrency($this->getCurrencyFormatUtils());
  }

  protected function initialize() {
    $config = $this->getPersistentConfig();
    if (! $config->get('installId')) {
      $n = time();
      $config->set('installId', UUID::v4());
      $config->set('installDate', $n);
      $config->set('trialEnds', $n+1209600);
    }

    // potencially perform auto migrate
    $dbMigratedTo = $config->get('dbVersion');
    if ($dbMigratedTo) {
      $manager = $this->getMigrationManager();
      $isMigrated = $manager->isLatest($dbMigratedTo);
      if (! $isMigrated) {
        $version = $this->getVersion();
        $last = $config->get('autoMigrate');
        if ($last != $version) {
          $config->set('autoMigrate', $version);
          $config->set('dbVersion', $manager->migrateFrom($dbMigratedTo));
        }
      }
    }

    // perform module update migration
    $version = $config->get('version');
    if ($version != $this->getVersion()) {
      $config->set('version', $this->getVersion());
      $this->moduleUpdate($version);
    }
  }

  public function debugMode() {
    return $this->debugMode;
  }

  public function setDebugMode($mode) {
    $this->debugMode = $mode;
  }

  public function getServices() {
    if (! $this->services) {
      $this->services = new Services($this);
    }
    return $this->services;
  }

  public function getCustomization() {
    if (! $this->customization) {
      $this->customization = new Customization($this);
    }
    return $this->customization;
  }

  public function getPlaces() {
    if (! $this->places) {
      $this->places = new Places($this);
    }
    return $this->places;
  }

  public function getDictionary() {
    if (! $this->dictionary) {
      $user = $this->getUser();
      $this->dictionary = new Dictionary($this);
    }
    return $this->dictionary;
  }

  public function getExpressions() {
    if (! $this->expressions) {
      $this->expressions = new Expressions($this);
    }
    return $this->expressions;
  }

  public function getRestrictionTypes() {
    if (! $this->restrictionTypes) {
      $this->restrictionTypes = new Registry('Restriction types');
      $this->restrictionTypes->register('user', new UserRestrictionType($this->getUser()));
      $this->includePlatformRestrictionTypes($this->restrictionTypes);
    }
    return $this->restrictionTypes;
  }

  public function getTempTables() {
    if (! $this->tempTables) {
      $this->tempTables = new Registry("Temporary Tables");
      $this->registerTempTables($this->tempTables);
    }
    return $this->tempTables;
  }

  public function getQuery() {
    return new Query($this->getConnection(), $this->getDictionary());
  }

  public function getModification(Context $context) {
    return new Modification($this, $context);
  }

  public function getConnection() {
    if (! $this->connection) {
      $this->connection = $this->createConnection();
    }
    return $this->connection;
  }

  public function getRecord($type, $systemContext=false, $context=null) {
    if (is_null($context)) {
      $context = $this->getContext('app', -1, $systemContext);
    }
    return new Record($type, $context, $this);
  }

  public function getTasks() {
    if (! $this->tasks) {
      $this->tasks = new Tasks($this);
    }
    return $this->tasks;
  }

  public function getContext($source='app', $sourceId=-1, $systemContext=false) {
    $parameters = $systemContext ? $this->getSystemParameters() : $this->getParameters();
    $context = new Context($this, $this->getParameterProvider(), $parameters);
    $context->setParameter('executionSource', $source);
    $context->setParameter('executionSourceId', $sourceId);
    return $context;
  }

  public function getScheduler() {
    if (! $this->scheduler) {
      $this->scheduler = new Scheduler($this);
    }
    return $this->scheduler;
  }

  public function getConfiguration() {
    $dictionary = $this->getDictionary();
    $expressions = $this->getExpressions();
    return array(
      'user' => $this->getUserInfo(),
      'licenseKey' => $this->getLicenseKey(),
      'site' => $this->getSiteInfo(),
      'options' => $this->getOptions(),
      'version' => $this->getVersion(),
      'debugMode' => $this->debugMode(),
      'dictionary' => $dictionary->toJS(),
      'functions' => $expressions->getFunctions(),
      'parameters' => $this->getParameters(),
      'tasks' => $this->getTasks()->getNames(),
      'enums' => $this->getEnums(),
    );
  }


  public function getApiServer() {
    return 'https://api.getdatakick.com';
  }

  public function fetch($url, $method='GET', $params=null) {
    return new Fetch($this, $url, $method, $params);
  }

  public function callApi($endpoint, $params) {
    $url = $this->getApiServer().'/'.$endpoint;
    $ret = $this->fetch($url, 'POST', $params)
      ->acceptsJSON()
      ->execute();
    if ($ret) {
      try {
        $parsed = json_decode($ret, true);
        if (isset($parsed['error']) && $parsed['error']) {
          return false;
        }
        if (isset($parsed['data'])) {
          return $parsed['data'];
        }
      } catch (\Exception $ignored) {}
    }
    return false;
  }

  public function getId() {
    $info = $this->getSiteInfo();
    return $info['id'];
  }

  public function getDimensions() {
    $si = $this->getSiteInfo();
    return array(
      'id' => $si['id'],
      'name' => $si['name'],
      'domain' => $si['domain'],
      'licenseType' => $si['licenseType'],
      'version' => $si['version'],
      'platform' => $si['platform'],
      'platformVersion' => $si['platformVersion'],
      'phpVersion' => $si['phpVersion'],
      'dbVersion' => $si['dbVersion'],
      'installed' => date('c', $si['installed']),
      'fetch' => $si['fetch']
    );
  }

  public function activate() {
    $ret = $this->callApi('install', $this->getDimensions());
    if ($ret && is_array($ret)) {
      $config = $this->getPersistentConfig();
      foreach ($ret as $id => $key) {
        $config->set($id, $key);
      }
    }
    return true;
  }

  public function install() {
    $config = $this->getPersistentConfig();
    $manager = $this->getMigrationManager();
    $dbMigratedTo = $config->get('dbVersion');
    if ($dbMigratedTo) {
      if ($manager->isLatest($dbMigratedTo)) {
        return $this->activate();
      } else {
        $config->set('dbVersion', $manager->migrateFrom($dbMigratedTo));
        if ($manager->isLatest($dbMigratedTo)) {
          return $this->activate();
        }
      }
    } else {
      if ($manager->install()) {
        $config->set('dbVersion', $manager->getLatest());
        $ts = time();
        $rand = mt_rand();
        $domain = $this->getDomain();
        $email = $this->getEmail();
        $config->set('webcronToken', sha1("webcron:$ts:$email:2.1.3:$domain:$rand"));
        return $this->activate();
      }
    }
    return false;
  }

  public function deactivate() {
    $this->callApi('uninstall', $this->getDimensions());
    return true;
  }

  public function uninstall() {
    $config = $this->getPersistentConfig();
    $ver = $config->get('dbVersion');
    if ($ver) {
      $this->deactivate();
      $this->getCustomization()->removeAllCustomization();
      $manager = $this->getMigrationManager();
      if ($manager->uninstall($ver)) {
        $config->remove('dbVersion');
      }
    }
    return true;
  }

  public function getMigrationManager() {
    if (! $this->migrationManager) {
      $this->migrationManager = new MigrationManager($this);
    }
    return $this->migrationManager;
  }

  public function getServiceTable($table) {
    $tables = array(
      'executions' => 'datakick_execution',
      'execution-parameters' => 'datakick_execution_parameter',
      'xml-templates' => 'datakick_xml_template',
      'mass-updates' => 'datakick_mass_update',
      'lists' => 'datakick_list',
      'task-type' => 'datakick_task_type',
      'endpoint' => 'datakick_endpoint',
      'endpoint-parameter' => 'datakick_endpoint_parameter',
      'cron-type' => 'datakick_cron_type',
      'schedule' => 'datakick_schedule',
      'schedule-parameter' => 'datakick_schedule_parameter',
      'place' => 'datakick_place',
      'place-config' => 'datakick_place_config',
      'custom-table' => 'datakick_custom_table',
      'custom-field' => 'datakick_custom_field',
      'task' => 'datakick_task',
      'task-data' => 'datakick_task_data',
      'options' => 'datakick_options',
      'user-permissions' => 'datakick_user_permission',
      'user-restrictions' => 'datakick_user_restriction',
      'role-permissions' => 'datakick_role_permission',
      'role-restrictions' => 'datakick_role_restriction',
      'import-datasource' => 'datakick_import_datasource',
      'import-definition' => 'datakick_import_definition',
      'assets' => 'datakick_assets'
    );
    if (! isset($tables[$table]))
      throw new \Exception("Service table not found: $table");
    return $this->prefixTable($tables[$table]);
  }

  public function getCustomTable($table) {
    return $this->prefixTable("datakick_cust_" . $table);
  }

  public function substituteUser($userId) {
    $this->setUser($this->loadUser($userId));
  }

  private function setUser($user) {
    $this->user = $user;
    $this->restrictionTypes = null;
    $this->options = null;
    $this->dictionary = null;
    $this->emailService = null;
    $this->tasks = null;
    $this->enums = null;
    $this->parameters = null;
    $this->user->getPermissions()->setFactory($this);
    $this->user->getRestrictions()->setFactory($this);
  }

  public final function getParameters() {
    if (is_null($this->parameters)) {
      $this->parameters = array_merge($this->getSystemParameters(), $this->getPlatformParameters());
    }
    return $this->parameters;
  }

  public final function getSystemParameters() {
    return array(
      'executionSource' => array(
        'description' => 'Execution Source',
        'type' => 'string',
        'provided' => true,
        'default' => 'app',
        'values' => array(
          'app' => 'Application',
          'endpoint' => 'Endpoint',
          'schedule' => 'Scheduled task',
          'adhoc' => 'Ad-hoc execution'
        )
      ),
      'executionSourceId' => array(
        'description' => 'Execution Source ID',
        'type' => 'number',
        'provided' => true
      ),
      'executionId' => array(
        'description' => 'Execution ID',
        'type' => 'number',
        'derived' => array(
          'executionSource', 'executionSourceId'
        )
      ),
      'timestamp' => array(
        'description' => 'Execution Timestamp',
        'type' => 'datetime',
        'provided' => true,
      )
    );
  }

  public function getParameterProvider() {
    return new ParameterProvider($this);
  }

  public function getSiteInfo() {
    if (! $this->siteInfo) {
      $config = $this->getPersistentConfig();
      $dbMigratedTo = $config->get('dbVersion');
      $licenseKey = $this->getLicenseKey();
      return array(
        'id' => $config->get('installId'),
        'licenseType' => is_null($licenseKey) ? "trial" : "full",
        'os' => PHP_OS,
        'directorySeparator' => DIRECTORY_SEPARATOR,
        'phpVersion' => self::getPhpVersion(),
        'dbVersion' => $this->getConnection()->getVersion(),
        'version' => $this->getVersion(),
        'migrated' => $dbMigratedTo,
        'isMigrated' => $this->getMigrationManager()->isLatest($dbMigratedTo),
        'platform' => $this->getPlatform(),
        'platformVersion' => $this->getPlatformVersion(),
        'rootDir' => $this->getRootDir(),
        'moduleDir' => $this->getModuleDir(),
        'installed' => (int)$config->get('installDate'),
        'trialEnds' => (int)$config->get('trialEnds'),
        'name' => $this->getSiteName(),
        'domain' => $this->getDomain(),
        'email' => $this->getEmail(),
        'url' => $this->getBaseURL(),
        'crons' => $this->getCrons(),
        'fetch' => Fetch::detectMode()
      );
    }
    return $this->siteInfo;
  }

  private static function getPhpVersion() {
    $version = null;
    if (defined('PHP_VERSION')) {
      $version = PHP_VERSION;
    } else {
      $version  = phpversion('');
    }
    if (strpos($version, '-') !== false) {
      $version  = substr($version, 0, strpos($version, '-'));
    }
    return $version;
  }

  public function getLicenseKey() {
    return $this->getPersistentConfig()->get('licenseKey');
  }

  public function getUser() {
    return $this->user;
  }

  public function registerTempTables($registry) {
  }

  public abstract function getCipher();

  private function getDir($key) {
    $path = $this->getPersistentConfig()->get('directory' . ucfirst($key));
    if (! $path) {
      $datakickData = $this->getRootDir() . 'datakick_data';
      $dir = new Directory($datakickData);
      $dir->ensure(true, true, true);
      $path =  $datakickData . DIRECTORY_SEPARATOR . Utils::decamelize($key);
    }
    $dir = new Directory($path);
    $dir->ensure(true, true, true);
    return $path;
  }

  public function getDownloadDirectory() {
    return $this->getDir('fetched');
  }

  public function getAssetsDirectory() {
    return $this->getDir('assets');
  }

  public function getUploadDirectory() {
    return $this->getDir('uploaded');
  }

  public function getImportStagingDirectory() {
    return $this->getDir('importStaging');
  }

  public function getTempDirectory() {
    return $this->getDir('temp');
  }

  public function includePlatformTasks($tasks) {
  }

  public function getOptions() {
    if (is_null($this->options)) {
      $this->options = array();
      $conn = $this->getConnection();
      $table = $this->getServiceTable('options');
      $userId = (int)$this->getUser()->getId();
      $sql = "SELECT * FROM $table WHERE user_id IN (-1, $userId) ORDER BY user_id, name";
      $res = $conn->query($sql);
      while ($row = $res->fetch()) {
        $name = $row['name'];
        $value = $row['value'];
        $type = $row['type'];
        try {
          $this->options[$name] = Types::convertValue($type, $value);
        } catch (\Exception $e) {
          // ignored
        }
      }
    }
    return $this->options;
  }

  public function getUserInfo() {
    return array(
      'id' => $this->user->getId(),
      'name' => $this->user->getName(),
      'email' => $this->user->getEmail(),
      'language' => $this->user->getLanguage(),
      'isAdmin' => $this->user->getPermissions()->isAdmin(),
      'role' => $this->user->getPermissions()->getRoleId()
    );
  }

  public final function getEnums() {
    if (is_null($this->enums)) {
      $this->enums = $this->getPlatformEnums();
    }
    return $this->enums;
  }

  public function includePlatformRestrictionTypes($registry) {
  }

  public function getPlatformEnums() {
    return array();
  }

  public function getPlatformParameters() {
    return array();
  }

  public function getEmailService() {
    if (is_null($this->emailService)) {
      $this->emailService = $this->getPlatformEmailService();
    }
    return $this->emailService;
  }

  /**
   * create new empty email
   */
  public function createEmail($system=true) {
    if ($system) {
      $site = $this->getSiteInfo();
      return new Email($this->getEmailService(), $site['email'], $site['name']);
    } else {
      $user = $this->getUser();
      return new Email($this->getEmailService(), $user->getEmail(), $user->getName());
    }
  }

  public function moduleUpdate($fromVersion) {
    // noop
  }

  public function getPlatformCollectionFields() {
    return array();
  }

  private function getDomain() {
    $possibleHostSources = array('HTTP_X_FORWARDED_HOST', 'HTTP_HOST', 'SERVER_NAME', 'SERVER_ADDR');
    $sourceTransformations = array(
      'HTTP_X_FORWARDED_HOST' => function($value) {
        $elements = explode(',', $value);
        return trim(end($elements));
      }
    );
    $host = '';
    foreach ($possibleHostSources as $source) {
      if (!empty($host)) {
        break;
      }
      if (empty($_SERVER[$source])) {
        continue;
      }
      $host = $_SERVER[$source];
      if (array_key_exists($source, $sourceTransformations)) {
        $host = $sourceTransformations[$source]($host);
      }
    }
    $host = preg_replace('/:\d+$/', '', $host);
    return trim($host);
  }

  public function trialEnded() {
    $licenseKey = $this->getLicenseKey();
    if (is_null($licenseKey)) {
      $config = $this->getPersistentConfig();
      $te = $config->get('trialEnds');
      $now = time();
      if ($te) {
        return $now > $te;
      }
      $id = $config->get('installDate');
      if ($id) {
        $te = $id + 1209600;
        $config->set('trialEnds', $te);
        return $now > $te;
      }
      return true;
    } else {
      return false;
    }
  }

  public function getAssetManager() {
    if (! $this->assetManager) {
      $this->assetManager = new AssetManager($this);
    }
    return $this->assetManager;
  }

  public function getShutdownHandler() {
    if (! $this->shutdown) {
      $this->shutdown = new ShutdownHandler();
    }
    return $this->shutdown;
  }

  protected abstract function loadUser($userId);
  public abstract function getPermissions($userId, $roleId);
  public abstract function getPersistentConfig();
  public abstract function createConnection();
  public abstract function prefixTable($table);
  public abstract function getCurrencyFormatUtils();
  public abstract function getVersion();
  public abstract function clearCache();
  public abstract function getRootDir();
  public abstract function getModuleDir();
  public abstract function getBaseURL();
  public abstract function getResumeUrl($executionId);
  public abstract function getCrons();
  public abstract function getPlatform();
  public abstract function getPlatformVersion();
  public abstract function getSiteName();
  public abstract function getEmail();
  public abstract function getPlatformSchemaLoader($dictionary);
  public abstract function getPlatformEmailService();
}
