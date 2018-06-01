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
class AdminDatakickFullController extends ModuleAdminController {
  public function __construct() {
    parent::__construct();
    $this->display = 'view';
    $this->bootstrap = true;
    $this->addCSS($this->getPath('views/css/back.css'));
  }

  public function display() {
    $this->show_page_header_toolbar = false;
    $this->display_footer = false;
    $link = $this->context->link->getAdminLink('AdminDatakickFull');
    $debugMode = (int)Tools::getValue('debug-mode');
    $this->context->smarty->assign('dmLink', $link);
    $this->context->smarty->assign('dmBase', $this->context->shop->getBaseURL());
    $this->context->smarty->assign('dmRewrite', (int)Configuration::get('PS_REWRITING_SETTINGS'));
    $this->context->smarty->assign('dmDebugMode', $debugMode ? 1 : 0);
    if (Tools::getValue('ifm')) {
      if (version_compare(PHP_VERSION, '5.4.0') < 0) {
        die("DataKick requires at least PHP version 5.4, you have ".+PHP_VERSION);
      }
      $factory = $this->getFactory($this->context);
      if ($debugMode) {
        $factory->setDebugMode(true);
      }
      $config = $factory->getConfiguration();
      $site = $config['site'];
      $ver = urlencode($config['version']);
      $platform = urlencode($site['platform']);
      $platformVer = urlencode($site['platformVersion']);
      $domain = urlencode($site['domain']);
      $hours = 4;
      $cacheControl = gmdate('Ymd').($hours * floor(gmdate('h') / $hours));
      $s = "https://s3-us-west-2.amazonaws.com/datakick-js/datakick-2_1_0.js?c=$cacheControl&v=$ver&d=$domain&p=$platform&pv=$platformVer";
      $this->context->smarty->assign('dmUrl', $s);
      $this->context->smarty->assign('dmConfig', $config);
      $this->context->smarty->assign('dmTranslations', $this->module->getAppTranslations());
      $this->layout = $this->getPath('views/templates/admin/iframe.tpl');
      $dict = $factory->getDictionary();
      $tokens = array();
      foreach ($dict->getCollections() as $col) {
        if ($col->hasPlatformField('psController')) {
          $controller = $col->getPlatformField('psController');
          $controller = $col->getPlatformField('psController');
          $params = $col->hasPlatformField('psDrilldownParams') ? $col->getPlatformField('psDrilldownParams') : array();
          $tokens[$col->getId()] = $this->getAdminLink($controller, $params);
        }
      }
      $this->context->smarty->assign('dmTokens', $tokens);
    }
    return parent::display();
  }

  private function getAdminLink($controller, $params) {
    $params['token'] = Tools::getAdminTokenLite($controller);
    $lang = $this->context->language->id;
    return str_replace('%3Ckey%3E', '<key>', Dispatcher::getInstance()->createUrl($controller, $lang, $params, false));
  }

  public function createTemplate($tpl_name)
  {
    if ($this->viewAccess() && $tpl_name === 'content.tpl') {
      $path = $this->getPath('views/templates/admin/content.tpl');
      return $this->context->smarty->createTemplate($path, $this->context->smarty);
    }
    return parent::createTemplate($tpl_name);
  }

  private function getPath($path) {
    return _PS_MODULE_DIR_ . $this->module->name . '/' . $path;
  }

  public function ajaxProcessService() {
    require_once(dirname(__FILE__).'/../../engine/engine.php');
    $requestHandler = new DataKick\RequestHandler($this->getFactory(Context::getContext()));
    $requestHandler->handleAjax();
  }

  private function getFactory(Context $context) {
    require_once(dirname(__FILE__).'/../../engine/engine.php');
    return Datakick\PrestashopFactory::withContext($context);
  }
}
