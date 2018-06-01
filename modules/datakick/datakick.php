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
class Datakick extends Module {
  protected $config_form = false;

  public function __construct() {
    $this->name = 'datakick';
    $this->tab = 'export';
    $this->version = '2.1.3';
    $this->author = 'Petr Hucik <petr@getdatakick.com>';
    $this->need_instance = 0;
    $this->bootstrap = true;

    parent::__construct();
    $this->displayName = $this->l('DataKick - Data Manager');
    $this->description = $this->l('XML and CSV export, inline editing');
    $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
  }

  public function install() {
    $factory = $this->getFactory();
    return parent::install() &&
      $factory->install() &&
      $this->verifyInstallation();
  }

  public function uninstall() {
    $factory = $this->getFactory();
    return $this->removeTab()
      && $factory->uninstall()
      && parent::uninstall();
  }

  public function verifyInstallation() {
    // register hooks
    $ret = (
      $this->registerHook('actionObjectAddAfter') &&
      $this->registerHook('actionObjectUpdateAfter') &&
      $this->registerHook('moduleRoutes') &&
      $this->registerHook('actionCronJob') &&
      $this->registerHook('actionEmailAddAfterContent') &&
      $this->registerHook('displayBackOfficeHeader') &&
      $this->registerFormModifierHooks()
    );

    if (version_compare(_PS_VERSION_, '1.7.0', '<') === true) {
      $ret = $ret && (
        $this->registerHook('displayAdminProductsExtra') &&
        $this->registerHook('actionProductUpdate')
      );
    };

    $ret = $ret && $this->verifyTab();

    return $ret;
  }

  private function registerFormModifierHooks() {
    foreach ($this->getFactory()->getDictionary()->getCollections() as $col) {
      if ($col->hasPlatformField('psController')) {
        if (! $this->registerHook('action' . $col->getPlatformField('psController') . 'FormModifier'))
          return false;
      }
    }
    return true;
  }

  public function getContent() {
    Tools::redirectAdmin($this->context->link->getAdminLink('AdminDatakickFull'));
  }

  public function hookModuleRoutes($params) {
    return [
      'datakick' => [
        'controller' => 'endpoint',
        'rule' => 'endpoint/{:endpoint}',
        'keywords' => [
          'endpoint' => ['regexp' => '[a-z][a-z0-9_\.-]*', 'param' => 'endpoint'],
        ],
        'params' => [
          'fc' => 'module',
          'module' => 'datakick',
          'controller' => 'endpoint'
        ]
      ]
    ];
  }

  private function getTabParent() {
    $improve = Tab::getIdFromClassName('IMPROVE');
    if ($improve !== false) {
      return $improve;
    }
    return 0;
  }

  private function installTab() {
    $tab = new Tab();
    $tab->active = 1;
    $tab->class_name = 'AdminDatakickFull';
    $tab->module = $this->name;
    $tab->id_parent = $this->getTabParent();
    $tab->name = array();
    foreach (Language::getLanguages(true) as $lang) {
      $tab->name[$lang['id_lang']] = 'DataKick - Data Manager';
    }
    return $tab->add();
  }

  private function verifyTab() {
    $tabId = Tab::getIdFromClassName('AdminDatakickFull');
    if ($tabId) {
      $tab = new Tab($tabId);
      $parent = $this->getTabParent();
      if ($tab->id_parent != $parent) {
        $tab->id_parent = $parent;
        $tab->save();
      }
      return true;
    } else {
      return $this->installTab();
    }
  }

  private function removeTab() {
    $tabId = Tab::getIdFromClassName('AdminDatakickFull');
    if ($tabId) {
      $tab = new Tab($tabId);
      return $tab->delete();
    }
    return true;
  }

  // cronjobs module integration
  public function hookActionCronJob($params) {
    try {
      $this->getFactory()->getScheduler()->cronEvent('cronjobs', true);
    } catch (Exception $e) {}
    // restore time limit back to zero
    @set_time_limit(0);
  }

  // email templates
  public function hookActionEmailAddAfterContent($param) {
    if (! (isset($param['template']) && isset($param['template_html']) && isset($param['template_txt'])))
      return;
    if ($param['template'] !== 'datakick')
      return;
    $email = $this->getFactory()->getEmailService()->getEmail();
    if ($email->hasHtml()) {
      $param['template_html'] = $email->getBody('html');
    }
    if ($email->hasText()) {
      $param['template_txt'] = $email->getBody('text');
    }
  }

  public function __call($name, $args) {
    if (preg_match("/^hookAction(Admin[a-zA-Z0-9_]+)FormModifier$/i", $name, $matches)) {
      $type = $matches[1];
      $this->getCustomization()->addCustomFieldsToForm($matches[1], $args[0]);
    }
  }

  public function hookActionProductUpdate($params) {
    if (isset($params['id_product'])) {
      $this->getCustomization()->updateCustomFields('Product', array($params['id_product']));
    }
  }

  public function hookDisplayBackOfficeHeader() {
    $this->context->controller->addCSS($this->getPath('views/css/datakick.css'));
  }

  public function hookDisplayAdminProductsExtra($params) {
    $idProduct = isset($params['id_product']) ? $params['id_product'] : null;
    if (! Tools::getValue('id_product', $idProduct)) {
      return $this->displayWarning($this->l('You must save this product before adding customization.'));
    } else {
      $params = array(
        'fields' => array(
          array(
            'form' => array(
              'legend' => array(
                'title' => $this->l('Custom fields'),
                'icon' => 'icon-cogs'
              ),
              'description' => null,
              'input' => array(
              ),
              'submit' => array(
                'title' => $this->l('Save'),
                'name' => 'submitAddproductAndStay'
              )
            )
          )
        ),
        'fields_value' => array()
      );
      $this->getCustomization()->addCustomFieldsToForm('Product', $params);
      if (count($params['fields'][0]['form']['input']) == 0) {
        $params['fields'][0]['form']['description'] = 'No custom field exists. Please go to DataKick module to create a new one';
      }
      $this->smarty->assign('datakickFields', $params['fields']);
      $this->smarty->assign('datakickFieldsValue', $params['fields_value']);
      return $this->display(__FILE__, 'views/templates/admin/products_extra.tpl');
    }
  }

  public function hookActionObjectAddAfter($params) {
    if (isset($params['object'])) {
      $object = $params['object'];
      $this->getCustomization()->updateCustomFields(get_class($object), array($object->id));
    }
  }

  public function hookActionObjectUpdateAfter($params) {
    if (isset($params['object'])) {
      $object = $params['object'];
      $this->getCustomization()->updateCustomFields(get_class($object), array($object->id));
    }
  }

  public function getCronFrequency() {
    return array(
      'hour' => -1,
      'day' => -1,
      'month' => -1,
      'day_of_week' => -1
    );
  }

  public function getFactory($user=null) {
    require_once(dirname(__FILE__).'/engine/engine.php');
    return Datakick\PrestashopFactory::withContext(Context::getContext(), $user);
  }

  public function getAppTranslations() {
    require_once(dirname(__FILE__).'/js-app-translation.php');
    $appTranslations = new Datakick\JsAppTranslation($this);
    return $appTranslations->getTranslations();
  }

  private function getCustomization() {
    require_once(dirname(__FILE__).'/engine/prestashop/customization.php');
    return new Datakick\PrestashopCustomization($this);
  }

  private function getPath($path) {
    return _PS_MODULE_DIR_ . $this->name . '/' . $path;
  }
}
