<?php
if (!defined('_PS_VERSION_'))
    exit;

class Ekomiintegration extends Module
{
    /**
     * EKOMI constructor.
     */
    public function __construct()
    {
        $this->name                   = 'ekomiintegration';
        $this->tab                    = 'ekomiintegration';
        $this->version                = '1.3.0';
        $this->author                 = 'Ekomi';
        $this->need_instance          = 0;
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => _PS_VERSION_);
        $this->bootstrap              = true;
        $this->module_key             = 'c1bc4261f40e457cefa378b01b328842';
        parent::__construct();
        $this->displayName = $this->l('Ekomi Integration');
        $this->description = $this->l('eKomi Plugin for Prestashop allows you to integrate your Prestashop easily with eKomi system. This allows you to collect verified reviews, display eKomi seal on your website and get your seller ratings on Google. This helps you increase your website\'s click through rates, conversion rates and also, if you are running Google AdWord Campaigns, this helps in improving your Quality Score and hence your costs per click.          eKomi Reviews and Ratings allows you to:          a. Collect Reviews      b. Manage Reviews: our team of Customer Feedback Managers, reviews each and every review for any terms which are not allowed and also put all negative reviews in moderation.      c. Publish reviews on search engines: Google, Bing, Yahoo!      eKomi is available in English, French, German, Spanish, Dutch, Italian, Russian and Polish (more languages coming soon).     If you have any questions regarding the plugin, please contact your eKomi Account Manager.    Please note that you will need an eKomi account to use the plugin. To create an eKomi account, go to eKomi.com.');
        $this->confirmUninstall = $this->l(
            'Are you sure you want to uninstall?'
        );
    }

    /**
     * @return bool
     */
    public function install()
    {
        if (Shop::isFeatureActive())
            Shop::setContext(Shop::CONTEXT_ALL);

        if (!parent::install() ||
            !$this->registerHook('actionOrderStatusPostUpdate') ||
            !Configuration::updateValue('EKOMI_ENABLE', '0') ||
            !Configuration::updateValue('EKOMI_PRODUCT_REVIEWS', '0') ||
            !Configuration::updateValue('EKOMI_MODE', '0') ||
            !Configuration::updateValue('EKOMI_SHOP_ID', '') ||
            !Configuration::updateValue('EKOMI_ORDER_STATUS', '') ||
            !Configuration::updateValue('EKOMI_SHOP_PASSWORD', '')
        )
            return false;

        return true;
    }

    /**
     * @return bool
     */
    public function uninstall()
    {
        if (!parent::uninstall() ||
            !Configuration::deleteByName('EKOMI_ENABLE')  ||
            !Configuration::deleteByName('EKOMI_PRODUCT_REVIEWS')  ||
            !Configuration::deleteByName('EKOMI_MODE')  ||
            !Configuration::deleteByName('EKOMI_SHOP_ID')  ||
            !Configuration::deleteByName('EKOMI_SHOP_PASSWORD') ||
            !Configuration::deleteByName('EKOMI_ORDER_STATUS')
        )
            return false;

        return true;
    }

    /**
     * @return mixed
     */
    public function displayForm()
    {
        // Get default language
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        // Init Fields form array
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings'),
            ),
            'input' => $this->getInputFields(),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'button'
            )
        );

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = array(
            'save' =>
                array(
                    'desc' => $this->l('Save'),
                    'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                        '&token='.Tools::getAdminTokenLite('AdminModules'),
                ),
            'back' => array(
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        // Load current
        $states = Configuration::get('EKOMI_ORDER_STATUS');
        $helper->fields_value['EKOMI_ENABLE'] = Configuration::get('EKOMI_ENABLE');
        $helper->fields_value['EKOMI_PRODUCT_REVIEWS'] = Configuration::get('EKOMI_PRODUCT_REVIEWS');
        $helper->fields_value['EKOMI_MODE'] = Configuration::get('EKOMI_MODE');
        $helper->fields_value['EKOMI_SHOP_ID'] = Configuration::get('EKOMI_SHOP_ID');
        $helper->fields_value['EKOMI_SHOP_PASSWORD'] = Configuration::get('EKOMI_SHOP_PASSWORD');
        $helper->fields_value['EKOMI_ORDER_STATUS[]'] = explode(',', $states);

        return $helper->generateForm($fields_form);
    }

    /**
     * @param $params
     */
    //public function hookActionValidateOrder($params)
    public function hookActionOrderStatusPostUpdate($params)
    {
        $configValues = $this->getConfigValues();

        $orderStatusesArray = explode(',', $configValues['orderStatuses']);
        if(in_array($params['newOrderStatus']->id, $orderStatusesArray )) {
            $order = new Order(intval($params['id_order']));
            if (Validate::isLoadedObject($order)) {
                if ($configValues['isEnabled']) {
                    $fields = $this->getRequiredFields($configValues, $params, $order);
                    if ($configValues['productReviews']) {
                        $products = $this->getProductsData($order);
                        $fields['has_products'] = 1;
                        $fields['products_info'] = json_encode($products);
                    }
                    $postVars = $this->formatPostVars($fields);
                    $this->sendPostVars($postVars);
                    //file_put_contents('/var/www/html/prestaLog', print_r($fields, true),FILE_APPEND);
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getPostValues()
    {
        $configValues = array(
            'isEnabled'      => Tools::getValue('EKOMI_ENABLE'),
            'productReviews' => Tools::getValue('EKOMI_PRODUCT_REVIEWS'),
            'mode'           => Tools::getValue('EKOMI_MODE'),
            'ShopId'         => str_replace(' ', '', strval(Tools::getValue('EKOMI_SHOP_ID'))),
            'ShopPassword'   => str_replace(' ', '', strval(Tools::getValue('EKOMI_SHOP_PASSWORD'))),
            'orderStatuses'  => Tools::getValue('EKOMI_ORDER_STATUS')
        );
        return $configValues;
    }

    /**
     * @param $ShopId
     * @param $ShopPassword
     *
     * @return mixed
     */
    public function verifyAccount($ShopId, $ShopPassword)
    {
        $ApiUrl = 'http://api.ekomi.de/v3/getSettings';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$ApiUrl."?auth=".$ShopId."|".$ShopPassword."&version=cust-1.0.0&type=request&charset=iso");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);

        return $server_output;
    }

    /**
     * @param $configValues
     */
    public function updateValues($configValues)
    {
        $orderStatuses = implode(',',   $configValues['orderStatuses']);
        Configuration::updateValue('EKOMI_ENABLE',          $configValues['isEnabled']);
        Configuration::updateValue('EKOMI_PRODUCT_REVIEWS', $configValues['productReviews']);
        Configuration::updateValue('EKOMI_MODE',            $configValues['mode']);
        Configuration::updateValue('EKOMI_SHOP_ID',         str_replace(' ', '', $configValues['ShopId']));
        Configuration::updateValue('EKOMI_SHOP_PASSWORD',   str_replace(' ', '', $configValues['ShopPassword']));
        Configuration::updateValue('EKOMI_ORDER_STATUS',    $orderStatuses);
    }

    /**
     * @return string
     */
    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit'.$this->name)) {
            $configValues = $this->getPostValues();
            if (empty($configValues['ShopId']) || empty($configValues['ShopPassword'])) {
                $output .= $this->displayError($this->l('Shop ID & Password Required.'));
            } else {
                $server_output = $this->verifyAccount($configValues['ShopId'], $configValues['ShopPassword']);
                if ($server_output == 'Access denied') {
                    $output .= $this->displayError($this->l($server_output));
                    Configuration::updateValue('EKOMI_ENABLE', 0);
                } else {
                    $this->updateValues($configValues);
                    $output .= $this->displayConfirmation($this->l('Settings updated'));
                }
            }
        }
        return $output.$this->displayForm();
    }

    /**
     * @return array
     */
    public function getBoolOptions()
    {
        $options = array(
            array(
                'id_enable' => 0,
                'name' => 'No'
            ),
            array(
                'id_enable' => 1,
                'name' => 'Yes'
            ),
        );
        return $options;
    }

    /**
     * @return array
     */
    public function getModeOptions()
    {
        $modeOptions = array(
            array(
                'value' => 'email',
                'name' => 'Email'
            ),
            array(
                'value' => 'sms',
                'name' => 'SMS'
            ),
            array(
                'value' => 'fallback',
                'name' => 'SMS if mobile number, otherwise Email'
            ),
        );
        return $modeOptions;
    }

    /**
     * @return array
     */
    public function getStatusesArray()
    {
        $statuses_array = array();
        $statuses = OrderState::getOrderStates((int)$this->context->language->id);
        foreach ($statuses as $status) {
            $statuses_array[] = array('id' => $status['id_order_state'], 'name' => $status['name']);
        }
        return $statuses_array;
    }

    /**
     * @return array
     */
    public function getInputFields()
    {
        $options        = $this->getBoolOptions();
        $modeOptions    = $this->getModeOptions();
        $statuses_array = $this->getStatusesArray();

        $inputFields = array(
            array(
                'type' => 'select',
                'label' => $this->l('Enabled'),
                'name' => 'EKOMI_ENABLE',
                'options' => array(
                    'query' => $options,
                    'id' => 'id_enable',
                    'name' => 'name'
                ),
                'required' => true
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Product Base Reviews'),
                'name' => 'EKOMI_PRODUCT_REVIEWS',
                'options' => array(
                    'query' => $options,
                    'id' => 'id_enable',
                    'name' => 'name'
                ),
                'required' => true
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Mode'),
                'name' => 'EKOMI_MODE',
                'options' => array(
                    'query' => $modeOptions,
                    'id' => 'value',
                    'name' => 'name'
                ),
                'required' => true
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Shop ID'),
                'name' => 'EKOMI_SHOP_ID',
                'size' => 20,
                'required' => true
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Shop Password'),
                'name' => 'EKOMI_SHOP_PASSWORD',
                'size' => 20,
                'required' => true
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Order Statuses'),
                'name' => 'EKOMI_ORDER_STATUS[]',
                'id' => 'EKOMI_ORDER_STATUS',
                'multiple' => true ,
                'options' => array(
                    'query' => $statuses_array,
                    'id' => 'id',
                    'name' => 'name'
                ),
                'required' => true,
            )
        );
        return $inputFields;
    }

    /**
     * @return array
     */
    public function getConfigValues()
    {
        $configValues = array(
            'isEnabled'      => Configuration::get('EKOMI_ENABLE'),
            'productReviews' => Configuration::get('EKOMI_PRODUCT_REVIEWS'),
            'mode'           => Configuration::get('EKOMI_MODE'),
            'ShopId'         => str_replace(' ', '', strval(Configuration::get('EKOMI_SHOP_ID'))),
            'ShopPassword'   => str_replace(' ', '', strval(Configuration::get('EKOMI_SHOP_PASSWORD'))),
            'orderStatuses'  => Configuration::get('EKOMI_ORDER_STATUS')
        );
        return $configValues;
    }

    /**
     * @param $fields
     *
     * @return string
     */
    public function formatPostVars($fields)
    {
        $postvars = '';
        $counter  = 1;
        foreach ($fields as $key => $value) {
            if ($counter > 1) {
                $postvars .= "&";
            }
            $postvars .= $key . "=" . $value;
            $counter++;
        }
        return $postvars;
    }

    /**
     * @param $postvars
     */
    public function sendPostVars($postvars)
    {
        $apiUrl = 'https://apps.ekomi.com/srr/add-recipient';
        if (!empty($postvars)) {
            $boundary = md5(time());
            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $apiUrl);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('ContentType:multipart/form-data;boundary=' . $boundary));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
                $x = curl_exec($ch);
                curl_close($ch);
            } catch (Exception $e) {
                PrestaShopLogger::addLog($e->getMessage(), 1);
            }
        }
    }

    /**
     * @param $phone
     *
     * @return bool
     */
    public function validateE164($phone)
    {
        $pattern = '/^\+?[1-9]\d{1,14}$/';
        preg_match($pattern, $phone, $matches);
        if (!empty($matches)) {
            return true;
        }
        return false;
    }

    /**
     * @param $configValues
     * @param $params
     * @param $order
     *
     * @return array
     */
    public function getRequiredFields($configValues, $params, $order)
    {
        $customer  = new Customer(intval($order->id_customer));
        $address   = new Address(intval($order->id_address_delivery));
        $telephone = $address->phone_mobile;
        if(!$telephone){
            $telephone = $address->phone;
        }
        $e164      = $this->validateE164($telephone);
        $apiMode   = 'email';

        switch($configValues['mode']){
            case 'sms':
                $apiMode = 'sms';
                break;
            case 'email':
                $apiMode = 'email';
                break;
            case 'fallback':
                if($e164)
                    $apiMode = 'sms';
                else
                    $apiMode = 'email';
                break;
        }

        $fields = array(
            'recipient_type'   => $apiMode,
            'shop_id'          => $configValues['ShopId'],
            'password'         => $configValues['ShopPassword'],
            'salutation'       => '',
            'first_name'       => $customer->firstname,
            'last_name'        => $customer->lastname,
            'email'            => $customer->email,
            'transaction_id'   => $params['id_order'],
            'transaction_time' => $order->date_add,
            'telephone'        => $telephone,
            'sender_name'      => strval(Configuration::get('PS_SHOP_NAME')),
            'sender_email'     => strval(Configuration::get('PS_SHOP_EMAIL')),
            'client_id'        => $customer->id,
            'screen_name'      => $customer->firstname . " " . $customer->lastname
        );
        return $fields;
    }

    /**
     * @param $order
     *
     * @return array
     */
    public function getProductsData($order)
    {
        $products = array();
        $productsList = $order->getProducts();
        foreach ($productsList as $product) {
            $products[$product['product_id']] = addslashes(
                $product['product_name']
            );
        }
        return $products;
    }

}

