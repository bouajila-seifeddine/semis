<?php
/*
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
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
/*
* @author Krystian Podemski <podemski.krystian@gmail.com>
* @copyright Copyright (c) 2014-2015 Krystian Podemski - www.PrestaHome.com
* @license You only can use module, nothing more!
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class PH_HTML_Column extends Module
{

    private $_html = '';
    public $fields_form;
    public $fields_value;
    public $validation_errors = array();

    public static $cfg_prefix = 'PH_HTML_COLUMN_';
    
    public function __construct()
    {
        $this->name = 'ph_html_column';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'www.PrestaHome.com';
        $this->need_instance = 0;
        $this->is_configurable = 1;
        $this->secure_key = Tools::encrypt($this->name);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Custom HTML - column');
        $this->description = $this->l('Block with Custom HTML content displayed in column');

        $this->confirmUninstall = $this->l('Are you sure you want to delete this module?');
    }

    public function getDefaults()
    {
        return array(
            'TITLE' => $this->prepareValueForLangs('This is title'),
            'TEXT' => $this->prepareValueForLangs('This is content'),
        );
    }

    public function prepareValueForLangs($value)
    {
        $languages = Language::getLanguages(false);

        $output = array();
        foreach($languages as $language)
        {
            $output[$language['id_lang']] = $value;
        }

        return $output;
    }

    public function install()
    {
        // Hooks & Install
        return (parent::install() 
                && $this->prepareModuleSettings() 
                && $this->registerHook('displayLeftColumn') 
            );
    }

    public function prepareModuleSettings()
    {
        foreach($this->getDefaults() as $key => $value)
        {
            Configuration::updateValue(self::$cfg_prefix.$key, $value, true);
        }
       
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        foreach($this->getDefaults() as $key => $value)
        {
            Configuration::deleteByName(self::$cfg_prefix.$key);
        }

        return true;
    }

    public function getContent()
    {
        $this->initFieldsForm();
        if (Tools::getIsset('save'.$this->name))
        {
            $multiLangFields = array();
            foreach($this->getDefaults() as $field_name => $field_value)
            {
                if(is_array($field_value))
                {
                    $multiLangFields[] = self::$cfg_prefix.$field_name;
                }
            }

            foreach ($_POST as $key => $value)
            {
                $fieldName = Tools::substr($key, 0, -2);

                if(in_array($fieldName, $multiLangFields))
                {
                    $thisFieldValue = array();
                    foreach(Language::getLanguages(true) as $language)
                    {
                        if(Tools::getIsset($fieldName.'_'.$language['id_lang']))
                        {
                            $thisFieldValue[$language['id_lang']] = Tools::getValue($fieldName.'_'.$language['id_lang']);
                        }
                    }
                    $_POST[$fieldName] = $thisFieldValue;
                }
            }

            foreach($this->getDefaults() as $field_name => $field_value)
            {
                if(is_array($field_value))
                {
                    Configuration::updateValue($field_name, ${$field_name}, true);
                }
            }
                

            foreach($this->fields_form as $form)
                foreach($form['form']['input'] as $field)
                    if(isset($field['validation']))
                    {
                        $errors = array();       
                        $value = Tools::getValue($field['name']);
                        if (isset($field['required']) && $field['required'] && $value==false && (string)$value != '0')
                                $errors[] = sprintf(Tools::displayError('Field "%s" is required.'), $field['label']);
                        elseif($value)
                        {
                            if (!Validate::$field['validation']($value))
                                $errors[] = sprintf(Tools::displayError('Field "%s" is invalid.'), $field['label']);
                        }

                        // Set default value
                        if ($value === false && isset($field['default_value']))
                            $value = $field['default_value'];
                            
                        if(count($errors))
                        {
                            $this->validation_errors = array_merge($this->validation_errors, $errors);
                        }
                        elseif($value==false)
                        {
                            switch($field['validation'])
                            {
                                case 'isUnsignedId':
                                case 'isUnsignedInt':
                                case 'isInt':
                                case 'isBool':
                                    $value = 0;
                                break;
                                default:
                                    $value = '';
                                break;
                            }
                            Configuration::updateValue($field['name'], $value, true);
                        }
                        else
                            Configuration::updateValue($field['name'], $value, true);
                    }

            if(count($this->validation_errors))
                $this->_html .= $this->displayError(implode('<br/>',$this->validation_errors));
            else 
                $this->_html .= Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&conf=6&token='.Tools::getAdminTokenLite('AdminModules'));
        }

        $helper = $this->initForm();
        foreach($this->getDefaults() as $key => $value)
        {
            if(is_array($value))
            {
                foreach ($value as $lang => $val)
                    $helper->fields_value[self::$cfg_prefix.$key][(int)$lang] = Tools::getValue(self::$cfg_prefix.$key.'_'.(int)$lang, Configuration::get(self::$cfg_prefix.$key, (int)$lang));
            }
            else
            {
                $helper->fields_value[self::$cfg_prefix.$key] = Configuration::get(self::$cfg_prefix.$key);
            }
        }

        return $this->_html.$helper->generateForm($this->fields_form);
    }

    protected function initFieldsForm()
    {
        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->displayName,
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Title:'),
                    'name' => self::$cfg_prefix.'TITLE',
                    'lang' => true,
                    'validation' => 'isCleanHtml',
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Content:'),
                    'name' => self::$cfg_prefix.'TEXT',
                    'lang' => true,
                    'autoload_rte' => true,
                    'cols' => 60,
                    'rows' => 30,
                    'validation' => 'isCleanHtml',
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'button pull-right'
            )
        );
        
    }

    protected function initForm()
    {
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        foreach (Language::getLanguages(false) as $lang)
            $helper->languages[] = array(
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
            );

        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'save'.$this->name;
        $helper->toolbar_btn =  array(
            'save' =>
            array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
            )
        );
        return $helper;
    }

    public function assignModuleVariables()
    {
        foreach($this->getDefaults() as $key => $value)
        {
            if(is_array($value))
                $this->smarty->assign(Tools::strtolower($key), Configuration::get(self::$cfg_prefix.$key, $this->context->language->id));
            else
                $this->smarty->assign(Tools::strtolower($key), Configuration::get(self::$cfg_prefix.$key));
        }
    }

    public function hookDisplayLeftColumn()
    {
        $this->assignModuleVariables();

        return $this->display(__FILE__, 'column.tpl');
    }

    public function hookDisplayRightColumn()
    {
        return $this->hookDisplayLeftColumn();
    }
}
