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
 *  @copyright  2007-2015 PrestaShop SA
 *  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

/**
 * @author PrestaHome Team <support@prestahome.com>
 * @copyright Copyright (c) 2014-2015 PrestaHome Team - www.PrestaHome.com
 * @license You only can use module, nothing more!
 */
define('_PH_SHOP_URL_', _PS_BASE_URL_.__PS_BASE_URI__);
define('_PH_UPLOAD_URL_', _PS_BASE_URL_.__PS_BASE_URI__.'modules/prestahome/views/img/upload/');

class PrestaHomeOptions
{
    public $sections    = array();
    public $defaults    = array();
    public $options     = array();

    public $shortcodes_search = array('[shop_url]','[upload_url]','[theme_url]','[theme_path]');
    public $shortcodes_replace = array(_PH_SHOP_URL_,_PH_UPLOAD_URL_,_THEME_DIR_,_PS_THEME_DIR_);
    public $GoogleFonts;

    public function __construct()
    {
        $this->sections = include _PS_MODULE_DIR_.'prestahome/options.php';
        $this->defaults = $this->getDefaultOptions();
        $this->options = $this->getOptions();

        $controller = Tools::getValue('controller', 0);

        if ($controller && $controller == 'AdminPrestaHomeOptions' || (Tools::getValue('tab', 0) && Tools::getValue('tab') == 'AdminPrestaHomeOptions')) {
            $this->GoogleFonts = $this->getGoogleFonts();
        }
    }

    /**
     * Return options array
     * @param  boolean Use in theme for specific language or in Back-Office?
     * @return array
     */
    public function getOptions($use_in_theme = false)
    {
        $custom_options = $this->getCustomOptions();
        $default_options = $this->getDefaultOptions();

        if ($custom_options && is_array($custom_options)) {
            $options = array_merge($default_options, $custom_options);
        } else {
            $options = $default_options;
        }

        if ($options && sizeof($options)) {
            if ($use_in_theme) {
                return $this->prepareOptions($options, Context::getContext()->language->id);
            } else {
                return $options;
            }
        } else {
            die('Options doesnt exists, something went wrong');
        }
    }

    public static function encode($data)
    {
        $functionName = 'base' . '64'. '_encode';

        return $functionName($data);
    }

    public static function decode($data)
    {
        $functionName = 'base' . '64'. '_decode';

        return $functionName($data);
    }

    public function installOptions()
    {
        return PrestaHomeOptions::updateValue('prestahome_options_custom', PrestaHomeOptions::encode(serialize($this->defaults)), true);
    }

    public function getDefaultOptions()
    {
        // if(!Configuration::get('prestahome_options_default'))
        //     $this->installOptions();

        // $options = preg_replace_callback ( '!s:(\d+):"(.*?)";!', function($match) {
        //     return ($match[1] == Tools::strlen($match[2], 'ASCII')) ? $match[0] : 's:' . Tools::strlen($match[2], 'ASCII') . ':"' . $match[2] . '";';
        // }, Configuration::get('prestahome_options_default'));

        // return unserialize(PrestaHomeOptions::decode($options));
        
        $options = array();

        foreach ($this->sections as $k => $section) {
            if (sizeof($section['fields'])) {
                foreach ($section['fields'] as $key => $field) {
                    if ($field['type'] == 'custom_js' or $field['type'] == 'custom_css') {
                        continue;
                    }
                    
                    if (isset($field['default'])) {
                        $value = $field['default'];

                        $value = str_replace($this->shortcodes_search, $this->shortcodes_replace, $value);
                        // if(!isset($value['path']) && !isset($value['url']) && is_array($value))
                        // {
                        //     $value = str_replace($this->shortcodes_search, $this->shortcodes_replace, array_map( 'stripslashes', $value ));
                        // }
                        // else
                        // {
                        //     $value = str_replace($this->shortcodes_search, $this->shortcodes_replace, $value);
                        // }

                        $options[$field['id']] = $value;
                    } else {
                        if (isset($field['options'])) {
                            $options[$field['id']] = $field['options'];
                        }
                    }
                    
                }
            }
        }

        return $options;
    }

    public function getCustomOptions()
    {
        if (!Configuration::get('prestahome_options_custom')) {
            $this->copyDefaultsToCustom();
        }

        $options = preg_replace_callback('!s:(\d+):"(.*?)";!', function ($match) {
            return ($match[1] == Tools::strlen($match[2], 'ASCII')) ? $match[0] : 's:' . Tools::strlen($match[2], 'ASCII') . ':"' . $match[2] . '";';
        }, Configuration::get('prestahome_options_custom'));

        return unserialize(PrestaHomeOptions::decode($options));
    }

    public function updateCustomOptions($options)
    {
        return PrestaHomeOptions::updateValue('prestahome_options_custom', PrestaHomeOptions::encode(serialize($options)), true);
    }

    public function copyDefaultsToCustom()
    {

    }

    /**
     * Return value with every available language
     * @param  string Value
     * @return array
     */
    public static function prepareValueForLangs($value)
    {
        $output = array();

        foreach (Language::getLanguages(false) as $lang) {
            $output[$lang['id_lang']] = $value;
        }

        return $output;
    }

    public function l($string)
    {
        return Translate::getModuleTranslation('prestahome', $string, 'options');
    }

    public function prepareOptions($options, $id_lang)
    {
        $output = array();

        foreach ($options as $key => $option) {
            if (is_array($option)) {
                if (isset($option[$id_lang])) {
                    $output[$key] = $option[$id_lang];
                } else {
                    $output[$key] = $option;
                }
            } else {
                $output[$key] = $option;
            }
        }

        return $output;
    }

    public function updateOption($name, $value)
    {
        $options = $this->getOptions();

        if ($options && is_array($options)) {
            $options[$name] = $value;
        } else {
            return;
        }

        return PrestaHomeOptions::updateValue('prestahome_options_custom', PrestaHomeOptions::encode(serialize($options)), true);
    }

    public function emptyOption($name)
    {
        $options = $this->getOptions();

        if ($options && is_array($options)) {
            $options[$name] = '';
        } else {
            return;
        }

        return PrestaHomeOptions::updateValue('prestahome_options_custom', PrestaHomeOptions::encode(serialize($options)), true);
    }

    public static function getOptionAsImage($name, $width = '', $height = '', $before = '', $after = '', $class = '', $id = '', $alt = '')
    {
        $options = $this->getOptions();

        if (empty($options[$name]['url']) || !file_exists($options[$name]['path'])) {
            return;
        }

        return $before.'<img src="'.$options[$name]['url'].'" '.(!empty($width) ? 'width="'.$width.'"' : '').' '.(!empty($height) ? 'height="'.$height.'"' : '').' '.(!empty($class) ? 'class="'.$class.'"' : '').' '.(!empty($id) ? 'id="'.$id.'"' : '').' '.(!empty($alt) ? 'alt="'.$alt.'"' : '').' />'.$after;
    }

    public static function reArrayFiles(&$file_post)
    {

        $output = array();

        foreach ($file_post['name'] as $key => $fileName) {
            $output[$key]['name'] = $fileName[0];
        }

        foreach ($file_post['type'] as $key => $filetype) {
            $output[$key]['type'] = $filetype[0];
        }

        foreach ($file_post['size'] as $key => $filesize) {
            $output[$key]['size'] = $filesize[0];
        }

        foreach ($file_post['tmp_name'] as $key => $filetmp_name) {
            $output[$key]['tmp_name'] = $filetmp_name[0];
        }

        return $output;
    }

    public static function resetBoxShadow($settingValue)
    {
        if ($settingValue == '1') {
            return 'continue';
        } else {
            return 'none';
        }
    }

    public static function makeGradient($values, $params)
    {
        if (!is_array($values)) {
            return;
        }

        $search  = array('%start%', '%end%');
        $replace = array($values['start'], $values['end']);
        $subject = $params['pattern'];

        return str_replace($search, $replace, $subject);
    }

    public static function convertToRGBA($color, $params)
    {
        if (isset($params['alpha'])) {
            $opacity = ', '.$params['alpha'];
        }

        $rgbColor = self::hex2rgb($color);

        return 'rgba('.$rgbColor.$opacity.')';
    }

    public function getValueByProperty($property, $value, $important)
    {
        if ($property == 'background-image') {
            //if(Configuration::get('PS_CSS_THEME_CACHE'))
                //$value['url'] = '../../../..'.$value['url'];
            //$url = explode('/', $value['url']);
            //$url = explode('/', $value['url']);
            return $property.': url(../img/upload/'.$value.')'.$important.';'.PHP_EOL;
        }

    }

    public function processCSS()
    {
        $options = $this->getOptions();

        $context = Context::getContext();

        $css = '';

        if ($options && sizeof($options)) {
            $cssOutput = array();
            $beforeAndAfter = array();
            $fontsToImport = array();

            foreach ($this->sections as $k => $section) {
                if (sizeof($section['fields'])) {
                    foreach ($section['fields'] as $key => $field) {
                        //if(isset($field['css']) && !empty($options[$field['id']]) && $field['css']['property'])
                        if (isset($field['css']) && $field['css']['property']) {
                            if (isset($field['css']['before'])) {
                                $beforeAndAfter[$field['css']['selector']]['before'] = $field['css']['before'];
                            }

                            if (isset($field['css']['after'])) {
                                $beforeAndAfter[$field['css']['selector']]['after'] = $field['css']['after'];
                            }

                            $important = '';
                            if (isset($field['css']['important'])) {
                                $important = '!important';
                            }

                            // Check dependencies
                            $continueTheLoop = false;

                            if (isset($field['css']['dependency']) && is_array($field['css']['dependency'])) {
                                foreach ($field['css']['dependency'] as $setting => $required_value) {
                                    if (!isset($options[$setting]) || $options[$setting] != $required_value) {
                                        $continueTheLoop = true;
                                    }
                                }

                                if ($continueTheLoop) {
                                    continue;
                                }
                            }

                            // Check callbacks
                            if (isset($field['css']['callback'])) {
                                if (method_exists('PrestaHomeOptions', $field['css']['callback'])) {
                                    $callback_params = array();

                                    // additional callback params if required
                                    if (isset($field['css']['callback_params']) && is_array($field['css']['callback_params'])) {
                                        $callback_params = $field['css']['callback_params'];
                                    }

                                    $valueFromCallback = call_user_func_array(array('PrestaHomeOptions', $field['css']['callback']), array($options[$field['id']], $callback_params));
                                    if ($valueFromCallback != 'continue') {
                                        if (is_array($field['css']['property']) && is_array($field['css']['selector'])) {
                                            foreach ($field['css']['selector'] as $tmp_key => $tmp_val) {
                                                $cssOutput[$field['css']['selector'][$tmp_key]][] = $field['css']['property'][$tmp_key].': '. $valueFromCallback.$important.';'.PHP_EOL;
                                            }
                                        } else {
                                            $cssOutput[$field['css']['selector']][] = $field['css']['property'].': '. $valueFromCallback.$important.';'.PHP_EOL;
                                        }

                                        continue;
                                    } else {
                                        continue;
                                    }
                                }
                            }

                            // Save colors only if user use custom color scheme
                            if (isset($field['is_color_scheme']) && $options['global_style'] != 'custom') {
                                continue;
                            }

                            if (is_array($field['css']['property']) && is_array($field['css']['selector'])) {
                                foreach ($field['css']['selector'] as $tmp_key => $tmp_val) {
                                    $cssOutput[$field['css']['selector'][$tmp_key]][] = $field['css']['property'][$tmp_key].': '. $options[$field['id']].$important.';'.PHP_EOL;
                                }
                            } else {
                                if ($field['css']['property'] == 'background-image') {
                                    if (!empty($options[$field['id']])) {
                                        $cssOutput[$field['css']['selector']][] = $this->getValueByProperty($field['css']['property'], $options[$field['id']], $important);
                                    }
                                } elseif ($field['css']['property'] == 'box-shadow') {
                                    $cssOutput[$field['css']['selector']][] = $field['css']['property'].': '.$field['css']['shadow'].' '.$options[$field['id']].$important.';'.PHP_EOL;
                                } elseif ($field['css']['property'] == 'font-family') {
                                    $fontFamily = $options[$field['id']];
                                    if (isset($this->GoogleFonts[$fontFamily]['url'])) {
                                        $fontsToImport[] = $this->GoogleFonts[$fontFamily]['url'];
                                    }
                                    
                                    $cssOutput[$field['css']['selector']][] = $field['css']['property'].': "'.$fontFamily.'"'.$important.';'.PHP_EOL;

                                    unset($fontFamily);
                                } else {
                                    $cssOutput[$field['css']['selector']][] = $field['css']['property'].': '. $options[$field['id']].$important.';'.PHP_EOL;
                                }
                            }
                            
                        } else {
                            continue;
                        }
                    }
                }
            }

            $css = "/* Custom CSS */".PHP_EOL;

            $fontsToImport = array_unique($fontsToImport);

            // Check if there are some fonts to import!
            if ($options['use_custom_fonts'] == '1' && sizeof($fontsToImport > 0)) {
                $customFontsCSS = "/* Custom Fonts CSS */".PHP_EOL;
                foreach ($fontsToImport as $fontUrl) {
                    $customFontsCSS .= '@import url('.$fontUrl.');'.PHP_EOL;
                }
                $fontsFile = _PS_MODULE_DIR_.'prestahome/views/css/customFonts.css';
                file_put_contents($fontsFile, $customFontsCSS, LOCK_EX);
            }

            // Generate CSS from selectors and properties
            foreach ($cssOutput as $selector => $propertyValue) {
                if (isset($beforeAndAfter[$selector]['before'])) {
                    $css .= $beforeAndAfter[$selector]['before'].PHP_EOL;
                }

                $css .= $selector.' {'.PHP_EOL;

                foreach ($propertyValue as $val) {
                    $css .= $val;
                }

                $css .= '}'.PHP_EOL;

                if (isset($beforeAndAfter[$selector]['after'])) {
                    $css .= $beforeAndAfter[$selector]['after'].PHP_EOL;
                }

                $css .= ''.PHP_EOL;
            }

           // Save CSS

            if (Shop::getContext() == Shop::CONTEXT_GROUP && Shop::isFeatureActive()) {
                $file_name = 'userCss-ShopGroup-'.(int)$context->shop->getContextShopGroupID().'.css';
            } elseif (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive()) {
                $file_name = 'userCss-Shop-'.(int)$context->shop->getContextShopID().'.css';
            } else {
                $file_name = 'userCss.css';
            }

            $file = _PS_MODULE_DIR_.'prestahome/views/css/'.$file_name;

            file_put_contents($file, $css, LOCK_EX);
        } else {
            return;
        }
    }

    public function getGoogleFonts($key = 'AIzaSyAZ8O32RwPGl2I2zeS4qUDuBmuSh4OEn80', $sort = 'alpha')
    {
        /*
        $key = Web Fonts Developer API
        $sort=
        alpha: Sort the list alphabetically
        date: Sort the list by date added (most recent font added or updated first)
        popularity: Sort the list by popularity (most popular family first)
        style: Sort the list by number of styles available (family with most styles first)
        trending: Sort the list by families seeing growth in usage (family seeing the most growth first)
        */

        @ini_set('allow_url_fopen', 'on');
        @ini_set('allow_url_include', 'on');

        $http = (!empty($_SERVER['HTTPS'])) ? "https" : "http";

        $url = 'https://www.googleapis.com/webfonts/v1/webfonts?key=' . $key . '&sort=' . $sort;

        $result = Tools::file_get_contents($url);
        if (!$result || !count($result)) {
            return 'Cannot load Google Fonts';
        }

        $GoogleFonts = Tools::jsonDecode($result);

        $available_fonts = array(
            'Arial' => array('url' => ''),
            'Courier New' => array('url' => ''),
            'Comic Sans MS' => array('url' => ''),
            'Tahoma' => array('url' => ''),
            'Times' => array('url' => ''),
            'Georgia' => array('url' => ''),
        );

        foreach ($GoogleFonts->items as $key => $font) {
            $available_fonts[$font->family] = array();
            $font_name = trim(str_replace(' ', '+', $font->family));

            $font_variants = implode($font->variants, ',');

            $options = $this->getOptions(true);

            $font_subsets = array();

            if (isset($options['include_google_cyrillic']) && $options['include_google_cyrillic'] == 1) {
                if (in_array('cyrillic', $font->subsets)) {
                    $font_subsets[] = 'cyrillic';
                }
                if (in_array('cyrillic-ext', $font->subsets)) {
                    $font_subsets[] = 'cyrillic-ext';
                }
            }

            if (isset($options['include_google_greek']) && $options['include_google_greek'] == 1) {
                if (in_array('greek', $font->subsets)) {
                    $font_subsets[] = 'greek';
                }
                if (in_array('cyrillic-ext', $font->subsets)) {
                    $font_subsets[] = 'greek-ext';
                }
            }

            $available_fonts[$font->family]['css-name'] = $font_name;

            $available_fonts[$font->family]['url'] = trim('//fonts.googleapis.com/css?family='.$font_name.':'.$font_variants.'&subset=latin,latin-ext,'.join(',', $font_subsets), ',');
        }

        return $available_fonts;
    }

    public static function updateValue($key, $values, $html = false, $id_shop_group = null, $id_shop = null)
    {
        if (!Validate::isConfigName($key)) {
            die(Tools::displayError());
        }

        if ($id_shop === null) {
            $id_shop = Shop::getContextShopID(true);
        }
        if ($id_shop_group === null) {
            $id_shop_group = Shop::getContextShopGroupID(true);
        }

        if (!is_array($values)) {
            $is_i18n = false;
            $values = array($values);
        } else {
            $is_i18n = true;
        }

        $result = true;
        foreach ($values as $lang => $value) {
            if ($value === Configuration::get($key, $lang, $id_shop_group, $id_shop)) {
                continue;
            }

            // If key already exists, update value
            if (Configuration::hasKey($key, $lang, $id_shop_group, $id_shop)) {
                if (!$lang) {
                    // Update config not linked to lang
                    $result &= Db::getInstance()->update('configuration', array(
                        'value' => $value,
                        'date_upd' => date('Y-m-d H:i:s'),
                    ), '`name` = \''.pSQL($key).'\''.PrestaHomeOptions::sqlRestriction($id_shop_group, $id_shop), 1, true);
                } else {
                    // Update multi lang
                    $sql = 'UPDATE '._DB_PREFIX_.'configuration_lang cl
                            SET cl.value = \''.$value.'\',
                                cl.date_upd = NOW()
                            WHERE cl.id_lang = '.(int)$lang.'
                                AND cl.id_configuration = (
                                    SELECT c.id_configuration
                                    FROM '._DB_PREFIX_.'configuration c
                                    WHERE c.name = \''.pSQL($key).'\''
                                        .PrestaHomeOptions::sqlRestriction($id_shop_group, $id_shop)
                                .')';
                    $result &= Db::getInstance()->execute($sql);
                }
            } else {
                // If key does not exists, create it
                if (!$configID = Configuration::getIdByName($key, $id_shop_group, $id_shop)) {
                    $newConfig = new Configuration();
                    $newConfig->name = $key;
                    if ($id_shop) {
                        $newConfig->id_shop = (int)$id_shop;
                    }
                    if ($id_shop_group) {
                        $newConfig->id_shop_group = (int)$id_shop_group;
                    }
                    if (!$lang) {
                        $newConfig->value = $value;
                    }
                    $result &= $newConfig->add(true, true);
                    $configID = $newConfig->id;
                }

                if ($lang) {
                    $result &= Db::getInstance()->insert('configuration_lang', array(
                        'id_configuration' =>   $configID,
                        'id_lang' =>            $lang,
                        'value' =>              $value,
                        'date_upd' =>           date('Y-m-d H:i:s'),
                    ));
                }
            }
        }

        Configuration::set($key, $values, $id_shop_group, $id_shop);

        return $result;
    }

    protected static function sqlRestriction($id_shop_group, $id_shop)
    {
        if ($id_shop) {
            return ' AND id_shop = '.(int)$id_shop;
        } elseif ($id_shop_group) {
            return ' AND id_shop_group = '.(int)$id_shop_group.' AND (id_shop IS NULL OR id_shop = 0)';
        } else {
            return ' AND (id_shop_group IS NULL OR id_shop_group = 0) AND (id_shop IS NULL OR id_shop = 0)';
        }
    }

    /**
     * [cleanJSON description]
     * @param  string
     * @return result
     */
    public static function cleanJSON($value)
    {
        return trim(preg_replace('/(\r\n)|\n|\r/', '', $value));
    }

    /**
     * Convert hex to rgb
     * @param  string $hex color in hex format
     * @return string rgb color format
     */
    public static function hex2rgb($hex)
    {
        $hex = str_replace("#", "", $hex);

        if (Tools::strlen($hex) == 3) {
            $r = hexdec(Tools::substr($hex, 0, 1).Tools::substr($hex, 0, 1));
            $g = hexdec(Tools::substr($hex, 1, 1).Tools::substr($hex, 1, 1));
            $b = hexdec(Tools::substr($hex, 2, 1).Tools::substr($hex, 2, 1));
        } else {
            $r = hexdec(Tools::substr($hex, 0, 2));
            $g = hexdec(Tools::substr($hex, 2, 2));
            $b = hexdec(Tools::substr($hex, 4, 2));
        }

        $rgb = array($r, $g, $b);
        return implode(",", $rgb);
    }
}
