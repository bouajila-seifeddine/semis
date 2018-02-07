<?php
/**
 * 2007-2015 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author PrestaShop SA <contact@prestashop.com>
 *  @author PrestaShop SA <contact@prestashop.com>
 *  @copyright  2007-2015 PrestaShop SA
 *  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

/**
 * @author PrestaHome Team <support@prestahome.com>
 * @copyright Copyright (c) 2014-2015 PrestaHome Team - www.PrestaHome.com
 * @license You only can use module, nothing more!
 */
if (!defined('_PS_VERSION_')) {
    exit;
}
    
include_once _PS_MODULE_DIR_ . 'prestahome/prestahome.php';

class AdminPrestaHomeOptionsController extends ModuleAdminController
{
    public $sections = array();
    public $defaults = array();
    public $options = array();
    public $fields;

    public $ignoredTypes = array('heading', 'heading-external','info-box', 'separator', 'title-block', 'sub-title-block');
    public $schemes;
    public $schemes_colors;

    public $dev_mode;

    public $ThemeOptions;

    public $theme_version;
    public $theme_name;
    public $theme_last_update;

    private $api_prestahome = 'https://api.prestahome.com/';

    protected $file_suffix;

    public function __construct()
    {
        parent::__construct();

        if (!$this->module->active) {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminHome'));
        }

        $this->display = 'view';
        $this->meta_title = $this->l('Theme Options');
        
        $ThemeOptions = new PrestaHomeOptions();

        $this->sections = $ThemeOptions->sections;
        $this->defaults = $ThemeOptions->defaults;
        $this->options = $ThemeOptions->options;

        $fields = array();
        foreach ($this->sections as $section) {
            if (isset($section['fields'])) {
                foreach ($section['fields'] as $field) {
                    $fields[$field['id']] = $field;
                }
            }
        }
        $this->fields = $fields;

        $this->shortcodes_search = $ThemeOptions->shortcodes_search;
        $this->shortcodes_replace = $ThemeOptions->shortcodes_replace;

        $this->ThemeOptions = $ThemeOptions;

        $this->dev_mode = false;

        $this->bootstrap = true;

        if (file_exists(_PS_MODULE_DIR_ . 'prestahome/theme.xml')) {
            $xml_theme = simplexml_load_file(_PS_MODULE_DIR_ . 'prestahome/theme.xml');
            $this->theme_name = $xml_theme->name;
            $this->theme_version = $xml_theme->version;
            $this->theme_last_update = $xml_theme->last_update;
            $this->schemes = explode(',', $xml_theme->schemes);
            $this->schemes_colors = explode(',', $xml_theme->schemes_colors);
        }

        $this->file_suffix = $this->getFileSuffix();

    }

    public function renderView()
    {
        $db = Db::getInstance();

        $this->context->controller->addJS(_MODULE_DIR_.'prestahome/views/js/select2/select2.min.js');
        $this->context->controller->addJS(_MODULE_DIR_.'prestahome/views/js/select2/select2_locale_'.$this->context->language->iso_code.'.js');
        $this->context->controller->addJqueryPlugin('colorpicker');
        
        $this->context->controller->addJS(_MODULE_DIR_.'prestahome/views/js/sticky.js');
        
        $this->context->controller->addJS(_MODULE_DIR_.'prestahome/views/js/SimpleAjaxUploader.min.js');
        $this->context->controller->addJS(_MODULE_DIR_.'prestahome/views/js/bootstrap-confirmation.js');
        $this->context->controller->addJS(_MODULE_DIR_.'prestahome/views/js/main.js');

        # css
        $this->context->controller->addCSS(_MODULE_DIR_.'prestahome/views/css/select2/select2.css', 'all');

        $this->context->controller->addCSS(_MODULE_DIR_.'prestahome/views/css/font-awesome.css', 'all');
        $this->context->controller->addCSS(_MODULE_DIR_.'prestahome/views/css/main.css', 'all');

        $iso = $this->context->language->iso_code;

        $tplVars = array();

        $tplVars = $this->checkForUpdate();

        /**
         * Error handling
         */
        $tplVars['error_msg'] = false;
        if (Tools::getValue('error_msg')) {
            switch (Tools::getValue('error_msg')) {
                case 1:
                    $tplVars['error_msg'] = $this->l('Something went wrong with the update, please contact us.');
                    break;

                case 2:
                    $tplVars['error_msg'] = $this->l('You need to provide valid purchase code in order to run auto-update.');
                    break;

                case 3:
                    $tplVars['error_msg'] = $this->l('Provided purchase code is not valid!');
                    break;

                case 4:
                    $tplVars['error_msg'] = $this->l('Cannot copy update files to temporary location on your server, maybe there is a problem with CHMOD, everything is fine with your store however update was not done.');
                    break;

                case 5:
                    $tplVars['error_msg'] = $this->l('Something went wrong while unzipping update files, maybe problem with CHMOD? everything is fine with your store however update was not done.');
                    break;

                case 6:
                    $tplVars['error_msg'] = $this->l('Ooops! Something is not right on our server, please contact us on support system. Everything is fine with your store however update was not done.');
                    break;
            }
        }

        $tplVars['iso'] = file_exists(_PS_CORE_DIR_.'/js/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en';
        $tplVars['path_css'] = _THEME_CSS_DIR_;
        $tplVars['ad'] = __PS_BASE_URI__.basename(_PS_ADMIN_DIR_);
        $tplVars['tinymce'] = true;

        $this->context->controller->addJS(_PS_JS_DIR_.'tiny_mce/tiny_mce.js');
        $this->context->controller->addJS(_PS_JS_DIR_.'admin/tinymce.inc.js');
        $this->context->controller->addJS(_PS_JS_DIR_.'tinymce.inc.js');

        $this->context->controller->addJqueryPlugin('autosize');
        
        $tplVars['title'] = 'Theme Options - '.$this->theme_name.' version '.$this->theme_version.' (Last update: '.$this->theme_last_update.')';

        $tplVars['sections'] = $this->sections;
        $tplVars['options'] = $this->options;
        $tplVars['ignoredTypes'] = $this->ignoredTypes;
        $tplVars['languages'] = Language::getLanguages(false);
        $tplVars['defaultFormLanguage'] = $this->context->language->id;
        $tplVars['safeCurrentOptions'] = serialize($this->options);
        $tplVars['schemes'] = $this->schemes;
        $tplVars['schemes_colors'] = $this->schemes_colors;
        $tplVars['fonts'] = $this->ThemeOptions->GoogleFonts;
        $tplVars['action'] = AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminPrestaHomeOptions');
        $tplVars['module_dir'] = _MODULE_DIR_.'prestahome/';

        $this->tpl_view_vars = $tplVars;

        return parent::renderView();
    }

    public function initPageHeaderToolbar()
    {
        unset($this->toolbar_btn['back']);
        $this->toolbar_btn['save'] = array(
            'short' => 'save',
            'href' => '#',
            'desc' => $this->l('Save'),
        );
        $this->toolbar_btn['refresh-index'] = array(
            'short' => 'refresh',
            'icon' => 'process-icon-reset',
            'href' => AdminController::$currentIndex.'&restoreSettings=1&token='.Tools::getAdminTokenLite('AdminPrestaHomeOptions'),
            'desc' => $this->l('Restore defaults'),
        );

        $this->toolbar_btn['back-to-top'] = array(
            'short' => 'back-to-top',
            'icon' => 'process-icon-anchor',
            'href' => '#header',
            'desc' => $this->l('Back to top'),
        );

        
    }

    public function ajaxProcessSwitchModuleStatus()
    {
        if (!($module = Tools::getValue('module'))) {
            $this->jsonError(Tools::displayError('An error occurred (the module name not exists).'));
        }
        
        $action = Tools::getValue('method');

        if ($action == 'enable') {
            Module::enableByName($module);

            $return = Tools::jsonEncode(
                array(
                    'success'=> true,
                    'confirmations' => $this->l('Module: '.$module.', enabled successfully')
                )
            );
        }

        if ($action == 'disable') {
            Module::disableByName($module);

            $return = Tools::jsonEncode(
                array(
                    'success'=> true,
                    'confirmations' => $this->l('Module: '.$module.', disabled successfully')
                )
            );
        }

        $this->content = die($return);

    }

    public function ajaxProcessSaveOptions()
    {
        $this->saveOptions(true);

        $return = Tools::jsonEncode(
            array(
                'success'=> true,
                'confirmations' => $this->l('Settings saved')
            )
        );

        $this->content = die($return);
    }

    public function ajaxProcessDeleteImage()
    {
        $field_id = Tools::getValue('field');
        @unlink(_PS_MODULE_DIR_.'prestahome/views/img/upload/'.$this->options[$field_id]);
        $this->ThemeOptions->emptyOption($field_id);
        $this->ThemeOptions->processCSS();

        $return = Tools::jsonEncode(
            array(
                'success'=> true,
                'confirmations' => $this->l('Image deleted'),
            )
        );

        $this->content = die($return);
    }

    public function ajaxProcessUploadImage()
    {
        require _PS_MODULE_DIR_.'prestahome/classes/Uploader.php';
        $key = Tools::getValue('nameOfTheFile');

        $file_name = $key.$this->file_suffix;

        $upload_dir = _PS_MODULE_DIR_.'prestahome/views/img/upload/';
        $uploader = new FileUpload($key);
        $uploader->newFileName = $file_name;
        $result = $uploader->handleUpload($upload_dir);
        
        if (!$this->ThemeOptions->updateOption($key, $uploader->getFileName()) || !$this->ThemeOptions->processCSS()) {
            $return = Tools::jsonEncode(
                array(
                    'success'=> false,
                    'msg' => $this->module->l('Cannot save the option'),
                )
            );
        }

        if ($result) {
            $return = Tools::jsonEncode(
                array(
                    'success'=> true,
                    'confirmations' => $this->l('Image successfully uploaded'),
                    'file' => $uploader->getFileName(),
                )
            );
        } else {
            $return = Tools::jsonEncode(
                array(
                    'success'=> false,
                    'msg' => $uploader->getErrorMsg()
                )
            );
        }
        

        $this->content = die($return);
    }

    public function postProcess()
    {
        if (Tools::isSubmit('doUpdate')) {
            $this->doThemeUpdate();
        }

        if (Tools::isSubmit('submitImportOptions')) {
            $options = unserialize(PrestaHomeOptions::decode(Tools::getValue('newOptionsToImport')));

            foreach ($options as $key => $value) {
                $this->ThemeOptions->updateOption($key, $value);
            }

            Tools::redirectAdmin(AdminController::$currentIndex.'&conf=4&token='.Tools::getAdminTokenLite('AdminPrestaHomeOptions'));
        }

        if (Tools::isSubmit('restoreSettings')) {
            Configuration::deleteByName('prestahome_options_custom');

            $css_files_path = _PS_MODULE_DIR_.'prestahome/views/css/userCss*';

            foreach (glob($css_files_path) as $filename) {
                @unlink($filename);
            }

            $img_files_path = _PS_MODULE_DIR_.'prestahome/views/img/upload/*';

            foreach (glob($img_files_path) as $filename) {
                if (strpos($filename, 'index.php') !== false) {
                    continue;
                }
                @unlink($filename);
            }

            $f = @fopen(_PS_MODULE_DIR_.'prestahome/views/css/custom'.$this->file_suffix.'.css', 'r+');
            if ($f !== false) {
                @ftruncate($f, 0);
                fclose($f);
            }
            unset($f);

            $f = @fopen(_PS_MODULE_DIR_.'prestahome/views/js/custom-header'.$this->file_suffix.'.js', 'r+');
            if ($f !== false) {
                @ftruncate($f, 0);
                fclose($f);
            }
            unset($f);

            $f = @fopen(_PS_MODULE_DIR_.'prestahome/views/js/custom-footer'.$this->file_suffix.'.js', 'r+');
            if ($f !== false) {
                @ftruncate($f, 0);
                fclose($f);
            }
            unset($f);

            $this->ThemeOptions->installOptions();
                        
            Tools::redirectAdmin(AdminController::$currentIndex.'&conf=21&token='.Tools::getAdminTokenLite('AdminPrestaHomeOptions'));
        }

        // if(Tools::isSubmit('deleteUploadedImage'))
        // {
        //  $field_id = Tools::getValue('field_id');

        //  if(isset($this->options[$field_id]['path']))
        //  {
        //      $image = $this->options[$field_id]['path'];

        //      // we of course delete image but only when uploaded image is different then original one
        //      if(!isset($this->defaults[$field_id]['path']) || isset($this->defaults[$field_id]['path']) && $this->defaults[$field_id]['path'] != $this->options[$field_id]['path'])
        //          @unlink($image);
        //  }
        //  PrestaHomeOptions::emptyOption($field_id);
            
        //  Tools::redirectAdmin(AdminController::$currentIndex.'&conf=7&token='.Tools::getAdminTokenLite('AdminPrestaHomeOptions'));
        // }

        // if(Tools::isSubmit('submitOptionsconfiguration'))
        // {
        //  $this->saveOptions();

        //  if(sizeof($errors))
        //  {
        //      array_unshift($errors, Tools::displayError('Settings saved but with errors.'));
        //      $this->errors = $errors;
        //      $this->display = 'view';
        //  }
        //  else
        //      Tools::redirectAdmin(AdminController::$currentIndex.'&conf=4&tab='.Tools::getValue('ph_tab', 0).'&token='.Tools::getAdminTokenLite('AdminPrestaHomeOptions'));
        // }

        parent::postProcess();
    }

    public function saveOptions($ajax = false)
    {
        /**

        POST

        **/
        $errors = array();

        $fields = Tools::getValue('fields');

        if ($ajax) {
            parse_str(Tools::getValue('fields'), $datas);
            $fields = $datas['fields'];
        }

        // valid & available to save options
        $options = array();

        // current options
        $currents = $this->options;

        foreach ($fields as $key => $value) {
            if ($this->fields[$key]['type'] == 'custom_css' || $this->fields[$key]['type'] == 'custom_js') {
                continue;
            }
            
            $canSave = false;
            $issetError = false;
            $fieldTitle = isset($this->fields[$key]['title']) ? $this->fields[$key]['title'] : $this->fields[$key]['id'];

            /* Todo: Bedziemy musieli obslugiwac dane z tablic totez musimy dodac w przyszlosci zmienna multilang => true dla takich danych! */
            if (is_array($value)) {
                // multilang
                if (isset($this->fields[$key]['validate'])) {
                    foreach ($value as $lang => $single) {
                        if (call_user_func(array('Validate', $this->fields[$key]['validate']), $single)) {
                            $canSave = true;
                        } else {
                            $issetError = true;
                            $errors[] = '['.Tools::strtoupper(Language::getIsoById($lang)).'] '.sprintf(Tools::displayError('field %s is invalid.'), $fieldTitle);
                        }
                    }
                } else {
                    $canSave = true;
                }

                if (isset($this->fields[$key]['required']) && $this->fields[$key]['required'] == true && !$issetError) {
                    foreach ($value as $lang => $single) {
                        if (!empty($single)) {
                            $canSave = true;
                        } else {
                            $issetError = true;
                            $errors[] = '['.Tools::strtoupper(Language::getIsoById($lang)).'] '.sprintf(Tools::displayError('field %s is required.'), $fieldTitle);
                        }
                    }
                } else {
                    $canSave = true;
                }

                if (!isset($value['path']) && !isset($value['url'])) {
                    //$value = str_replace($this->shortcodes_search, $this->shortcodes_replace, array_map( 'stripslashes', $value ));
                } else {
                    //$value = str_replace($this->shortcodes_search, $this->shortcodes_replace, $value);
                }

                if ($canSave && !$issetError) {
                    $options[$key] = $value;
                } else {
                    $options[$key] = $currents[$key];
                }
            } else {
                $lang = $this->context->language->id;

                if (isset($this->fields[$key]['validate'])) {
                    if (call_user_func(array('Validate', $this->fields[$key]['validate']), $single)) {
                        $canSave = true;
                    } else {
                        $issetError = true;
                        $errors[] = '['.Tools::strtoupper(Language::getIsoById($lang)).'] '.sprintf(Tools::displayError('field %s is invalid.'), $fieldTitle);
                    }
                } else {
                    $canSave = true;
                }

                if (isset($this->fields[$key]['required']) && $this->fields[$key]['required'] == true && !$issetError) {
                    if (!empty($value)) {
                        $canSave = true;
                    } else {
                        $issetError = true;
                        $errors[] = '['.Tools::strtoupper(Language::getIsoById($lang)).'] '.sprintf(Tools::displayError('field %s is required.'), $fieldTitle);
                    }
                } else {
                    $canSave = true;
                }

                //$key = 'imp_to_'.Tools::substr(md5($key),0,25);
                //$value = str_replace($this->shortcodes_search, $this->shortcodes_replace, stripslashes($value));
                //$value = str_replace($this->shortcodes_search, $this->shortcodes_replace, $value);

                if ($canSave && !$issetError) {
                    $options[$key] = $value;
                } else {
                    $options[$key] = $currents[$key];
                }

            }

        }

        $this->ThemeOptions->updateCustomOptions($options);

        /**

        PROCESS FILES

        **/

        // if(isset($_FILES['images']))
        // {
        //  $images2upload = PrestaHomeOptions::reArrayFiles($_FILES['images']);

        //  foreach($images2upload as $key => $image)
        //  {
        //      if( empty($image['tmp_name']) ) continue;

        //      $type = Tools::strtolower(Tools::substr(strrchr($image['name'], '.'), 1));
        //      $imagesize = array();
        //      $imagesize = @getimagesize($image['tmp_name']);

        //      if (isset($image) &&
        //          isset($image['tmp_name']) &&
        //          !empty($image['tmp_name']) &&
        //          !empty($imagesize) &&
        //          in_array(Tools::strtolower(Tools::substr(strrchr($imagesize['mime'], '/'), 1)), array('jpg', 'gif', 'jpeg', 'png')) &&
        //          in_array($type, array('jpg', 'gif', 'jpeg', 'png')))
        //      {
        //          $temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
        //          $salt = sha1(microtime());

        //          $file_name = $key.$this->file_suffix;
        //          // if ($error = ImageManager::validateUpload($image))
        //          //     $errors[] = $error;
        //          // elseif (!$temp_name || !move_uploaded_file($image['tmp_name'], $temp_name))
        //          //     return false;
        //          // elseif (!ImageManager::resize($temp_name, _PS_MODULE_DIR_.'prestahome/views/img/upload/'.Tools::encrypt($key).'.'.$type, null, null, $type))
        //          //     $errors[] = sprintf(Tools::displayError('Problem with upload image for option: %s. Bad image type.'), $$key);
        //          // if (isset($temp_name))
        //          //     @unlink($temp_name);
        //          if (!$temp_name || !move_uploaded_file($image['tmp_name'], $temp_name))
        //              return false;
        //          elseif (!ImageManager::resize($temp_name, _PS_MODULE_DIR_.'prestahome/views/img/upload/'.$file_name.'.'.$type, null, null, $type))
        //              $errors[] = sprintf(Tools::displayError('Problem with upload image for option: %s. Bad image type.'), $key);
        //          if (isset($temp_name))
        //              @unlink($temp_name);

        //          $field_value = array('url' => _MODULE_DIR_.'prestahome/views/img/upload/'.$file_name.'.'.$type, 'path' => _PS_MODULE_DIR_.'prestahome/views/img/upload/'.$file_name.'.'.$type);
                    
        //          $this->ThemeOptions->updateOption($key, $field_value);
        //      }
        //  }
        // }

        /**

        PROCESS CUSTOM CSS

        **/

        $this->ThemeOptions->processCSS();

        /**

        PROCESS CUSTOM CSS AND JS TO FILES

        **/

        foreach ($fields as $key => $value) {
            if ($key == 'custom_css') {
                $file = _PS_MODULE_DIR_.'prestahome/views/css/custom'.$this->file_suffix.'.css';

                $css = $value;
                @file_put_contents($file, $css, LOCK_EX);
            }

            if ($key == 'custom_js_head') {
                $file = _PS_MODULE_DIR_.'prestahome/views/js/custom-header'.$this->file_suffix.'.js';

                $js = $value;
                @file_put_contents($file, $js, LOCK_EX);
            }

            if ($key == 'custom_js_footer') {
                $file = _PS_MODULE_DIR_.'prestahome/views/js/custom-footer'.$this->file_suffix.'.js';

                $js = $value;
                @file_put_contents($file, $js, LOCK_EX);
            }
        }

        Tools::clearSmartyCache();
        Autoload::getInstance()->generateIndex();
    }

    public function getFileSuffix()
    {
        $file_suffix = '';
        if (Shop::getContext() == Shop::CONTEXT_GROUP) {
            $file_suffix = '_group_'.(int)Context::getContext()->shop->getContextShopGroupID();
        } elseif (Shop::getContext() == Shop::CONTEXT_SHOP) {
            $file_suffix = '_shop_'.(int)Context::getContext()->shop->getContextShopID();
        }

        return $file_suffix;
    }

    private function checkForUpdate()
    {
        $tplVars = array();

        if (!$this->options['check_for_updates']) {
            $tplVars['update_available'] = false;
            return $tplVars;
        }

        $newer_version = Tools::jsonDecode(Tools::file_get_contents($this->api_prestahome.'check_for_update.php?checkNewerVersion=true&theme_name='.Tools::strtolower($this->theme_name).'&theme_version='.$this->theme_version), true);

        if (is_array($newer_version) && $newer_version['version'] != false) {
            $tplVars['newer_version'] = $newer_version['version'];
            $tplVars['is_auto_update_available'] = $newer_version['is_auto'];
            $tplVars['update_available'] = true;
            $tplVars['invalid_purchase_code'] = $newer_version['invalid_purchase_code'];

            $tplVars['changelog'] = false;
            if ($newer_version['changelog'] != false) {
                $tplVars['changelog'] = $newer_version['changelog'];
            }
        } else {
            $tplVars['update_available'] = false;
        }

        return $tplVars;
    }

    private function doThemeUpdate()
    {
        if (!$this->options['check_for_updates']) {
            return;
        }

        if ($newer_version['invalid_purchase_code'] && empty($this->options['purchase_code'])) {
            Tools::redirectAdmin(AdminController::$currentIndex.'&error_msg=2&token='.Tools::getAdminTokenLite('AdminPrestaHomeOptions'));
        } else {
            $newer_version = Tools::file_get_contents($this->api_prestahome.'check_for_update.php?checkNewerVersion=true&theme_name='.Tools::strtolower($this->theme_name).'&theme_version='.$this->theme_version.'&purchase_code='.$this->options['purchase_code']);
        }

        if ($newer_version) {
            if (!is_array($newer_version = Tools::jsonDecode($newer_version, true))) {
                Tools::redirectAdmin(AdminController::$currentIndex.'&error_msg=1&token='.Tools::getAdminTokenLite('AdminPrestaHomeOptions'));
            }
        }

        if ($newer_version['invalid_purchase_code']) {
            Tools::redirectAdmin(AdminController::$currentIndex.'&error_msg=3&token='.Tools::getAdminTokenLite('AdminPrestaHomeOptions'));
        }

        if ($newer_version['update_file'] == 'non-exist') {
            Tools::redirectAdmin(AdminController::$currentIndex.'&error_msg=6&token='.Tools::getAdminTokenLite('AdminPrestaHomeOptions'));
        }
        
        $updateFileUrl = $this->api_prestahome.$newer_version['update_file'];
        $tmp_file = _PS_MODULE_DIR_.md5(time()).'.zip';

        if (!Tools::copy($updateFileUrl, $tmp_file)) {
            Tools::redirectAdmin(AdminController::$currentIndex.'&error_msg=4&token='.Tools::getAdminTokenLite('AdminPrestaHomeOptions'));
        } else {
            if (Tools::ZipExtract($tmp_file, _PS_ROOT_DIR_.'/')) {
                @unlink($tmp_file);
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules').'&conf=29&token='.Tools::getAdminTokenLite('AdminModules'));
            } else {
                Tools::redirectAdmin(AdminController::$currentIndex.'&error_msg=5&token='.Tools::getAdminTokenLite('AdminPrestaHomeOptions'));
            }
        }
    }

    public function getPrestaHomeApiUrl()
    {
        return $this->api_prestahome;
    }
}
