<?php
/**
 * NOTICE OF LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * ...........................................................................
 *
 * @package   Slider
 * @author    Paul MORA
 * @copyright Copyright (c) 2012-2014 SAS BlobMarket - www.blobmarket.com - Paul MORA
 * @license   MIT license
 * Support by mail  :  contact@blobmarket.com
 */

class AdminSliderController extends ModuleAdminController
{
    protected $position_identifier = 'id_slider_slides';
    public $module = 'slider';
    public $bootstrap = true;

    public function __construct()
    {
        $this->table = 'slider_slides';
        $this->className = 'Slideshow';
        $this->lang = true;

        $this->bulk_actions = array(
            'delete' => array('text' => $this->l('Delete selected'), 'confirm' => $this->l('Delete selected items?'), 'icon' => 'icon-trash')
        );

        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->identifier = 'id_slider_slides';

        $this->fieldImageSettings = array();
        $languages = Language::getLanguages(false);
        foreach ($languages as $language)
            $this->fieldImageSettings[] = array('name' => 'image_'.$language['id_lang'], 'dir' => 'slider');

        $this->imageType = 'png';

        parent::__construct();

        $this->context = Context::getContext();
    }

    /**
     * Function used to render the list to display for this controller
     */
    public function renderList()
    {
        $this->fields_list = array(
            'id_slider_slides' => array(
                'title' => $this->module->l('ID', 'AdminSliderController'),
                'align' => 'center',
                'width' => 40,
                'search' => false
            ),
            'image' => array(
                'title' => $this->module->l('Image', 'AdminSliderController'),
                'align' => 'center',
                'slider_image' => _PS_IMG_.$this->fieldImageSettings[0]['dir'].'/',
                'shop' => $this->context->shop->id,
                'timestamp' => time(),
                'orderby' => false,
                'search' => false
            ),
            'title' => array(
                'title' => $this->module->l('Title', 'AdminSliderController'),
                'search' => false
            ),
            'active' => array(
                'title' => $this->module->l('Enabled', 'AdminSliderController'),
                'width' => 40,
                'align' => 'center',
                'active' => 'status',
                'type' => 'bool',
                'search' => false
            ),
            'position' => array(
                'title' => $this->module->l('Position', 'AdminSliderController'),
                'width' => 40,
                'filter_key' => 'a!position',
                'position' => 'position',
                'search' => false
            )
        );

        $this->_where = 'AND a.id_shop = '.$this->context->shop->id;

        return parent::renderList();
    }

    /**
     * Function used to render the form for this controller
     */
    public function renderForm()
    {
        $this->fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => $this->l('Configure your slide'),
                'icon' => 'icon-picture'
            ),
            'input' => array(
                array(
                    'type' => 'file_lang',
                    'label' => $this->l('Image'),
                    'name' => 'image',
                    'display_image' => true,
                    'lang' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->module->l('Link', 'AdminSliderController'),
                    'name' => 'link',
                    'lang' => true,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Open in new tab', 'AdminSliderController'),
                    'name' => 'blank',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->module->l('Title', 'AdminSliderController'),
                    'name' => 'title',
                    'lang' => true,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->module->l('Content', 'AdminSliderController'),
                    'name' => 'content',
                    'lang' => true,
                    'rows' => 5,
                    'cols' => 40,
                    'autoload_rte' => 'rte'
                ),
                array(
                    'type' => 'text',
                    'label' => $this->module->l('Button text', 'AdminSliderController'),
                    'name' => 'button',
                    'lang' => true,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Enabled'),
                    'name' => 'active',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
            ),
            'submit' => array(
                'title' => $this->module->l('Save', 'AdminSliderController'),
                'class' => 'btn btn-default pull-right'
            )
        );

        $this->fields_value = array(
            'slider_id_shop' => 0,
            'slider_from_quantity' => 1,
        );

        $this->context->smarty->assign(
            array(
                'image_baseurl' => _PS_IMG_.$this->fieldImageSettings[0]['dir'].'/'
            )
        );

        return parent::renderForm();
    }

    // Overrided for Slider
    public function postProcess()
    {
        parent::postProcess();

        /* Processes Slide */
        if (Tools::isSubmit('submitAddslider_slides'))
        {
            /* Sets ID if needed */
            if (Tools::getValue('id_slider_slides'))
            {
                $slide = new $this->className((int)Tools::getValue('id_slider_slides'));
                if (!Validate::isLoadedObject($slide))
                    return false;
            }
            else{
                $slide = new $this->className();
            }

            /* Sets image name */

            $languages = Language::getLanguages(false);
            foreach ($languages as $language){
                $old_name = $slide->id.'_'.$language['id_lang'].'.'.$this->imageType;
                $new_name = $slide->id.'_'.$language['id_lang'].'_'.time().'.'.$this->imageType;
                if(file_exists(_PS_IMG_DIR_.$this->fieldImageSettings[0]['dir'].'/'.$old_name)){
                    rename(_PS_IMG_DIR_.$this->fieldImageSettings[0]['dir'].'/'.$old_name, _PS_IMG_DIR_.$this->fieldImageSettings[0]['dir'].'/'.$new_name);
                    $slide->image[$language['id_lang']] = $new_name;
                }
            }

            $slide->save();
        }
    }

    // Overrided for Slider
    // Multilingual Images
    protected function uploadImage($id, $name, $dir, $ext = false, $width = null, $height = null)
    {
        if (isset($_FILES[$name]['tmp_name']) && !empty($_FILES[$name]['tmp_name']))
        {
            // Added for Slider | Multilingual Images
            $languages = Language::getLanguages(false);
            foreach ($languages as $language)
                if ($name == 'image_'.$language['id_lang'])
                    $id .= '_'.$language['id_lang'];
            // Added for Slider | Multilingual Images

            // Delete old image
            if (Validate::isLoadedObject($object = $this->loadObject()))
                $object->deleteImage();
            else
                return false;

            // Check image validity
            $max_size = isset($this->max_image_size) ? $this->max_image_size : 0;
            if ($error = ImageManager::validateUpload($_FILES[$name], Tools::getMaxUploadSize($max_size)))
                $this->errors[] = $error;

            $tmp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
            if (!$tmp_name)
                return false;

            if (!move_uploaded_file($_FILES[$name]['tmp_name'], $tmp_name))
                return false;

            // Evaluate the memory required to resize the image: if it's too much, you can't resize it.
            if (!ImageManager::checkImageMemoryLimit($tmp_name))
                $this->errors[] = Tools::displayError('Due to memory limit restrictions, this image cannot be loaded. Please increase your memory_limit value via your server\'s configuration settings. ');

            // Copy new image
            if (empty($this->errors) && !ImageManager::resize($tmp_name, _PS_IMG_DIR_.$dir.$id.'.'.$this->imageType, (int)$width, (int)$height, ($ext ? $ext : $this->imageType)))
                $this->errors[] = Tools::displayError('An error occurred while uploading the image.');

            if (count($this->errors))
                return false;
            if ($this->afterImageUpload())
            {
                unlink($tmp_name);
                return true;
            }
            return false;
        }
        return true;
    }

    // Created for Slider
    // Ajax Update Position
    public function ajaxProcessUpdatePositions()
    {
        $way = (int)(Tools::getValue('way'));
        $id_tab = (int)(Tools::getValue('id'));
        $positions = Tools::getValue($this->table);

        if (is_array($positions))
            foreach ($positions as $position => $value)
            {
                $pos = explode('_', $value);

                if (isset($pos[2]) && (int)$pos[2] === $id_tab)
                {
                    if ($tab = new $this->className((int)$pos[2]))
                        if (isset($position) && $tab->updatePosition($way, $position))
                            echo 'ok position '.(int)$position.' for tab '.(int)$pos[1].'\r\n';
                        else
                            echo '{"hasError" : true, "errors" : "Can not update tab '.(int)$id_tab.' to position '.(int)$position.' "}';
                    else
                        echo '{"hasError" : true, "errors" : "This tab ('.(int)$id_tab.') can t be loaded"}';

                    break;
                }
            }
    }
}