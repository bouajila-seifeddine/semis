<?php

if (! defined('_PS_VERSION_')) {
    exit;
}

class AwesomeWatermark extends Module
{
    private $html = '';
    private $postErrors = array();
    private $imageTypes = array();
    
    private $configDefault = array();

    public function __construct()
    {
        $this->name = 'awesomewatermark';
        $this->tab = 'administration';
        $this->version = '1.0.2';
        $this->author = 'nexomedia';
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->module_key = '8649af4e7a8b262f024fdf305364e15b';

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Awesome Watermark and AJAX Regenerate', $this->name);
        $this->description = $this->l('Protect product photos with watermarks.', $this->name);
        $this->confirmUninstall = $this->l('Are you sure you want to delete your details?', $this->name);

        foreach (ImageType::getImagesTypes('products') as $type) {
            $this->imageTypes[] = $type;
            $this->configDefault[$type['name']] = [];
        }
    }

    public function install()
    {
        if (! parent::install() || ! $this->registerHook('watermark') || ! $this->registerHook('displayBackOfficeHeader')) {
            return false;
        }

        Configuration::updateValue('AWESOMEWATERMARK_HASH', Tools::passwdGen(10));
        Configuration::updateValue('AWESOMEWATERMARK_CONFIG', Tools::jsonEncode($this->configDefault));

        return true;
    }

    public function uninstall()
    {
        return (parent::uninstall() && Configuration::deleteByName('AWESOMEWATERMARK_CONFIG'));
    }

    public function hookDisplayBackOfficeHeader($params)
    {
        if (Tools::getValue('configure') != $this->name) {
            return false;
        }

        $this->context->controller->addJquery();
        $this->context->controller->addJqueryUI('ui.draggable');
        $this->context->controller->addJqueryUI('ui.resizable');
        $this->context->controller->addJS($this->_path.'views/js/admin.js');
        $this->context->controller->addCSS($this->_path.'views/css/admin.css');
    }

    public function getContent()
    {
        $id_shop = (int)$this->context->shop->id;
        if (Shop::getContext() != Shop::CONTEXT_SHOP) {
            $id_shop = '';
        }

        $_tpl = 'views/templates/admin/awesomewatermark.tpl';
        if (! empty($_REQUEST['awtab']) && $_REQUEST['awtab'] == 'regenerate') {
            $_tpl = 'views/templates/admin/awesomewatermark_regenerate.tpl';
        }

        // Form save
        if (Tools::isSubmit('submit'.$this->name)) {
            $config = array();
            
            $config['trim'] = array(
                'color' => Tools::strtoupper(trim(str_replace('#', '', Tools::getValue('trim_color')))),
                'threshold' => (float) trim(str_replace(',', '.', Tools::getValue('trim_threshold'))),
                'enabled' => (int) Tools::getValue('trim_enabled'),
            );

            $config['id_category_excluded'] = Tools::getValue('id_category_excluded');

            foreach ($this->imageTypes as $imageType) {
                if (isset($_FILES[$imageType['name'].'__file']['tmp_name']) && !empty($_FILES[$imageType['name'].'__file']['tmp_name'])) {
                    if (! ImageManager::isRealImage($_FILES[$imageType['name'].'__file']['tmp_name'], $_FILES[$imageType['name'].'__file']['type'], array('image/gif', 'image/png', 'image/jpg', 'image/jpeg'))) {
                        $this->postErrors[] = $this->l('Unknown image format', $this->name).' ('.$imageType['name'].').';
                    } else {
                        if ($error = ImageManager::validateUpload($_FILES[$imageType['name'].'__file'])) {
                            $this->postErrors[] = $error;
                        } elseif (! copy($_FILES[$imageType['name'].'__file']['tmp_name'], dirname(__FILE__).'/watermark'.$id_shop.'-'.$imageType['name'].'.png')) {
                            $this->postErrors[] = sprintf($this->l('An error occurred while uploading watermark: %1$s to %2$s', $this->name), $_FILES[$imageType['name'].'__file']['tmp_name'], dirname(__FILE__).'/watermark'.$id_shop.'-'.$imageType['name'].'.png');
                        }
                    }
                }

                if ((int) Tools::getValue($imageType['name'].'__remove') == 1) {
                    if (file_exists(dirname(__FILE__).'/watermark'.$id_shop.'-'.$imageType['name'].'.png')) {
                        if (! unlink(dirname(__FILE__).'/watermark'.$id_shop.'-'.$imageType['name'].'.png')) {
                            $this->postErrors[] = sprintf($this->l('An error occured while removing watermark file: %1$s', $this->name), dirname(__FILE__).'/watermark'.$id_shop.'-'.$imageType['name'].'.png');
                        }
                    }
                }

                if (file_exists(dirname(__FILE__).'/watermark'.$id_shop.'-'.$imageType['name'].'.png')) {
                    $config[$imageType['name']] = [];
                }

                if ((int) Tools::getValue($imageType['name'].'__coords_enabled') == 1) {
                    $config[$imageType['name']]['coords'] = array(
                        'x' => empty(Tools::getValue($imageType['name'].'__coords_x')) ? '' : (int) Tools::getValue($imageType['name'].'__coords_x'),
                        'y' => empty(Tools::getValue($imageType['name'].'__coords_y')) ? '' : (int) Tools::getValue($imageType['name'].'__coords_y'),
                        'w' => empty(Tools::getValue($imageType['name'].'__coords_w')) ? '' : (int) Tools::getValue($imageType['name'].'__coords_w'),
                        'h' => empty(Tools::getValue($imageType['name'].'__coords_h')) ? '' : (int) Tools::getValue($imageType['name'].'__coords_h'),
                        'enabled' => (int) Tools::getValue($imageType['name'].'__coords_enabled'),
                    );
                }

                if ((int) Tools::getValue($imageType['name'].'__padding')) {
                    $config[$imageType['name']]['padding'] = (int) Tools::getValue($imageType['name'].'__padding');
                }

                if (! empty(Tools::getValue($imageType['name'].'__background'))) {
                    $config[$imageType['name']]['background'] = Tools::strtoupper(trim(str_replace('#', '', Tools::getValue($imageType['name'].'__background'))));
                }

            }

            if (! count($this->postErrors)) {
                Configuration::updateValue('AWESOMEWATERMARK_CONFIG', Tools::jsonEncode($config));
                Tools::redirectAdmin('index.php?tab=AdminModules&configure='.$this->name.'&conf=6&token='.Tools::getAdminTokenLite('AdminModules'));
            } else {
                foreach ($this->postErrors as $err) {
                    $this->html .= $this->displayError($err);
                }
            }
        }

        $this->context->smarty->assign(array(
            'aw_link' => 'index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'aw_tab_settings' => 'index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'aw_tab_regenerate' => 'index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&awtab=regenerate',
            'awtab' => empty($_REQUEST['awtab']) ? '' : $_REQUEST['awtab'],
            'aw_l_settings' => $this->l('Settings', $this->name),
            'aw_l_regenerate' => $this->l('Regenerate', $this->name),
            'token' => Tools::getAdminTokenLite('AdminModules'),
            'aw_translations' => array(
                'reset' => $this->l('Reset', $this->name),
                'set_size_and_location' => $this->l('Set size and location', $this->name),
                'not_image' => $this->l('Selected file may not be image.', $this->name),
                'expand_collapse' => $this->l('Expand/Collapse', $this->name),
                'already_started' => $this->l('Already started!', $this->name),
                'id_image' => $this->l('ID Image', $this->name),
                'id_product' => $this->l('ID Product', $this->name),
                'result' => $this->l('Result', $this->name),
                'action' => $this->l('Action', $this->name),
                'correct_errors' => $this->l('Please correct errors', $this->name),
            )
        ));

        if (empty($_REQUEST['awtab'])) {
            $this->context->smarty->assign(array('content' => $this->html.$this->displayForm()));
        }

        return $this->display(__FILE__, $_tpl);
    }

    public function ajaxProcessGetFiles()
    {
        die(Tools::jsonEncode(Image::getAllImages()));
    }

    public function ajaxProcessRegenerateOne()
    {
        $response = array('result' => false);
        if (! empty($_REQUEST['id_image']) && ! empty($_REQUEST['id_product'])) {
            $result = Hook::exec('actionWatermark', array('id_image' => (int) Tools::getValue('id_image'), 'id_product' => (int) Tools::getValue('id_product')));
            $response['result'] = $result;
        }
        die(Tools::jsonEncode(array('response' => $response)));
    }

    public function hookwatermark($params)
    {
        $this->hookActionWatermark($params);
    }

    public function hookActionWatermark($params)
    {
        // Image metadata
        $image = new Image($params['id_image']);
        $image->id_product = $params['id_product'];

        // Setting paths
        $path_original = _PS_PROD_IMG_DIR_.$image->getExistingImgPath().'.jpg';

        $id_shop = (int)$this->context->shop->id;
        if (Shop::getContext() != Shop::CONTEXT_SHOP) {
            $id_shop = '';
        }
        
        if (! Configuration::get('AWESOMEWATERMARK_HASH')) {
            Configuration::updateValue('AWESOMEWATERMARK_HASH', Tools::passwdGen(10));
        }

        $config = Configuration::get('AWESOMEWATERMARK_CONFIG');
        if (! $config) {
            $config = $this->configDefault;
        } else {
            $config = Tools::jsonDecode($config, true);
        }

        $skip_wm = false;
        if (isset($config['id_category_excluded']) && is_array($config['id_category_excluded']) && count($config['id_category_excluded']) > 0) {
            $id_category = Db::getInstance()->getValue('SELECT id_category_default FROM '._DB_PREFIX_.'product WHERE id_product = '.pSQL($image->id_product), 0);

            if (in_array($id_category, $config['id_category_excluded'])) {
                $skip_wm = true;
            }
        }
        
        // Loading original image
        list($original_width, $original_height, $original_type) = getimagesize($path_original);
        $original = imagecreatefromstring(Tools::file_get_contents($path_original));
        if (! $original) {
            return false;
        }

        // Trim if necessary
        if (isset($config['trim']) && $config['trim']['enabled'] == 1) {
            $original = imagecropauto($original, IMG_CROP_THRESHOLD, $config['trim']['threshold'], hexdec($config['trim']['color']));
            $original_width = imagesx($original);
            $original_height = imagesy($original);
        }
        
        //$original_ratio = $original_width/$original_height;

        foreach ($this->imageTypes as $imageType) {
            // Load config, if not exists, skip watermarking
            $conf = isset($config[$imageType['name']]) ? $config[$imageType['name']] : false;
            if ($conf === false) {
                continue;
            }

            $conf['padding'] = empty($conf['padding']) ? 0 : (int)$conf['padding'];

            $wm = true;
            $path_watermark = dirname(__FILE__).'/watermark'.$id_shop.'-'.$imageType['name'].'.png';
            if (! file_exists($path_watermark)) {
                $wm = false;
            }

            $wm = $skip_wm ? false : $wm;

            if ($wm) {
                $watermark = imagecreatefromstring(Tools::file_get_contents($path_watermark));
                if (! $watermark) {
                    $wm = false;
                } else {
                    $watermark_width = imagesx($watermark);
                    $watermark_height = imagesy($watermark);
                }
            }

            // Calculating scales
            $scale_x = $original_width/((int)$imageType['width']-$conf['padding']*2);
            $scale_y = $original_height/((int)$imageType['height']-$conf['padding']*2);
            $scale = $scale_y > $scale_x ? $scale_y : $scale_x;
            $target_width = round($original_width * (1/$scale));
            $target_height = round($original_height * (1/$scale));

            if ($wm) {
                $scale_x_wm = $watermark_width/((int)$imageType['width']-$conf['padding']*2);
                $scale_y_wm = $watermark_height/((int)$imageType['height']-$conf['padding']*2);
                $scale_wm = $scale_y_wm > $scale_x_wm ? $scale_y_wm : $scale_x_wm;
                $target_width_wm = $watermark_width;
                $target_height_wm = $watermark_height;

                if ($watermark_width > (int)$imageType['width'] || $watermark_height > (int)$imageType['height']) {
                    $target_width_wm = round($watermark_width * (1/$scale_wm));
                    $target_height_wm = round($watermark_height * (1/$scale_wm));
                }

                // If coords are specified, ignore
                if (isset($conf['coords']) && $conf['coords']['enabled'] == 1) {
                    if ($conf['coords']['w'] !== '') {
                        $target_width_wm = (int) $conf['coords']['w'];
                    }
                    if ($conf['coords']['h'] !== '') {
                        $target_height_wm = (int) $conf['coords']['h'];
                    }
                }
            }

            // Create target image
            $img = imagecreatetruecolor((int)$imageType['width'], (int)$imageType['height']);

            // Fill with color
            if (isset($conf['background'])) {
                imagefill($img, 0, 0, hexdec($conf['background']));
            }

            // Resample
            imagecopyresampled(
                $img,
                $original,
                ((int)$imageType['width']-$target_width)/2,
                ((int)$imageType['height']-$target_height)/2,
                0,
                0,
                $target_width,
                $target_height,
                $original_width,
                $original_height
            );

            if ($wm) {
                $cx = ((int)$imageType['width']-$target_width_wm)/2;
                $cy = ((int)$imageType['height']-$target_height_wm)/2;
                if (isset($conf['coords']) && $conf['coords']['enabled'] == 1) {
                    if ($conf['coords']['x'] !== '') {
                        $cx = (int) $conf['coords']['x'];
                    }
                    if ($conf['coords']['y'] !== '') {
                        $cy = (int) $conf['coords']['y'];
                    }
                }
                imagecopyresampled(
                    $img,
                    $watermark,
                    $cx,
                    $cy,
                    0,
                    0,
                    $target_width_wm,
                    $target_height_wm,
                    $watermark_width,
                    $watermark_height
                );
            }

            $type = 'jpg';

            switch($original_type) {
                case IMAGETYPE_PNG:
                    $type = 'png';
                    break;
                case IMAGETYPE_GIF:
                    $type = 'gif';
                    break;
                default:
                case IMAGETYPE_JPEG:
                    $type = 'jpg';
                    break;
            }

            imagealphablending($img, false);
            imagesavealpha($img, true);

            ImageManager::write($type, $img, _PS_PROD_IMG_DIR_.$image->getExistingImgPath().'-'.Tools::stripslashes($imageType['name']).'.jpg');
        }

        return true;
    }

    public function displayForm()
    {
        foreach ($this->imageTypes as &$imageType) {
            $imageType['label'] = $imageType['name'].' ('.$imageType['width'].' x '.$imageType['height'].')';
        }

        if (Shop::getContext() == Shop::CONTEXT_SHOP) {
            $id_shop = (int)$this->context->shop->id;
        } else {
            $id_shop = '';
        }

        $data = Tools::jsonDecode(Configuration::get('AWESOMEWATERMARK_CONFIG'), true);
        $values = [];
        $data = is_array($data) ? $data : array();

        $tree = new HelperTreeCategories('categories-tree', $this->l('Select categories', $this->name));
        $tree->setInputName('id_category_excluded')
            ->setUseCheckBox(true)
            ->setRootCategory(Category::getRootCategory()->id)
            ->setSelectedCategories(isset($data['id_category_excluded']) && is_array($data['id_category_excluded']) ? $data['id_category_excluded'] : array());

        $html_tree = $tree->render();
        $html_tree .= '<div class="help_block">'.$this->l('Generation may become slower because of additional database query.', $this->name).'</div>';

        $fields_form = array();
        $fields_form[] = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('General settings', $this->name),
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'name' => "trim_enabled",
                        'label' => $this->l('Trim', $this->name),
                        'hint' => $this->l('Toggle on this option, select threshold between 0 and 1 eg. 0.6 and select color to automatically trim your photo.', $this->name),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled', $this->name)
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled', $this->name)
                            ),
                        )
                    ),
                    array(
                        'type' => 'color',
                        'name' => "trim_color",
                        'hint' => $this->l('Color to be trimmed out from edges of your photo. This and following option works best when you specify additional padding on each thumbnail size.', $this->name),
                        'label' => $this->l('Trim color', $this->name),
                    ),
                    array(
                        'type' => 'text',
                        'name' => "trim_threshold",
                        'hint' => $this->l('Threshold for color to be trimmed. Increase it if your photo is not trimmed at current value.', $this->name),
                        'label' => $this->l('Trim threshold', $this->name),
                        'suffix' => '0&hellip;1',
                        'class' => 'col-lg-2',
                    ),
                    array(
                        'type' => 'html',
                        'name' => "id_category_excluded",
                        'hint' => $this->l('Skip watermarking for product photos with following categories selected as default.', $this->name),
                        'label' => $this->l('Exclude categories from watermarking', $this->name),
                        'class' => 'col-lg-2',
                        'html_content' => $html_tree,
                    )
                )
            )
        );
        
        foreach ($this->imageTypes as $type) {
            $path_watermark = null;
            if (file_exists(dirname(__FILE__).'/watermark'.$id_shop.'-'.$type['name'].'.png')) {
                $path_watermark = '../modules/'.$this->name.'/watermark'.$id_shop.'-'.$type['name'].'.png?t='.rand(0, time());
            }
            
            $values[$type['name'].'__remove'] = 0;
            $values[$type['name'].'__background'] = isset($data[$type['name']]['background']) ? '#'.$data[$type['name']]['background'] : '';
            $values[$type['name'].'__padding'] = isset($data[$type['name']]['padding']) ? $data[$type['name']]['padding'] : 0;
            $values[$type['name'].'__coords_x'] = isset($data[$type['name']]['coords']['x']) ? $data[$type['name']]['coords']['x'] : '';
            $values[$type['name'].'__coords_y'] = isset($data[$type['name']]['coords']['y']) ? $data[$type['name']]['coords']['y'] : '';
            $values[$type['name'].'__coords_w'] = isset($data[$type['name']]['coords']['w']) ? $data[$type['name']]['coords']['w'] : '';
            $values[$type['name'].'__coords_h'] = isset($data[$type['name']]['coords']['h']) ? $data[$type['name']]['coords']['h'] : '';
            $values[$type['name'].'__coords_enabled'] = isset($data[$type['name']]['coords']['enabled']) ? (int) $data[$type['name']]['coords']['enabled'] : 0;

            $fields_form[] = array(
                'form' => array(
                    'legend' => array(
                        'title' => $type['label'],
                        'identifier' => $type['name'],
                        'width' => $type['width'],
                        'height' => $type['height']
                    ),
                    'input' => array(
                        array(
                            'type' => 'file',
                            'name' => $type['name']."__file",
                            'label' => '',
                            'desc' => sprintf($this->l('Upload watermark image (png, gif, jpg) for %s thumbnail size.', $this->name), '<b>'.$type['label'].'</b>'),
                            'thumb' => $path_watermark
                        ),
                        array(
                            'type' => 'switch',
                            'name' => $type['name']."__remove",
                            'label' => $this->l('Remove watermark file', $this->name),
                            'desc' => $this->l('Select this field only if you want to remove watermark file.', $this->name),
                            'hint' => $this->l("This option removes watermark file. Watermark won't be applied.", $this->name),
                            'thumb' => $path_watermark,
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('Enabled', $this->name)
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Disabled', $this->name)
                                ),
                            )
                        ),
                        array(
                            'type' => 'color',
                            'name' => $type['name']."__background",
                            'hint' => $this->l('Select color for thumbnail to be filled in when resized photo does not match exact size. This is also background of padding when specified below. Leave empty and image will be not filled by default.', $this->name),
                            'label' => $this->l('Background fill color', $this->name),
                        ),
                        array(
                            'type' => 'text',
                            'name' => $type['name']."__padding",
                            'hint' => $this->l('Add extra padding (in pixels) on each side of image.', $this->name),
                            'label' => $this->l('Padding', $this->name),
                            'suffix' => 'px',
                            'class' => 'col-lg-2',
                        ),
                        array(
                            'type' => 'switch',
                            'name' => $type['name']."__coords_enabled",
                            'hint' => $this->l('Toggle ON this option if you want to customize exact size and placement for watermark on photo. Otherwise, watermark will be centered horizontally and vertically.', $this->name),
                            'label' => $this->l('Custom size and location', $this->name),
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('Enabled', $this->name)
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Disabled', $this->name)
                                ),
                            )
                        ),
                        array(
                            'type' => 'text',
                            'name' => $type['name']."__coords_x",
                            'hint' => $this->l('Set X (in pixels) of watermark top left corner.', $this->name),
                            'label' => $this->l('Left', $this->name),
                            'suffix' => 'px',
                            'class' => 'col-lg-2',
                        ),
                        array(
                            'type' => 'text',
                            'name' => $type['name']."__coords_y",
                            'hint' => $this->l('Set Y (in pixels) of watermark top left corner.', $this->name),
                            'label' => $this->l('Top', $this->name),
                            'suffix' => 'px',
                            'class' => 'col-lg-2',
                        ),
                        array(
                            'type' => 'text',
                            'name' => $type['name']."__coords_w",
                            'hint' => $this->l('Set width (in pixels) of watermark.', $this->name),
                            'label' => $this->l('Width', $this->name),
                            'suffix' => 'px',
                            'class' => 'col-lg-2',
                        ),
                        array(
                            'type' => 'text',
                            'name' => $type['name']."__coords_h",
                            'hint' => $this->l('Set height (in pixels) of watermark.', $this->name),
                            'label' => $this->l('Height', $this->name),
                            'suffix' => 'px',
                            'class' => 'col-lg-2',
                        )
                    )
                )
            );
        }

        $fields_form[]['form'] = array(
            'legend' => array(
                'title' => $this->l('Save changes', $this->name),
            ),
            'submit' => array(
                'title' => $this->l('Save', $this->name),
                'class' => 'button btn btn-default pull-right'
            )
        );

        $values['trim_enabled'] = isset($data['trim']['enabled']) ? (int) $data['trim']['enabled'] : 0;
        $values['trim_color'] = isset($data['trim']['color']) ? '#'.$data['trim']['color'] : '';
        $values['trim_threshold'] = isset($data['trim']['threshold']) ? $data['trim']['threshold'] : '';

        $helper = new HelperForm();
        $helper->submit_action = 'submit'.$this->name;
        $helper->module = $this;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->tpl_vars = array(
            'uri' => $this->getPathUri(),
            'fields_value' => $values,
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm($fields_form);
    }

    // Original Watermark functions
    public function getAdminDir()
    {
        $admin_dir = str_replace('\\', '/', _PS_ADMIN_DIR_);
        $admin_dir = explode('/', $admin_dir);
        $len = count($admin_dir);
        return $len > 1 ? $admin_dir[$len - 1] : _PS_ADMIN_DIR_;
    }
}
