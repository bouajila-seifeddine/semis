<?php
/**
 * @author    Jamoliddin Nasriddinov <jamolsoft@gmail.com>
 * @copyright (c) 2016, Jamoliddin Nasriddinov
 * @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
 */

require_once('initialize.php');

/**
 * Main class of the module
 */
class ElegantalTinyPngImageCompress extends ElegantalTinyPngImageCompressModule
{

    /**
     * ID of this module as product on addons
     * @var int
     */
    protected $productIdOnAddons = 22488;

    /**
     * List of hooks to register
     * @var array
     */
    protected $hooksToRegister = array(
        'actionProductSave',
    );

    /**
     * List of module settings to be saved as Configuration record
     * @var array
     */
    protected $settings = array(
        'api_key' => '',
        'compress_original_images' => 1,
        'compress_generated_images' => 1,
        'image_formats_to_compress' => '',
        'analyze_per_request' => 100, // Number of images to analyze per ajax request
        'cron_secure_key' => '',
        'cron_compress_per_request' => 5,
        'cron_compress_image_groups' => array(),
        'cron_compress_custom_dirs' => '',
        'cron_analyzed_image_groups' => array(),
        'cron_last_error' => '',
    );

    /**
     * Current model object being edited on back-office
     */
    private $model = null;

    /**
     * Attributes of current model object being edited on back-office
     */
    private $modelAttrs = null;

    /**
     * Constructor method called on each newly-created object
     */
    public function __construct()
    {
        $this->name = 'elegantaltinypngimagecompress';
        $this->tab = 'administration';
        $this->version = '5.4.2';
        $this->author = 'Elegantal';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->module_key = '0d283326323f534701e0c4987a92716a';

        parent::__construct();

        $this->displayName = $this->l('Image Compressor With TinyPNG');
        $this->description = $this->l('Compress JPG and PNG images in your store with TinyPNG, reduce page size of your store, make your store load much more faster and save a lot of disk space.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    /**
     * This function plays controller role for the back-office page of the module
     * @return html
     */
    public function getContent()
    {
        $this->setTimeLimit();

        if (_PS_VERSION_ < '1.6') {
            $this->context->controller->addCSS($this->_path . 'views/css/elegantaltinypngimagecompress-bootstrap.css', 'all');
            $this->context->controller->addCSS($this->_path . 'views/css/font-awesome.css', 'all');

            if (!in_array(Tools::getValue('event'), array('settings', 'editSettings'))) {
                $this->context->controller->addJS($this->_path . 'views/js/jquery-1.11.0.min.js');
                $this->context->controller->addJS($this->_path . 'views/js/bootstrap.js');
            }
        }

        $this->context->controller->addCSS($this->_path . 'views/css/elegantaltinypngimagecompress-back.css', 'all');
        $this->context->controller->addJS($this->_path . 'views/js/elegantaltinypngimagecompress-back.js');

        $this->initModel();

        $html = $this->getRedirectAlerts();

        if ($event = Tools::getValue('event')) {
            switch ($event) {
                case 'settings':
                    $html .= $this->settings();
                    break;
                case 'editSettings':
                    $html .= $this->editSettings();
                    break;
                case 'viewCron':
                    $html .= $this->viewCron();
                    break;
                case 'analyze':
                    $html .= $this->analyze();
                    break;
                case 'compress':
                    $html .= $this->compress();
                    break;
                case 'customDir':
                    $html .= $this->customDir();
                    break;
                case 'imagesLog':
                    $html .= $this->imagesLog();
                    break;
                case 'tinify':
                    $this->tinify();
                    break;
                default:
                    $html .= $this->history();
                    break;
            }
        } else {
            $html .= $this->history();
        }

        return $html;
    }

    /**
     * Initializes current model object and its attributes
     */
    protected function initModel()
    {
        $model = null;
        $modelId = Tools::getValue('id_elegantaltinypngimagecompress');

        if ($modelId) {
            $model = ElegantalTinyPngImageCompressClass::model()->loadObjectByPk($modelId);
            if ($model) {
                $this->model = $model;
                $this->modelAttrs = $model->getAttributes();
            } else {
                $this->setRedirectAlert($this->l('Record not found.'), 'error');
                $this->redirectAdmin();
            }
        }
    }

    /**
     * Renders list of compressions history
     * @return string HTML
     */
    protected function history()
    {
        // Pagination data
        $allModels = ElegantalTinyPngImageCompressClass::model()->findAllWithImages();
        $total = count($allModels);
        $limit = 10;
        $pages = ceil($total / $limit);
        $currentPage = Tools::getValue('page') ? (int) Tools::getValue('page') : 1;
        if ($currentPage > $pages) {
            $currentPage = $pages;
        }
        $halfVisibleLinks = 5;
        $offset = ($total > $limit) ? ($currentPage - 1) * $limit : 0;

        // Sorting records
        $sortableColumns = array(
            'image_group',
            'created_at',
            'images_count',
            'compressed',
            'not_compressed',
            'failed',
            'images_size_before',
            'images_size_after',
            'disk_space_saved',
            'status'
        );
        $orderBy = (in_array(Tools::getValue('orderBy'), $sortableColumns)) ? Tools::getValue('orderBy') : 'id_elegantaltinypngimagecompress';
        $orderType = (Tools::getValue('orderType') == 'asc') ? 'asc' : 'desc';

        $models = ElegantalTinyPngImageCompressClass::model()->findAllWithImages($offset, $limit, $orderBy, $orderType);

        $total_images = 0;
        $total_compressed = 0;
        $total_not_compressed = 0;
        $total_failed = 0;
        $total_size_before = 0;
        $total_size_after = 0;
        $total_disk_saved = 0;

        foreach ($models as &$model) {
            $total_images += (int) $model['images_count'];
            $total_compressed += (int) $model['compressed'];
            $total_not_compressed += (int) $model['not_compressed'];
            $total_failed += (int) $model['failed'];
            $total_size_before += (int) $model['images_size_before'];
            $total_size_after += (int) $model['images_size_after'];
            $total_disk_saved += ($model['images_size_before'] > $model['images_size_after']) ? ($model['images_size_before'] - $model['images_size_after']) : 0;

            $model['disk_space_saved'] = ($model['images_size_before'] > $model['images_size_after']) ? ElegantalTinyPngImageCompressTools::displaySize($model['images_size_before'] - $model['images_size_after']) : ElegantalTinyPngImageCompressTools::displaySize(0);
            $model['images_size_before'] = ElegantalTinyPngImageCompressTools::displaySize($model['images_size_before']);
            $model['images_size_after'] = ElegantalTinyPngImageCompressTools::displaySize($model['images_size_after']);
        }

        $api_key = $this->getSetting('api_key');
        $apiCompressionsCount = $api_key ? ElegantalTinyPngImageCompressImagesClass::getTinifyCompressionsCount($api_key) : '';

        $is_readme_read = false;
        if (!isset($_COOKIE[$this->name])) {
            setcookie($this->name, 1, time() + (86400 * 365), "/");
        } else {
            $is_readme_read = true;
        }

        $this->context->smarty->assign(
            array(
                'adminUrl' => $this->getAdminUrl(),
                'documentationUrls' => $this->getDocumentationUrls(),
                'contactDeveloperUrl' => $this->getContactDeveloperUrl(),
                'rateModuleUrl' => $this->getRateModuleUrl(),
                'models' => $models,
                'pages' => $pages,
                'currentPage' => $currentPage,
                'halfVisibleLinks' => $halfVisibleLinks,
                'orderBy' => $orderBy,
                'orderType' => $orderType,
                'status_analyzing' => ElegantalTinyPngImageCompressClass::$STATUS_ANALYZING,
                'status_compressing' => ElegantalTinyPngImageCompressClass::$STATUS_COMPRESSING,
                'status_completed' => ElegantalTinyPngImageCompressClass::$STATUS_COMPLETED,
                'imageGroups' => ElegantalTinyPngImageCompressClass::$imageGroups,
                'is_readme_read' => $is_readme_read,
                'apiCompressionsCount' => $apiCompressionsCount,
                'total_images' => $total_images,
                'total_compressed' => $total_compressed,
                'total_not_compressed' => $total_not_compressed,
                'total_failed' => $total_failed,
                'total_size_before' => ElegantalTinyPngImageCompressTools::displaySize($total_size_before),
                'total_size_after' => ElegantalTinyPngImageCompressTools::displaySize($total_size_after),
                'total_disk_saved' => ElegantalTinyPngImageCompressTools::displaySize($total_disk_saved),
                'cron_last_error' => $this->getSetting('cron_last_error'),
            )
        );

        return $this->display(__FILE__, 'views/templates/admin/list.tpl');
    }

    /**
     * Action function to manage settings. Renders and processes settings form
     * @return html
     */
    protected function editSettings()
    {
        $html = "";

        // Process Form
        if ($this->isPostRequest()) {
            $errors = array();

            if (Tools::getValue('api_key')) {
                $this->setSetting('api_key', Tools::getValue('api_key'));
                //Validate API KEY
                try {
                    ElegantalTinyPngImageCompressImagesClass::requireTinify();
                    ElegantalTinyPngImageCompressTinify::setKey(Tools::getValue('api_key'));
                    ElegantalTinyPngImageCompressTinify::validate();
                } catch (ElegantalTinyPngImageCompressException $e) {
                    $errors[] = 'Verify your API key. ' . $e->getMessage();
                }
            } else {
                $errors[] = $this->l('API key is not valid.');
            }

            if (Tools::getValue('compress_original_images') || Tools::getValue('compress_generated_images')) {
                if (Tools::getValue('compress_original_images')) {
                    $this->setSetting('compress_original_images', 1);
                } else {
                    $this->setSetting('compress_original_images', 0);
                }
                if (Tools::getValue('compress_generated_images')) {
                    $this->setSetting('compress_generated_images', 1);
                } else {
                    $this->setSetting('compress_generated_images', 0);
                }
                if (Tools::getValue('image_formats_to_compress')) {
                    $this->setSetting('image_formats_to_compress', ElegantalTinyPngImageCompressTools::serialize(Tools::getValue('image_formats_to_compress')));
                }
                if (Tools::getValue('cron_compress_image_groups')) {
                    $this->setSetting('cron_compress_image_groups', ElegantalTinyPngImageCompressTools::serialize(Tools::getValue('cron_compress_image_groups')));
                }
                if (Tools::getValue('cron_compress_custom_dirs')) {
                    $paths = explode(';', Tools::getValue('cron_compress_custom_dirs'));
                    $is_paths_valid = true;
                    foreach ($paths as $path) {
                        $realpath = (Tools::substr($path, 0, 1) == '/') ? realpath($path) : realpath(_PS_ROOT_DIR_ . '/' . $path);
                        if (!$realpath || !is_dir($realpath)) {
                            $errors[] = $this->l('Invalid directory path:') . ' ' . $path;
                            $is_paths_valid = false;
                        }
                    }
                    if ($is_paths_valid) {
                        $this->setSetting('cron_compress_custom_dirs', Tools::getValue('cron_compress_custom_dirs'));
                    }
                }
            } else {
                $errors[] = $this->l('Please choose to compress either original images or generated images or both.');
            }

            if (empty($errors)) {
                $this->setRedirectAlert($this->l('Settings saved successfully.'), 'success');
                if (Tools::isSubmit('submitAndStay')) {
                    $this->redirectAdmin(array(
                        'event' => 'editSettings',
                    ));
                } else {
                    $this->redirectAdmin();
                }
            } else {
                $html .= $this->displayError(implode('<br>', $errors));
            }
        }

        // Render Form
        $fields_value = $this->getSettings();
        $fields_value['cron_compress_image_groups[]'] = ElegantalTinyPngImageCompressTools::unserialize($fields_value['cron_compress_image_groups']);
        $fields_value['image_formats_to_compress[]'] = ElegantalTinyPngImageCompressTools::unserialize($fields_value['image_formats_to_compress']);
        if (empty($fields_value['image_formats_to_compress[]'])) {
            $fields_value['image_formats_to_compress[]'] = array('all');
        }

        $switch = 'switch';
        if (_PS_VERSION_ < '1.6') {
            $switch = 'el_switch';
        }

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Edit Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('TinyPNG API Key'),
                        'name' => 'api_key',
                        'hint' => $this->l('Enter your TinyPNG API key. You can get your API key on https://tinypng.com/developers'),
                        'desc' => $this->l('Enter your TinyPNG API key. You can get your API key on https://tinypng.com/developers'),
                    ),
                    array(
                        'type' => $switch,
                        'label' => $this->l('Compress Original Images'),
                        'name' => 'compress_original_images',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'compress_original_images_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'compress_original_images_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        ),
                        'hint' => $this->l('Original images will be compressed'),
                        'desc' => $this->l('Original images will be compressed'),
                    ),
                    array(
                        'type' => $switch,
                        'label' => $this->l('Compress Prestashop Generated Images'),
                        'name' => 'compress_generated_images',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'compress_generated_images_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'compress_generated_images_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        ),
                        'hint' => $this->l('Regenerated thumbnails will be compressed'),
                        'desc' => $this->l('Regenerated thumbnails will be compressed'),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Product Image Formats To Compress'),
                        'name' => 'image_formats_to_compress[]',
                        'multiple' => true,
                        'options' => array(
                            'query' => $this->getProductImageTypesForSelect(),
                            'id' => 'key',
                            'name' => 'value'
                        ),
                        'hint' => $this->l('Select product image formats that you want to compress'),
                        'desc' => $this->l('Select product image formats that you want to compress'),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Image groups to compress by CRON'),
                        'name' => 'cron_compress_image_groups[]',
                        'multiple' => true,
                        'options' => array(
                            'query' => $this->getImageGroupsForSelect(),
                            'id' => 'key',
                            'name' => 'value'
                        ),
                        'hint' => $this->l('Select image groups that you want to compress by CRON'),
                        'desc' => $this->l('Select image groups that you want to compress by CRON'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Custom directory to compress by CRON'),
                        'name' => 'cron_compress_custom_dirs',
                        'hint' => $this->l('Enter custom directory that you want to compress by CRON. You can enter multiple directories separated by semicolon.'),
                        'desc' => $this->l('Enter custom directory that you want to compress by CRON. You can enter multiple directories separated by semicolon. You may enter ABSOLUTE path which must start with "/" such as /www/prestashop/example/ or RELATIVE path to root folder of your store like themes/example/img/'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
                'buttons' => array(
                    array(
                        'href' => $this->getAdminUrl(),
                        'title' => $this->l('Back'),
                        'icon' => 'process-icon-back'
                    ),
                    array(
                        'title' => $this->l('Save and stay'),
                        'name' => 'submitAndStay',
                        'type' => 'submit',
                        'class' => 'pull-right',
                        'icon' => 'process-icon-save'
                    ),
                )
            )
        );

        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->submit_action = 'editSettings';
        $helper->name_controller = 'elegantalBootstrapWrapper';
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->getAdminUrl(array('event' => 'editSettings'));
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => array(
                'id_lang' => $lang->id,
                'iso_code' => $lang->iso_code
            ),
            'fields_value' => $fields_value,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $html . $helper->generateForm(array($fields_form));
    }

    /**
     * Returns list of product image types
     * @return array
     */
    protected function getProductImageTypesForSelect()
    {
        $result = array(array('key' => 'all', 'value' => $this->l('ALL FORMATS')));
        $imageTypes = ImageType::getImagesTypes();
        foreach ($imageTypes as $imageType) {
            if ($imageType['products'] == 1) {
                $result[] = array('key' => $imageType['name'], 'value' => $imageType['name']);
            }
        }
        return $result;
    }

    /**
     * Returns list of image groups for select
     * @return array
     */
    protected function getImageGroupsForSelect()
    {
        $result = array();
        $imageGroups = ElegantalTinyPngImageCompressClass::$imageGroups;
        foreach ($imageGroups as $imageGroup) {
            $result[] = array('key' => $imageGroup, 'value' => Tools::ucfirst($imageGroup));
        }
        return $result;
    }

    /**
     * Function to get custom directory from user
     * @return string HTML
     */
    protected function customDir()
    {
        $html = "";
        $path = null;

        if ($this->isPostRequest()) {
            if (Tools::getValue('custom_dir')) {
                $path = Tools::getValue('custom_dir');
                if (Tools::substr($path, 0, 1) == '/') {
                    $path = realpath($path);
                } else {
                    $path = realpath(_PS_ROOT_DIR_ . '/' . $path);
                }
            }

            if ($path && is_dir($path)) {
                $this->redirectAdmin(array('event' => 'analyze', 'image_group' => 'custom', 'custom_dir' => $path));
            } else {
                $html .= $this->displayError($this->l('Invalid directory path.'));
            }
        }

        $this->context->smarty->assign(
            array(
                'adminUrl' => $this->getAdminUrl(),
            )
        );

        return $html . $this->display(__FILE__, 'views/templates/admin/customDir.tpl');
    }

    /**
     * Renders log of images compressed
     * @return string HTML
     */
    protected function imagesLog()
    {
        $total = ElegantalTinyPngImageCompressImagesClass::model()->countAll();
        $limit = 20;
        $pages = ceil($total / $limit);
        $currentPage = Tools::getValue('page') ? (int) Tools::getValue('page') : 1;
        if ($currentPage > $pages) {
            $currentPage = $pages;
        }
        $halfVisibleLinks = 5;
        $offset = ($total > $limit) ? ($currentPage - 1) * $limit : 0;

        // Sorting records
        $sortableColumns = array(
            'image_path',
            'image_size_before',
            'image_size_after',
            'status',
        );
        $orderBy = (in_array(Tools::getValue('orderBy'), $sortableColumns)) ? Tools::getValue('orderBy') : 'id_elegantaltinypngimagecompress_images';
        $orderType = (Tools::getValue('orderType') == 'asc') ? 'asc' : 'desc';

        $images = ElegantalTinyPngImageCompressImagesClass::model()->findAll(array(
            'limit' => $limit,
            'offset' => $offset,
            'order' => $orderBy . ' ' . $orderType
        ));

        foreach ($images as &$image) {
            $image['image_size_before'] = ElegantalTinyPngImageCompressTools::displaySize($image['image_size_before']);
            $image['image_size_after'] = ElegantalTinyPngImageCompressTools::displaySize($image['image_size_after']);
        }

        $this->context->smarty->assign(
            array(
                'adminUrl' => $this->getAdminUrl(),
                'images' => $images,
                'pages' => $pages,
                'currentPage' => $currentPage,
                'halfVisibleLinks' => $halfVisibleLinks,
                'orderBy' => $orderBy,
                'orderType' => $orderType,
                'status_not_compressed' => ElegantalTinyPngImageCompressImagesClass::$STATUS_NOT_COMPRESSED,
                'status_compressed' => ElegantalTinyPngImageCompressImagesClass::$STATUS_COMPRESSED,
                'status_failed' => ElegantalTinyPngImageCompressImagesClass::$STATUS_FAILED,
            )
        );

        return $this->display(__FILE__, 'views/templates/admin/imagesLog.tpl');
    }

    /**
     * Renders form with options and analyzes images for compression. POST request handled as AJAX
     * @return string HTML
     */
    protected function analyze()
    {
        if ($this->isPostRequest()) {
            $result = array();

            if ($this->model) {
                $offset = Tools::getValue('offset');
                $limit = Tools::getValue('limit');

                if ($this->model->collectImages($offset, $limit)) {
                    $result['success'] = true;
                } else {
                    $result['success'] = false;
                    $result['message'] = $this->l('There was a problem analyzing images. Please try again later.');
                }
            } else {
                $result['success'] = false;
                $result['message'] = $this->l('Record not found.');
            }

            die(Tools::jsonEncode($result));
        } else {
            if ($this->model && $this->model->status == ElegantalTinyPngImageCompressClass::$STATUS_ANALYZING) {
                $model = $this->model;

                // Previous analyze did not finish. So delete images of this model and start analyze from beginning
                $model->deleteImages();

                $model->compress_original_images = (int) $this->getSetting('compress_original_images');
                $model->compress_generated_images = (int) $this->getSetting('compress_generated_images');
                $model->image_formats_to_compress = $this->getSetting('image_formats_to_compress');
                $model->images_count = 0;
                $model->images_size_before = 0;
                $model->images_size_after = 0;
                $model->update();
            } else {
                $image_group = Tools::getValue('image_group');
                if (!$image_group || !in_array($image_group, ElegantalTinyPngImageCompressClass::$imageGroups)) {
                    $this->redirectAdmin();
                }

                $model = new ElegantalTinyPngImageCompressClass();
                $model->image_group = $image_group;
                $model->compress_original_images = (int) $this->getSetting('compress_original_images');
                $model->compress_generated_images = (int) $this->getSetting('compress_generated_images');
                $model->image_formats_to_compress = $this->getSetting('image_formats_to_compress');
                $model->images_count = 0;
                $model->images_size_before = 0;
                $model->images_size_after = 0;
                $model->status = ElegantalTinyPngImageCompressClass::$STATUS_ANALYZING;

                if (Tools::getValue('custom_dir')) {
                    if ($customDir = realpath(Tools::getValue('custom_dir'))) {
                        $model->custom_dir = $customDir;
                    } else {
                        $this->redirectAdmin();
                    }
                }

                $model->add();
            }

            $totalImageIds = $model->getImageIdsByImageGroup();
            $total = (!empty($totalImageIds) && is_array($totalImageIds)) ? count($totalImageIds) : 0;
            $offset = 0;
            $limit = (int) $this->getSetting('analyze_per_request');
            $numberOfRequests = 1;

            if ($total && $total > $offset && $total > $limit) {
                $numberOfRequests = ceil(($total - $offset) / $limit);
            }

            $this->context->smarty->assign(
                array(
                    'id_elegantaltinypngimagecompress' => $model->id,
                    'image_group' => $model->image_group,
                    'total' => $total,
                    'offset' => $offset,
                    'limit' => $limit,
                    'requests' => $numberOfRequests,
                    'adminUrl' => $this->getAdminUrl(),
                    'compressUrl' => $this->getAdminUrl(array('event' => 'compress', 'id_elegantaltinypngimagecompress' => $model->id)),
                )
            );

            return $this->display(__FILE__, 'views/templates/admin/analyze.tpl');
        }
    }

    /**
     * Renders compression page.
     * @return string HTML
     */
    protected function compress()
    {
        if (!$this->model) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin();
        }

        if ($this->model->status == ElegantalTinyPngImageCompressClass::$STATUS_COMPLETED) {
            $this->setRedirectAlert($this->l('Compression completed successfully.'), 'success');
            $this->redirectAdmin();
        }

        if ($this->model->images_count == 0) {
            $this->model->delete();
            $this->setRedirectAlert($this->l('No images found to compress OR all images in this group were already processed.'), 'success');
            $this->redirectAdmin();
        }

        $this->model->status = ElegantalTinyPngImageCompressClass::$STATUS_COMPRESSING;
        if (!$this->model->update()) {
            $this->setRedirectAlert($this->l('Could not update status.'), 'success');
            $this->redirectAdmin();
        }

        $api_key = $this->getSetting('api_key');
        if (!$api_key) {
            $this->setRedirectAlert($this->l('Enter your TinyPNG API key. You can get your API key on https://tinypng.com/developers'), 'error');
            $this->redirectAdmin(array('event' => 'editSettings'));
        }

        $modelAttrs = $this->model->findByPkWithImages($this->model->id);
        if (!$modelAttrs) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin();
        }

        $modelAttrs['images_size_before'] = ElegantalTinyPngImageCompressTools::displaySize($modelAttrs['images_size_before']);
        $modelAttrs['images_size_after'] = ElegantalTinyPngImageCompressTools::displaySize($modelAttrs['images_size_after']);

        // Progress bar
        $total = $modelAttrs['images_count'];
        $processed = $total - $modelAttrs['not_compressed'];
        $progress = ($processed * 100) / $total;
        $progressTxt = round($progress);
        if ($progress < 1) {
            $progressTxt = round($progress, 2);
        }

        $this->context->smarty->assign(
            array(
                'adminUrl' => $this->getAdminUrl(),
                'model' => $modelAttrs,
                'progress' => $progress,
                'progressTxt' => $progressTxt,
            )
        );

        return $this->display(__FILE__, 'views/templates/admin/compress.tpl');
    }

    /**
     * Ajax function to compress image using TinyPNG
     */
    protected function tinify()
    {
        $result = array();

        if (!$this->model) {
            $result['success'] = false;
            $result['next'] = 0;
            $result['message'] = $this->l('Record not found.');
            $result['redirect'] = $this->getAdminUrl();
            die(Tools::jsonEncode($result));
        }

        $api_key = $this->getSetting('api_key');
        if (!$api_key) {
            $result['success'] = false;
            $result['next'] = 0;
            $result['message'] = $this->l('API key is not valid.');
            $result['redirect'] = $this->getAdminUrl(array('event' => 'editSettings'));
            die(Tools::jsonEncode($result));
        }

        $imageModelArr = ElegantalTinyPngImageCompressImagesClass::model()->find(array(
            'condition' => array(
                'id_elegantaltinypngimagecompress' => $this->model->id,
                'status' => ElegantalTinyPngImageCompressImagesClass::$STATUS_NOT_COMPRESSED
            )
        ));

        if ($imageModelArr) {
            $result['next'] = 1;
            $imageModel = ElegantalTinyPngImageCompressImagesClass::model()->loadObjectByPk($imageModelArr['id_elegantaltinypngimagecompress_images']);
            if ($imageModel) {
                $compressResult = $imageModel->compress($api_key);
                if ($compressResult === true) {
                    $this->model->images_size_after -= $imageModel->image_size_before - $imageModel->image_size_after;
                    $result['success'] = true;
                } elseif ($compressResult === false) {
                    $result['success'] = false;
                } else {
                    $result['success'] = false;
                    $result['message'] = $compressResult;
                    $result['next'] = 0;
                    $result['redirect'] = $this->getAdminUrl();
                }
            } else {
                $result['success'] = false;
            }
        } else {
            $this->model->status = ElegantalTinyPngImageCompressClass::$STATUS_COMPLETED;
            $result['success'] = false;
            $result['next'] = 0;
        }

        $this->model->update();

        $result['imagesSizeAfter'] = ElegantalTinyPngImageCompressTools::displaySize($this->model->images_size_after);
        $result['sizeSaved'] = ElegantalTinyPngImageCompressTools::displaySize($this->model->images_size_before - $this->model->images_size_after);

        die(Tools::jsonEncode($result));
    }

    /**
     * Hook action called when product is saved: both add() and update()
     * @param array $params
     */
    public function hookActionProductSave($params)
    {
        $id_product = null;

        if (isset($params['id_product']) && $params['id_product']) {
            $id_product = $params['id_product'];
        } elseif (isset($params['product']) && $params['product']->id) {
            $id_product = $params['product']->id;
        }

        $cron_compress_image_groups = ElegantalTinyPngImageCompressTools::unserialize($this->getSetting('cron_compress_image_groups'));

        if ($id_product && in_array('product', $cron_compress_image_groups)) {
            $model = new ElegantalTinyPngImageCompressClass();
            $model->image_group = 'product';
            $model->compress_original_images = (int) $this->getSetting('compress_original_images');
            $model->compress_generated_images = (int) $this->getSetting('compress_generated_images');
            $model->image_formats_to_compress = $this->getSetting('image_formats_to_compress');

            $model->images_count = 0;
            $model->images_size_before = 0;
            $model->images_size_after = 0;
            $model->status = ElegantalTinyPngImageCompressClass::$STATUS_ANALYZING;
            if ($model->add()) {
                $model->status = ElegantalTinyPngImageCompressClass::$STATUS_COMPRESSING;
                $model->collectProductImages($id_product);
                if (!$model->images_count) {
                    $model->delete();
                }
            }
        }
    }

    /**
     * Renders CRON details
     * @return string HTML
     */
    protected function viewCron()
    {
        $this->context->smarty->assign(
            array(
                'adminUrl' => $this->getAdminUrl(),
                'cronUrl' => $this->getCronUrl(),
            )
        );

        return $this->display($this->name, 'views/templates/admin/cron.tpl');
    }

    /**
     * Returns URL for CRON
     * @return string URL
     */
    protected function getCronUrl($params = array())
    {
        $secure_key = $this->getSetting('cron_secure_key');
        $params['secure_key'] = $secure_key;

        $id_shop = $this->context->shop->id;
        $id_lang = $this->context->language->id;

        return $this->context->link->getModuleLink($this->name, 'cron', $params, null, $id_lang, $id_shop);
    }

    /**
     * Action to execute module's CRON job
     */
    public function executeCron()
    {
        $secure_key = $this->getSetting('cron_secure_key');
        $api_key = $this->getSetting('api_key');

        if ($secure_key && $api_key && Tools::getValue('secure_key') == $secure_key) {
            $this->setSetting('cron_last_error', '');

            $limit = $this->getSetting('cron_compress_per_request');

            $modelAttr = ElegantalTinyPngImageCompressClass::model()->find(array(
                'condition' => array(
                    'status' => ElegantalTinyPngImageCompressClass::$STATUS_COMPRESSING
                ),
                'order' => 'id_elegantaltinypngimagecompress'
            ));

            if ($modelAttr && $modelAttr['id_elegantaltinypngimagecompress']) {
                $model = ElegantalTinyPngImageCompressClass::model()->loadObjectByPk($modelAttr['id_elegantaltinypngimagecompress']);
                if ($model->id) {
                    $imageModels = ElegantalTinyPngImageCompressImagesClass::model()->findAll(array(
                        'condition' => array(
                            'id_elegantaltinypngimagecompress' => $model->id,
                            'status' => ElegantalTinyPngImageCompressImagesClass::$STATUS_NOT_COMPRESSED
                        ),
                        'order' => 'id_elegantaltinypngimagecompress_images',
                        'limit' => $limit,
                    ));

                    if ($imageModels) {
                        foreach ($imageModels as $imageModelAttr) {
                            $imageModel = ElegantalTinyPngImageCompressImagesClass::model()->loadObjectByPk($imageModelAttr['id_elegantaltinypngimagecompress_images']);
                            $compressResult = $imageModel->compress($api_key);
                            if ($compressResult === true) {
                                // Compressed successfully
                                $model->images_size_after -= $imageModel->image_size_before - $imageModel->image_size_after;
                            } elseif ($compressResult === false) {
                                // This particular image failed but it is OK to continue
                                // We don't have to do anything here, just keep compressing
                            } else {
                                // Something went wrong, we should stop here
                                $this->setSetting('cron_last_error', $compressResult);
                                break;
                            }
                        }
                    } else {
                        // If analyze did not find any image, complete this compression
                        $model->status = ElegantalTinyPngImageCompressClass::$STATUS_COMPLETED;
                    }
                    $model->update();
                }
            } else {
                // Analyze new images
                $cron_compress_image_groups = ElegantalTinyPngImageCompressTools::unserialize($this->getSetting('cron_compress_image_groups'));
                $cron_analyzed_image_groups = ElegantalTinyPngImageCompressTools::unserialize($this->getSetting('cron_analyzed_image_groups'));

                // Product images are handled after it is saved/updated
                if (!in_array('product', $cron_analyzed_image_groups)) {
                    $cron_analyzed_image_groups[] = 'product';
                }

                // Analyze one image group per cron execution
                $image_group = false;
                foreach ($cron_compress_image_groups as $cron_image_group) {
                    if (!in_array($cron_image_group, $cron_analyzed_image_groups)) {
                        $image_group = $cron_image_group;
                        break;
                    }
                }

                if ($image_group) {
                    // Collect images
                    $newModel = new ElegantalTinyPngImageCompressClass();
                    $newModel->image_group = $image_group;
                    $newModel->custom_dir = $this->getSetting('cron_compress_custom_dirs');
                    $newModel->compress_original_images = (int) $this->getSetting('compress_original_images');
                    $newModel->compress_generated_images = (int) $this->getSetting('compress_generated_images');
                    $newModel->image_formats_to_compress = $this->getSetting('image_formats_to_compress');
                    $newModel->images_count = 0;
                    $newModel->images_size_before = 0;
                    $newModel->images_size_after = 0;
                    $newModel->status = ElegantalTinyPngImageCompressClass::$STATUS_ANALYZING;
                    if ($newModel->add()) {
                        $newModel->status = ElegantalTinyPngImageCompressClass::$STATUS_COMPRESSING;
                        $newModel->collectImages();
                        if (!$newModel->images_count) {
                            $newModel->delete();
                        }
                    }
                    // Add this image group to analyzed images, so that it will be skipped next time
                    $cron_analyzed_image_groups[] = $image_group;
                } else {
                    // If all image groups were analyzed, empty this setting
                    $cron_analyzed_image_groups = array();
                }
                $this->setSetting('cron_analyzed_image_groups', ElegantalTinyPngImageCompressTools::serialize($cron_analyzed_image_groups));
            }
        } elseif (!$api_key) {
            $this->setSetting('cron_last_error', $this->l('API key is not valid.'));
        }
    }
}
