<?php



// Avoid direct access to the file

if (!defined('_PS_VERSION_'))

    exit;

require_once(_PS_MODULE_DIR_.'tipsacarrier/lib/tipsalog.php');



class tipsacarrier extends CarrierModule {

    public  $id_carrier;



    private $_html = '';

    private $_postErrors = array();

    private $_moduleName = 'tipsacarrier';





    /*

	** Construct Method

	**

    */



    public function __construct() {

        $this->name = 'tipsacarrier';

        $this->tab = 'shipping_logistics';

        $this->version = '1.5';

        $this->author = 'TIPSA';

        $this->limited_countries = '';//array('es', 'ad', 'pt');



        parent::__construct ();



        $this->displayName = $this->l('Transportista TIPSA');

        $this->description = $this->l('Módulo que integra el sistema de envíos con TIPSA con sincronizacion de estados (20/02/13)');



        if (self::isInstalled($this->name)) {

            // Getting carrier list

            global $cookie;

            $carriers = Carrier::getCarriers($cookie->id_lang, true, false, false, NULL, PS_CARRIERS_AND_CARRIER_MODULES_NEED_RANGE);



            // Saving id carrier list

            $id_carrier_list = array();

            foreach($carriers as $carrier)

                $id_carrier_list[] .= $carrier['id_carrier'];



            // Testing if Carrier Id exists

            $warning = array();

            

            if (!Configuration::get('TIPSA_CODIGO_AGENCIA'))

                $warning[] .= $this->l('"Código de Agencia"').' ';

            if (!Configuration::get('TIPSA_CODIGO_CLIENTE'))

                $warning[] .= $this->l('"Código del cliente"').' ';

            if (!Configuration::get('TIPSA_PASSWORD_CLIENTE'))

                $warning[] .= $this->l('"Password del cliente"').' ';

            if (!Configuration::get('TIPSA_URL'))

                $warning[] .= $this->l('"URL del WS"').' ';

                

            if (count($warning))

                $this->warning .= implode(' , ',$warning).$this->l('debe finalizar la configuración antes de utilizar este módulo.').' ';

        }

    }





    /*

	** Install / Uninstall Methods

	**

    */

	// MIA : Crear un carrier para cada tipo de servicio que ofrece TIPSA unos 4

    public function install() {

    	//preparamos la tabla para los envios

        $query = "CREATE TABLE IF NOT EXISTS "._DB_PREFIX_."tipsa_envios (

			        id_envio int(11) NOT NULL AUTO_INCREMENT,

			        id_envio_order int(11) NOT NULL,

			        codigo_envio varchar(50) NOT NULL,

			        url_track varchar(255) NOT NULL,

			        num_albaran varchar(100) NOT NULL,

			        codigo_barras text,

			        fecha datetime NOT NULL,

			        PRIMARY KEY (`id_envio`)

			      ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8 ";

        if(!Db::getInstance()->Execute($query)){

        	TipsaLog::error('Imposible crear la tabla '._DB_PREFIX_.'tipsa_envios usando el ENGINE='._MYSQL_ENGINE_);        	

	        // do rollback

	        $this->tablesRollback();

	        return false;

	    }

    	//preparamos la tabla para el mensaje personalizado

        $query = "CREATE TABLE IF NOT EXISTS "._DB_PREFIX_."tipsa_email (

			        id int(11) NOT NULL AUTO_INCREMENT,

			        titulo varchar(128),

			        mensaje text,

			        PRIMARY KEY (`id`)

			      ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8 ";

        if(!Db::getInstance()->Execute($query)){

        	TipsaLog::error('Imposible crear la tabla '._DB_PREFIX_.'tipsa_email usando el ENGINE='._MYSQL_ENGINE_);        	

	        // do rollback

	        $this->tablesRollback();

	        return false;

	    }

        $query = "INSERT INTO "._DB_PREFIX_."tipsa_email (titulo,mensaje) VALUES ('ejemplo','Escriba aqui su mensaje...')";

        if(!Db::getInstance()->Execute($query)){

        	TipsaLog::error('Imposible crear registro en la tabla '._DB_PREFIX_.'tipsa_email usando el ENGINE='._MYSQL_ENGINE_);        	

	        // do rollback

	        $this->tablesRollback();

	        return false;

	    }	    

        //preparamos los diferentes servicos

        $carrierConfig = array(

                0 => array('name' => 'TIPSA-14H',

                        'id_tax_rules_group' => 0,

                        'active' => true,

                        'deleted' => 0,

                        'shipping_handling' => false,

                        'range_behavior' => 0,

                        'delay' => array(Language::getIsoById(Configuration::get('PS_LANG_DEFAULT')) => 'Tipsa 14 Horas'),

                        'id_zone' => 0,

                        'is_module' => false,

                        'shipping_external' => false,

                        'external_module_name' => $this->_moduleName,

                        'need_range' => false // mirar si ponemos en false para poder usar el CSV

                ),

                1 => array('name' => 'TIPSA-10H',

                        'id_tax_rules_group' => 0,

                        'active' => true,

                        'deleted' => 0,

                        'shipping_handling' => false,

                        'range_behavior' => 0,

                        'delay' => array(Language::getIsoById(Configuration::get('PS_LANG_DEFAULT')) => 'Tipsa antes 10'),

                        'id_zone' => 0,

                        'is_module' => false,

                        'shipping_external' => false,

                        'external_module_name' => $this->_moduleName,

                        'need_range' => false

                ),

                2 => array('name' => 'TIPSA-MV',

                        'id_tax_rules_group' => 0,

                        'active' => true,

                        'deleted' => 0,

                        'shipping_handling' => false,

                        'range_behavior' => 0,

                        'delay' => array(Language::getIsoById(Configuration::get('PS_LANG_DEFAULT')) => 'Tipsa Masivo'),

                        'id_zone' => 0,

                        'is_module' => false,

                        'shipping_external' => false,

                        'external_module_name' => $this->_moduleName,

                        'need_range' => false

                ),

                3 => array('name' => 'TIPSA-48H',

                        'id_tax_rules_group' => 0,

                        'active' => true,

                        'deleted' => 0,

                        'shipping_handling' => false,

                        'range_behavior' => 0,

                        'delay' => array(Language::getIsoById(Configuration::get('PS_LANG_DEFAULT')) => 'Tipsa entrega 48H'),

                        'id_zone' => 0,

                        'is_module' => false,

                        'shipping_external' => false,

                        'external_module_name' => $this->_moduleName,

                        'need_range' => false

                ),

                

        );



        $id_carrier1 = $this->installExternalCarrier($carrierConfig[0]);

        $id_carrier2 = $this->installExternalCarrier($carrierConfig[1]);

        $id_carrier3 = $this->installExternalCarrier($carrierConfig[2]);

        $id_carrier4 = $this->installExternalCarrier($carrierConfig[3]);

        Configuration::updateValue('MYCARRIER1_CARRIER_ID', (int)$id_carrier1);

        Configuration::updateValue('MYCARRIER2_CARRIER_ID', (int)$id_carrier2);

        Configuration::updateValue('MYCARRIER3_CARRIER_ID', (int)$id_carrier3);

        Configuration::updateValue('MYCARRIER4_CARRIER_ID', (int)$id_carrier4);

        

        if (!parent::install() || !$this->registerHook('updateCarrier'))

            return false;



        // creamos el boton para agregar las funcionalidades realizar y cancelar pedido e imprimir etiquetas     

        $tab = new Tab();

	    $tab->class_name = 'AdminTipsa';

	    $tab->id_parent = 10;

		$tab->position = 7;

	    $tab->module = $this->_moduleName;

	    $tab->name[(int)(Configuration::get('PS_LANG_DEFAULT'))] = 'TIPSA';

	    if(!$tab->add())

	    { 

	      $this->tablesRollback();

	      return false;

	    }        

        return true;

    }



    public function uninstall() {

        // Uninstall

        if (!parent::uninstall() || !$this->unregisterHook('updateCarrier'))

            return false;



        // Delete External Carrier

        $Carrier1 = new Carrier((int)(Configuration::get('MYCARRIER1_CARRIER_ID')));

        $Carrier2 = new Carrier((int)(Configuration::get('MYCARRIER2_CARRIER_ID')));

        $Carrier3 = new Carrier((int)(Configuration::get('MYCARRIER3_CARRIER_ID')));

        $Carrier4 = new Carrier((int)(Configuration::get('MYCARRIER4_CARRIER_ID')));

        // If external carrier is default set other one as default

        if (Configuration::get('PS_CARRIER_DEFAULT') == (int)($Carrier1->id) || 

        	Configuration::get('PS_CARRIER_DEFAULT') == (int)($Carrier2->id) ||

        	Configuration::get('PS_CARRIER_DEFAULT') == (int)($Carrier3->id) ||

        	Configuration::get('PS_CARRIER_DEFAULT') == (int)($Carrier4->id)) {

            global $cookie;

            $carriersD = Carrier::getCarriers($cookie->id_lang, true, false, false, NULL, PS_CARRIERS_AND_CARRIER_MODULES_NEED_RANGE);

            foreach($carriersD as $carrierD)

                if ($carrierD['active'] AND !$carrierD['deleted'] AND ($carrierD['name'] != $this->_config['name']))

                    Configuration::updateValue('PS_CARRIER_DEFAULT', $carrierD['id_carrier']);

        }



        // Then delete Carrier

        $Carrier1->deleted = 1;

        $Carrier2->deleted = 1;

        $Carrier3->deleted = 1;

        $Carrier4->deleted = 1;

        if (!$Carrier1->update() || !$Carrier2->update() || !$Carrier3->update() || !$Carrier4->update())

            return false;

        // Borramos el tab TIPSA

        Db::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.'tab WHERE module = "'.$this->_moduleName.'"');

        Db::getInstance()->Execute('DROP TABLE '._DB_PREFIX_.'tipsa_envios');

        Db::getInstance()->Execute('DROP TABLE '._DB_PREFIX_.'tipsa_email');

        return true;

    }



    public static function installExternalCarrier($config) {

        $carrier = new Carrier();

        $carrier->name = $config['name'];

        $carrier->id_tax_rules_group = $config['id_tax_rules_group'];

        $carrier->id_zone = $config['id_zone'];

        $carrier->active = $config['active'];

        $carrier->deleted = $config['deleted'];

        $carrier->delay = $config['delay'];

        $carrier->shipping_handling = $config['shipping_handling'];

        $carrier->range_behavior = $config['range_behavior'];

        $carrier->is_module = $config['is_module'];

        $carrier->shipping_external = $config['shipping_external'];

        $carrier->external_module_name = $config['external_module_name'];

        $carrier->need_range = $config['need_range'];



        $languages = Language::getLanguages(true);

        foreach ($languages as $language) {

            if ($language['iso_code'] == 'fr')

                $carrier->delay[(int)$language['id_lang']] = $config['delay'][$language['iso_code']];

            if ($language['iso_code'] == 'en')

                $carrier->delay[(int)$language['id_lang']] = $config['delay'][$language['iso_code']];

            if ($language['iso_code'] == Language::getIsoById(Configuration::get('PS_LANG_DEFAULT')))

                $carrier->delay[(int)$language['id_lang']] = $config['delay'][$language['iso_code']];

        }



        if ($carrier->add()) {

            $groups = Group::getGroups(true);

            foreach ($groups as $group)

                Db::getInstance()->autoExecute(_DB_PREFIX_.'carrier_group', array('id_carrier' => (int)($carrier->id), 'id_group' => (int)($group['id_group'])), 'INSERT');



            $rangePrice = new RangePrice();

            $rangePrice->id_carrier = $carrier->id;

            $rangePrice->delimiter1 = '0';

            $rangePrice->delimiter2 = '1000000000';

            $rangePrice->add();



            $rangeWeight = new RangeWeight();

            $rangeWeight->id_carrier = $carrier->id;

            $rangeWeight->delimiter1 = '0';

            $rangeWeight->delimiter2 = '1000000000';

            $rangeWeight->add();



            $zones = Zone::getZones(true);

            foreach ($zones as $zone) {

                Db::getInstance()->autoExecute(_DB_PREFIX_.'carrier_zone', array('id_carrier' => (int)($carrier->id), 'id_zone' => (int)($zone['id_zone'])), 'INSERT');

                Db::getInstance()->autoExecuteWithNullValues(_DB_PREFIX_.'delivery', array('id_carrier' => (int)($carrier->id), 'id_range_price' => (int)($rangePrice->id), 'id_range_weight' => NULL, 'id_zone' => (int)($zone['id_zone']), 'price' => '0'), 'INSERT');

                Db::getInstance()->autoExecuteWithNullValues(_DB_PREFIX_.'delivery', array('id_carrier' => (int)($carrier->id), 'id_range_price' => NULL, 'id_range_weight' => (int)($rangeWeight->id), 'id_zone' => (int)($zone['id_zone']), 'price' => '0'), 'INSERT');

            }



            // Copiamos los logos de cada servicio

            if($config['name'] == 'TIPSA-14H'){

            	if (!copy(dirname(__FILE__).'/tipsa_14.jpg', _PS_SHIP_IMG_DIR_.'/'.(int)$carrier->id.'.jpg'))

                	return false;

            }

            if($config['name'] == 'TIPSA-10H'){

            	if (!copy(dirname(__FILE__).'/tipsa_10.jpg', _PS_SHIP_IMG_DIR_.'/'.(int)$carrier->id.'.jpg'))

                	return false;

            }

            if($config['name'] == 'TIPSA-MV'){

            	if (!copy(dirname(__FILE__).'/tipsa_MV.jpg', _PS_SHIP_IMG_DIR_.'/'.(int)$carrier->id.'.jpg'))

                	return false;

            }

            if($config['name'] == 'TIPSA-48H'){

            	if (!copy(dirname(__FILE__).'/tipsa_48.jpg', _PS_SHIP_IMG_DIR_.'/'.(int)$carrier->id.'.jpg'))

                	return false;

            }

            

            // Return ID Carrier

            return (int)($carrier->id);

        }



        return false;

    }









    /*

	** Form Config Methods

	**

    */



    public function getContent() {

        $this->_html .= '<h2>' . $this->l('My Carrier').'</h2>';

        if (!empty($_POST) AND Tools::isSubmit('submitSave')) {

            $this->_postValidation();

            if (!sizeof($this->_postErrors))

                $this->_postProcess();

            else

                foreach ($this->_postErrors AS $err)

                    $this->_html .= '<div class="alert error"><img src="'._PS_IMG_.'admin/forbbiden.gif" alt="nok" />&nbsp;'.$err.'</div>';

        }

        $this->_displayForm();

        return $this->_html;

    }



    private function _displayForm() {

        $this->_html .= '<fieldset>

		<legend><img src="'.$this->_path.'logo.gif" alt="" /> '.$this->l('Estado del módulo').'</legend>';



        $alert = array();

        if(!Configuration::get('TIPSA_CODIGO_AGENCIA') || Configuration::get('TIPSA_CODIGO_AGENCIA') == '')

            $alert['tipsa_codigo_agencia'] = 1;

        if(!Configuration::get('TIPSA_CODIGO_CLIENTE') || Configuration::get('TIPSA_CODIGO_CLIENTE') == '')

            $alert['tipsa_codigo_cliente'] = 1;

        if(!Configuration::get('TIPSA_PASSWORD_CLIENTE') || Configuration::get('TIPSA_PASSWORD_CLIENTE') == '')

            $alert['tipsa_password_cliente'] = 1;

        if(!Configuration::get('TIPSA_URL') || Configuration::get('TIPSA_URL') == '')

            $alert['tipsa_url'] = 1;

        if (!count($alert))

            $this->_html .= '<img src="'._PS_IMG_.'admin/module_install.png" /><strong>'.$this->l('Módulo TIPSA configurado y en línea!').'</strong>';

        else {

            $this->_html .= '<img src="'._PS_IMG_.'admin/warn2.png" /><strong>'.$this->l('Módulo TIPSA no está configurado, por favor:').'</strong>';

            $this->_html .= '<br />'.(isset($alert['tipsa_codigo_agencia']) ? '<img src="'._PS_IMG_.'admin/warn2.png" />' : '<img src="'._PS_IMG_.'admin/module_install.png" />').' 1) '.$this->l('Configure el código de agencia');

            $this->_html .= '<br />'.(isset($alert['tipsa_codigo_cliente']) ? '<img src="'._PS_IMG_.'admin/warn2.png" />' : '<img src="'._PS_IMG_.'admin/module_install.png" />').' 2) '.$this->l('Configure el código de cliente');

            $this->_html .= '<br />'.(isset($alert['tipsa_password_cliente']) ? '<img src="'._PS_IMG_.'admin/warn2.png" />' : '<img src="'._PS_IMG_.'admin/module_install.png" />').' 3) '.$this->l('Configure el password');

            $this->_html .= '<br />'.(isset($alert['tipsa_url']) ? '<img src="'._PS_IMG_.'admin/warn2.png" />' : '<img src="'._PS_IMG_.'admin/module_install.png" />').' 4) '.$this->l('Configure la URL WS de conexión - formato http://xxxxx.com');



        }



        // Comprobamos selecciones anteriores

        // ENVIOS GRATUITOS

        $envio_gratuito_si = "";

        $envio_gratuito_no = "";

        $servicio_gratuito_nada = "";

        $servicio_gratuito_14 = "";

        $servicio_gratuito_10 = "";

		$servicio_gratuito_MV = "";

        $servicio_gratuito_48 = "";

        $mostrar_todo_si = "";

        $mostrar_todo_no = "";

        

        if(Tools::getValue('tipsa_envio_gratuito', Configuration::get('TIPSA_ENVIO_GRAT')) == "0") {

            $envio_gratuito_no = "checked=\"checked\"";

        }

        if(Tools::getValue('tipsa_envio_gratuito', Configuration::get('TIPSA_ENVIO_GRAT')) == "1") {

            $envio_gratuito_si = "checked=\"checked\"";

        }

        if(Tools::getValue('tipsa_servicio_envio_gratuito', Configuration::get('TIPSA_SERVICIO_GRAT')) == "0") {

            $servicio_gratuito_nada = "selected=\"selected\"";

        }

        if(Tools::getValue('tipsa_servicio_envio_gratuito', Configuration::get('TIPSA_SERVICIO_GRAT')) == "14") {

            $servicio_gratuito_14 = "selected=\"selected\"";

        }

        if(Tools::getValue('tipsa_servicio_envio_gratuito', Configuration::get('TIPSA_SERVICIO_GRAT')) == "10") {

            $servicio_gratuito_10 = "selected=\"selected\"";

        }

        if(Tools::getValue('tipsa_servicio_envio_gratuito', Configuration::get('TIPSA_SERVICIO_GRAT')) == "MV") {

            $servicio_gratuito_MV = "selected=\"selected\"";

        }

        if(Tools::getValue('tipsa_servicio_envio_gratuito', Configuration::get('TIPSA_SERVICIO_GRAT')) == "48") {

            $servicio_gratuito_48 = "selected=\"selected\"";

        }



        if(Tools::getValue('tipsa_mostrar_todo', Configuration::get('TIPSA_RESTO')) == "0") {

        	$mostrar_todo_no = "checked=\"checked\"";

        }

        if(Tools::getValue('tipsa_mostrar_todo', Configuration::get('TIPSA_RESTO')) == "1") {

        	$mostrar_todo_si = "checked=\"checked\"";

        }

        /*

        $envio_gratuito_internacional_si = "";

        $envio_gratuito_internacional_no = "";

        $servicio_gratuito_internacional_nada = "";

        $servicio_gratuito_internacional_eeu = "";

        $servicio_gratuito_internacional_eww = "";

        $mostrar_todo_internacional_si = "";

        $mostrar_todo_internacional_no = "";

        if(Tools::getValue('tipsa_envio_gratuito_internacional', Configuration::get('TIPSA_ENVIO_GRAT_INT')) == "0") {

            $envio_gratuito_internacional_no = "checked=\"checked\"";

        }

        if(Tools::getValue('tipsa_envio_gratuito_internacional', Configuration::get('TIPSA_ENVIO_GRAT_INT')) == "1") {

            $envio_gratuito_internacional_si = "checked=\"checked\"";

        }

        if(Tools::getValue('tipsa_servicio_envio_gratuito_internacional', Configuration::get('TIPSA_SERVICIO_GRAT_INT')) == "0") {

            $servicio_gratuito_internacional_nada = "selected=\"selected\"";

        }

        if(Tools::getValue('tipsa_servicio_envio_gratuito_internacional', Configuration::get('TIPSA_SERVICIO_GRAT_INT')) == "EEU") {

            $servicio_gratuito_internacional_eeu = "selected=\"selected\"";

        }

        if(Tools::getValue('tipsa_servicio_envio_gratuito_internacional', Configuration::get('TIPSA_SERVICIO_GRAT_INT')) == "EWW") {

            $servicio_gratuito_internacional_eww = "selected=\"selected\"";

        }

        if(Tools::getValue('tipsa_mostrar_todo_internacional', Configuration::get('TIPSA_INTER_RESTO')) == "0") {

        	$mostrar_todo_internacional_no = "checked=\"checked\"";

        }

        if(Tools::getValue('tipsa_mostrar_todo_internacional', Configuration::get('TIPSA_INTER_RESTO')) == "1") {

        	$mostrar_todo_internacional_si = "checked=\"checked\"";

        }

		*/

        // ENVIOS NO GRATUITOS

        $envio_servicio_14_no = "";

        $envio_servicio_14_si = "";

        $envio_servicio_10_no = "";

        $envio_servicio_10_si = "";

        $envio_servicio_MV_no = "";

        $envio_servicio_MV_si = "";

        $envio_servicio_48_no = "";

        $envio_servicio_48_si = "";

        if(Tools::getValue('envio_servicio_14', Configuration::get('TIPSA_14H')) == "0") {

            $envio_servicio_14_no = "checked=\"checked\"";

        }

        if(Tools::getValue('envio_servicio_14', Configuration::get('TIPSA_14H')) == "14") {

            $envio_servicio_14_si = "checked=\"checked\"";

        }

        if(Tools::getValue('envio_servicio_10', Configuration::get('TIPSA_10H')) == "0") {

            $envio_servicio_10_no = "checked=\"checked\"";

        }

        if(Tools::getValue('envio_servicio_10', Configuration::get('TIPSA_10H')) == "10") {

            $envio_servicio_10_si = "checked=\"checked\"";

        }

        if(Tools::getValue('envio_servicio_MV', Configuration::get('TIPSA_MV')) == "0") {

            $envio_servicio_MV_no = "checked=\"checked\"";

        }

        if(Tools::getValue('envio_servicio_MV', Configuration::get('TIPSA_MV')) == "MV") {

            $envio_servicio_MV_si = "checked=\"checked\"";

        }

        if(Tools::getValue('envio_servicio_48', Configuration::get('TIPSA_48H')) == "0") {

            $envio_servicio_48_no = "checked=\"checked\"";

        }

        if(Tools::getValue('envio_servicio_48', Configuration::get('TIPSA_48H')) == "48") {

            $envio_servicio_48_si = "checked=\"checked\"";

        }

        // VARIOS

        $bultos_si = "";

        $bultos_no = "";

        if(Tools::getValue('tipsa_bultos', Configuration::get('TIPSA_BULTOS')) == "0") {

        	$bultos_no = "checked=\"checked\"";

        }

        if(Tools::getValue('tipsa_bultos', Configuration::get('TIPSA_BULTOS')) == "1") {

        	$bultos_si = "checked=\"checked\"";

        }

        

        // CALCULAR PRECIO APARTIR DE: PESO O IMPORTE CARRITO

        $precio_x_peso = "";

        $precio_x_importe = "";

        if(Tools::getValue('tipsa_precio_por', Configuration::get('TIPSA_CALCULAR_PRECIO')) == "0") {

        	$precio_x_peso = "checked=\"checked\"";

        }

        if(Tools::getValue('tipsa_precio_por', Configuration::get('TIPSA_CALCULAR_PRECIO')) == "1") {

        	$precio_x_importe = "checked=\"checked\"";

        }

        

        

        $tipsa_manipulacion_fijo = "";

        $tipsa_manipulacion_variable = "";

        if(Tools::getValue('tipsa_manipulacion', Configuration::get('TIPSA_MANIPULACION')) == "F") {

            $tipsa_manipulacion_fijo = "selected=\"selected\"";

        }

        if(Tools::getValue('tipsa_manipulacion', Configuration::get('TIPSA_MANIPULACION')) == "V") {

            $tipsa_manipulacion_variable = "selected=\"selected\"";

        }



        $ruta_csv = _PS_MODULE_DIR_.'tipsacarrier/tipsa.tarifas.csv';

        $ruta_csv2 = _PS_MODULE_DIR_.'tipsacarrier/tipsa.tarifas.importe.carrito.csv';        



        $this->_html .= '</fieldset><div class="clear">&nbsp;</div>

			<style>

				#tabList { clear: left; }

				.tabItem { display: block; background: #FFFFF0; border: 1px solid #CCCCCC; padding: 10px; padding-top: 20px; }

                                .columna1 { width:320px;text-align:right;font-weight:bold;padding-bottom:15px;display:table-cell;vertical-align:top; }

                                .columna2 { text-align:left;padding-left:20px; }

                                .tip {color: #7F7F7F;font-size: 0.85em;}

			</style>

			<div id="tabList">

				<div class="tabItem">

					<form action="index.php?tab='.Tools::getValue('tab').'&configure='.Tools::getValue('configure').'&token='.Tools::getValue('token').'&tab_module='.Tools::getValue('tab_module').'&module_name='.Tools::getValue('module_name').'&id_tab=1&section=general" method="post" class="form" id="configForm">



					<fieldset style="border: 0px;">

						<h4>'.$this->l('General configuration').' :</h4>

                                                <table style="border: 0px;">

                                                    <tr>

                                                        <td class="columna1">'.$this->l('Codigo Agencia').' : </td>

                                                        <td class="columna2">

                                                            <input type="text" size="5" name="tipsa_codigo_agencia" value="'.Tools::getValue('tipsa_codigo_agencia', Configuration::get('TIPSA_CODIGO_AGENCIA')).'" />

                                                            <p class="tip">Código de centro de servicio TIPSA</p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td class="columna1">'.$this->l('Codigo Cliente').' : </td>

                                                        <td class="columna2">

                                                            <input type="text" size="5" name="tipsa_codigo_cliente" value="'.Tools::getValue('tipsa_codigo_cliente', Configuration::get('TIPSA_CODIGO_CLIENTE')).'" />

                                                            <p class="tip">Código de cuenta TIPSA</p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td class="columna1">'.$this->l('Password Cliente').' : </td>

                                                        <td class="columna2">

                                                            <input type="text" size="15" name="tipsa_password_cliente" value="'.Tools::getValue('tipsa_password_cliente', Configuration::get('TIPSA_PASSWORD_CLIENTE')).'" />

                                                            <p class="tip">Password de cuenta TIPSA</p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td class="columna1">'.$this->l('URL WS').' : </td>

                                                        <td class="columna2">

                                                            <input type="text" size="15" name="tipsa_url" value="'.Tools::getValue('tipsa_url', Configuration::get('TIPSA_URL')).'" />

                                                            <p class="tip"WebService, solicítalo en tu centro de servicio TIPSA . 916699191></p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                    	<td colspan="2"><hr/></td>

                                                    </tr>

                                                    <tr>

                                                        <td class="columna1">'.$this->l('Habilitar envio Gratuito (ES,PT,AD)').' :</td>

                                                        <td class="columna2">

                                                            <input type="radio" name="tipsa_envio_gratuito" value="0" '.$envio_gratuito_no.'/>No&nbsp;&nbsp;&nbsp;

                                                            <input type="radio" name="tipsa_envio_gratuito" value="1" '.$envio_gratuito_si.'/>Si

                                                            <p class="tip">Habilita el envío gratuito a los siguientes paises: ES-PT-AD.</p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                  		<td class="columna1">'.$this->l('Servicio para envio Gratuito (ES,PT,AD)').' : </td>

                                                        <td class="columna2">

                                                            <select name="tipsa_servicio_envio_gratuito">

                                                              <option '.$servicio_gratuito_nada.' value="0"> - elija un servicio - </option>

                                                              <option '.$servicio_gratuito_14.' value="14">TIPSA 14H</option>

                                                              <option '.$servicio_gratuito_10.' value="10">TIPSA 10H</option>

															  <option '.$servicio_gratuito_MV.' value="MV">TIPSA MV</option>

															  <option '.$servicio_gratuito_48.' value="48">TIPSA 48H</option>

                                                            </select>

                                                            <p class="tip">Servicio para envío gratuito a ES-PT-AD</p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td class="columna1">'.$this->l('Importe mínimo para envíos gratuitos (ES,PT,AD)').' : </td>

                                                        <td class="columna2">

                                                            <input type="text" size="15" name="tipsa_importe_minimo_envio_gratuito" value="'.Tools::getValue('tipsa_importe_minimo_envio_gratuito', Configuration::get('TIPSA_IMP_MIN_ENVIO_GRA')).'" />

                                                            <p class="tip">Importe mínimo de pedido para envío gratuito a ES-PT-AD</p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td class="columna1">'.$this->l('Visualizar envios NO gratuitos (ES,PT,AD)').' :</td>

                                                        <td class="columna2">

                                                            <input type="radio" name="tipsa_mostrar_todo" value="0" '.$mostrar_todo_no.'/>No&nbsp;&nbsp;&nbsp;

                                                            <input type="radio" name="tipsa_mostrar_todo" value="1" '.$mostrar_todo_si.'/>Si

                                                            <p class="tip">Visualizar también los envios no gratuitos para los siguientes paises: ES-PT-AD.</p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                    	<td colspan="2"><hr/></td>

                                                    </tr>

                                                    

                                                    <tr>

                                                    	<td colspan="2"><hr/></td>

                                                    </tr>

                                                    <tr>

                                                        <td class="columna1">'.$this->l('Habilitar servicio 14H').' : </td>

                                                        <td class="columna2">

                                                            <input type="radio" name="tipsa_servicio_14" value="0" '.$envio_servicio_14_no.'/>No&nbsp;&nbsp;&nbsp;

                                                            <input type="radio" name="tipsa_servicio_14" value="14" '.$envio_servicio_14_si.'/>Si

                                                            <p class="tip">Habilita 14 HORAS como método de envío este código de servicio. </p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td class="columna1">'.$this->l('Habilitar servicio 10H').' : </td>

                                                        <td class="columna2">

                                                            <input type="radio" name="tipsa_servicio_10" value="0" '.$envio_servicio_10_no.'/>No&nbsp;&nbsp;&nbsp;

                                                            <input type="radio" name="tipsa_servicio_10" value="10" '.$envio_servicio_10_si.'/>Si

                                                            <p class="tip">Habilita 10 HORAS como método de envío este código de servicio. </p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td class="columna1">'.$this->l('Habilitar servicio MV').' : </td>

                                                        <td class="columna2">

                                                            <input type="radio" name="tipsa_servicio_MV" value="0" '.$envio_servicio_MV_no.'/>No&nbsp;&nbsp;&nbsp;

                                                            <input type="radio" name="tipsa_servicio_MV" value="MV" '.$envio_servicio_MV_si.'/>Si

                                                            <p class="tip">Habilita MASIVO como método de envío este código de servicio. </p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td class="columna1">'.$this->l('Habilitar servicio 48H').' : </td>

                                                        <td class="columna2">

                                                            <input type="radio" name="tipsa_servicio_48" value="0" '.$envio_servicio_48_no.'/>No&nbsp;&nbsp;&nbsp;

                                                            <input type="radio" name="tipsa_servicio_48" value="48" '.$envio_servicio_48_si.'/>Si

                                                            <p class="tip">Habilita 48 HORAS como método de envío este código de servicio. </p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                    	<td colspan="2"><hr/></td>

                                                    </tr>

                                                    <tr>

                                                        <td class="columna1">'.$this->l('Bultos por envío').' :</td>

                                                        <td class="columna2">

                                                            <input type="radio" name="tipsa_bultos" value="0" '.$bultos_no.'/>Fijo&nbsp;&nbsp;&nbsp;

                                                            <input type="radio" name="tipsa_bultos" value="1" '.$bultos_si.'/>Variable

                                                            <p class="tip">Configuración de bultos por envío fijo o variable según numero de artículos.</p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td class="columna1">'.$this->l('Número de artículos por bultos fijo').' : </td>

                                                        <td class="columna2">

                                                            <input type="text" size="5" name="tipsa_num_fijo_bultos" default="1" value="'.Tools::getValue('tipsa_num_fijo_bultos', Configuration::get('TIPSA_FIJO_BULTOS')).'" />

                                                            <p class="tip">Indique el número de artículos por bulto.</p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td class="columna1">'.$this->l('Número de artículos por bultos variable').' : </td>

                                                        <td class="columna2">

                                                            <input type="text" size="5" name="tipsa_num_articulos" value="'.Tools::getValue('tipsa_num_articulos', Configuration::get('TIPSA_NUM_BULTOS')).'" />

                                                            <p class="tip">Indique el número de artículos por bulto.</p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                    	<td colspan="2"><hr/></td>

                                                    </tr>

                                                    

                                                    <tr>

                                                        <td class="columna1">'.$this->l('Calcular precio de envio por').' :</td>

                                                        <td class="columna2">

                                                            <input type="radio" name="tipsa_precio_por" value="0" '.$precio_x_peso.'/>Peso&nbsp;&nbsp;&nbsp;

                                                            <input type="radio" name="tipsa_precio_por" value="1" '.$precio_x_importe.'/>Precio Carrito

                                                            <p class="tip">Calcula el precio del envio por el peso total o por el precio del carrito de compras del usuario.</p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                    	<td colspan="2"><hr/></td>

                                                    </tr>

                                                    <tr>

                                                        <td class="columna1">'.$this->l('Impuesto agregado').' : </td>

                                                        <td class="columna2">

                                                            <input type="text" size="5" name="tipsa_impuesto_agregado" value="'.Tools::getValue('tipsa_impuesto_agregado', Configuration::get('TIPSA_IMPUESTO')).'" />

                                                            <p class="tip">Impuesto agregado. Modo de uso, si quiere poner un 18% deberá escribir 0.18</p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td class="columna1">'.$this->l('Coste fijo de envío').' : </td>

                                                        <td class="columna2">

                                                            <input type="text" size="5" name="tipsa_coste_fijo_envio" value="'.Tools::getValue('tipsa_coste_fijo_envio', Configuration::get('TIPSA_COSTE_FIJO_ENVIO')).'" />

                                                            <p class="tip">Precio fijo por envío</p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td class="columna1">'.$this->l('Manipulación').' : </td>

                                                        <td class="columna2">

                                                            <select name="tipsa_manipulacion">

                                                              <option '.$tipsa_manipulacion_fijo.' value="F">Fijo</option>

                                                              <option '.$tipsa_manipulacion_variable.' value="V">Variable</option>

                                                            </select>

                                                            <p class="tip">Sistema de cálculo para el cargo de manipulación</p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td class="columna1">'.$this->l('Coste de manipulación').' : </td>

                                                        <td class="columna2">

                                                            <input type="text" size="15" name="tipsa_coste_manipulacion" value="'.Tools::getValue('tipsa_coste_manipulacion', Configuration::get('TIPSA_COSTE_MANIPULACION')).'" />

                                                            <p class="tip">Coste manipulación. (0 – sin coste)</p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td class="columna1">'.$this->l('Margen sobre coste de envío').' : </td>

                                                        <td class="columna2">

                                                            <input type="text" size="15" name="tipsa_margen_coste_envio" value="'.Tools::getValue('tipsa_margen_coste_envio', Configuration::get('TIPSA_MARGEN_COSTE_ENVIO')).'" />

                                                            <p class="tip">Margen de incremento sobre tarifa de coste de envío</p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td class="columna1">'.$this->l('Sobrescribir Código Postal Origen').' : </td>

                                                        <td class="columna2">

                                                            <input type="text" size="15" name="tipsa_sobreescribir_cp" value="'.Tools::getValue('tipsa_sobreescribir_cp', Configuration::get('TIPSA_SOBRE_CP')).'" />

                                                            <p class="tip">Escriba el Código Postal para sobreescribir el existente.</p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td class="columna1">'.$this->l('Ruta o path al archivo de tárifas por peso').' : </td>

                                                        <td class="columna2">

                                                            <p>'.$ruta_csv.'</p>

                                                            <p class="tip">Archivo de tarifas tipsa.tarifas.csv, edita el archivo para personalizar las tarifas de los servicios de TIPSA. AVISO IMPORTANTE se deberá respetar el formato del archivo .csv, sin cambiar el nombre del mismo. Utiliza un editor de archivos .csv (ej. MS Excel)</p>

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td class="columna1">'.$this->l('Ruta del archivo de tárifas por precio carrito').' : </td>

                                                        <td class="columna2">

                                                            <p>'.$ruta_csv2.'</p>

                                                            <p class="tip">Archivo de tarifas tipsa.tarifas.importe.carrito.csv, edita el archivo para personalizar las tarifas de los servicios de TIPSA. AVISO IMPORTANTE se deberá respetar el formato del archivo .csv, sin cambiar el nombre del mismo. Utiliza un editor de archivos .csv (ej. MS Excel)</p>

                                                        </td>

                                                    </tr>												

                                                </table>

					<br /><br />

				</fieldset>				

				<div class="margin-form"><input class="button" name="submitSave" type="submit"></div> 

			</form>

		</div></div>';

    }



    private function _postValidation() {

    	// Check configuration values

        if(Tools::getValue('tipsa_codigo_agencia') == '' && 

			Tools::getValue('tipsa_codigo_cliente') == '' &&

            Tools::getValue('tipsa_password_cliente') == '' && 

            Tools::getValue('tipsa_url') == '')

            $this->_postErrors[]  = $this->l('Necesita configurar correctamente: código agencia, código cliente, password cliente y URL del Web Service.');

    }



    private function _postProcess() {

        // Saving new configurations

        if (Configuration::updateValue('TIPSA_CODIGO_AGENCIA', Tools::getValue('tipsa_codigo_agencia')) &&

            Configuration::updateValue('TIPSA_CODIGO_CLIENTE', Tools::getValue('tipsa_codigo_cliente')) &&

            Configuration::updateValue('TIPSA_PASSWORD_CLIENTE', Tools::getValue('tipsa_password_cliente')) &&

            Configuration::updateValue('TIPSA_URL', Tools::getValue('tipsa_url')) &&

        		

            Configuration::updateValue('TIPSA_ENVIO_GRAT', Tools::getValue('tipsa_envio_gratuito')) &&

            Configuration::updateValue('TIPSA_SERVICIO_GRAT', Tools::getValue('tipsa_servicio_envio_gratuito')) &&

            Configuration::updateValue('TIPSA_IMP_MIN_ENVIO_GRA', Tools::getValue('tipsa_importe_minimo_envio_gratuito')) &&

        	Configuration::updateValue('TIPSA_RESTO', Tools::getValue('tipsa_mostrar_todo')) &&

        		

           // Configuration::updateValue('TIPSA_ENVIO_GRAT_INT', Tools::getValue('tipsa_envio_gratuito_internacional')) &&

           // Configuration::updateValue('TIPSA_SERVICIO_GRAT_INT', Tools::getValue('tipsa_servicio_envio_gratuito_internacional')) &&

           // Configuration::updateValue('TIPSA_IMP_MIN_ENVIO_GRAT_INT', Tools::getValue('tipsa_importe_minimo_envio_gratuito_internacional')) &&

        	// Configuration::updateValue('TIPSA_INTER_RESTO', Tools::getValue('tipsa_mostrar_todo_internacional')) &&

        		

            Configuration::updateValue('TIPSA_14H', Tools::getValue('tipsa_servicio_14')) &&

            Configuration::updateValue('TIPSA_10H', Tools::getValue('tipsa_servicio_10')) &&

            Configuration::updateValue('TIPSA_MV', Tools::getValue('tipsa_servicio_MV')) &&

            Configuration::updateValue('TIPSA_48H', Tools::getValue('tipsa_servicio_48')) &&

        		

        	Configuration::updateValue('TIPSA_BULTOS', Tools::getValue('tipsa_bultos')) &&

        	Configuration::updateValue('TIPSA_FIJO_BULTOS', Tools::getValue('tipsa_num_fijo_bultos')) &&

        	Configuration::updateValue('TIPSA_NUM_BULTOS', Tools::getValue('tipsa_num_articulos')) &&

        	

        	Configuration::updateValue('TIPSA_CALCULAR_PRECIO', Tools::getValue('tipsa_precio_por')) &&

        		

            Configuration::updateValue('TIPSA_IMPUESTO', Tools::getValue('tipsa_impuesto_agregado')) &&

            Configuration::updateValue('TIPSA_COSTE_FIJO_ENVIO', Tools::getValue('tipsa_coste_fijo_envio')) &&

            Configuration::updateValue('TIPSA_MANIPULACION', Tools::getValue('tipsa_manipulacion')) &&

            Configuration::updateValue('TIPSA_COSTE_MANIPULACION', Tools::getValue('tipsa_coste_manipulacion')) &&

            Configuration::updateValue('TIPSA_MARGEN_COSTE_ENVIO', Tools::getValue('tipsa_margen_coste_envio')) &&

            Configuration::updateValue('TIPSA_SOBRE_CP', Tools::getValue('tipsa_sobreescribir_cp')) )

            $this->_html .= $this->displayConfirmation($this->l('Configuración actualizada'));

        else

            $this->_html .= $this->displayErrors($this->l('Error al actualizar la configuración'));

    }





    /*

	** Hook update carrier

	**

    */



    public function hookupdateCarrier($params) {

        if ((int)($params['id_carrier']) == (int)(Configuration::get('MYCARRIER1_CARRIER_ID')))

            Configuration::updateValue('MYCARRIER1_CARRIER_ID', (int)($params['carrier']->id));

        if ((int)($params['id_carrier']) == (int)(Configuration::get('MYCARRIER2_CARRIER_ID')))

            Configuration::updateValue('MYCARRIER2_CARRIER_ID', (int)($params['carrier']->id));

        if ((int)($params['id_carrier']) == (int)(Configuration::get('MYCARRIER3_CARRIER_ID')))

            Configuration::updateValue('MYCARRIER3_CARRIER_ID', (int)($params['carrier']->id));

        if ((int)($params['id_carrier']) == (int)(Configuration::get('MYCARRIER4_CARRIER_ID')))

            Configuration::updateValue('MYCARRIER4_CARRIER_ID', (int)($params['carrier']->id));

    }









    /*

	** Front Methods

	**

	** If you set need_range at true when you created your carrier (in install method), the method called by the cart will be getOrderShippingCost

	** If not, the method called will be getOrderShippingCostExternal

	**

	** $params var contains the cart, the customer, the address

	** $shipping_cost var contains the price calculated by the range in carrier tab

	**

    */



    public function getOrderShippingCost($params, $shipping_cost) {

    	global $cart;

    	

    	//obtenemos el tipo de tarifas 0=por peso 1=por carrito

    	$tipo_tarifa = Configuration::get('TIPSA_CALCULAR_PRECIO');

    	//obtenemos el costo total del carrito

    	$total = $params->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING);

    	$peso = $params->getTotalWeight();    	

    	if($peso<1){

    		$peso=1;

    	}

    	//obtenemos los datos del usuario

    	$usuario_direccion_id = $params->id_address_delivery;

    	$query = 'SELECT * FROM '._DB_PREFIX_.'address where id_address = "'.$usuario_direccion_id.'"';

    	$usuario_datos = Db::getInstance()->ExecuteS($query);

    	$query='SELECT iso_code FROM '._DB_PREFIX_.'country where id_country = "'.$usuario_datos[0]['id_country'].'"';

    	$usuario_pais_id = Db::getInstance()->ExecuteS($query);

    	$usuario_pais = $usuario_pais_id[0]['iso_code'];

    	$usuario_cp =$usuario_datos[0]['postcode'];

    	

    	//Hay que agregar el IVA???

    	$iva = 0;

    	if($this->agregar_impuesto($usuario_pais)){

    		$iva = Configuration::get('TIPSA_IMPUESTO');

    	}

    	//Cargamos las tarifas del CSV

    	$tarifas = $this->tarifas();

    	$tarifas2 = $this->tarifas2();

    	

    	//Preparamos los demas parametros

        if(Configuration::get('TIPSA_COSTE_FIJO_ENVIO')){

        	$coste_fijo_envio = floatval(Configuration::get('TIPSA_COSTE_FIJO_ENVIO'));

        }

        if(Configuration::get('TIPSA_MANIPULACION') == 'F'){

            $coste_manipulacion = floatval(Configuration::get('TIPSA_COSTE_MANIPULACION'));

        }

        if(Configuration::get('TIPSA_MANIPULACION') == 'V'){

            $coste_manipulacion = floatval($total*(Configuration::get('TIPSA_COSTE_MANIPULACION')/100));

        }

        if(Configuration::get('TIPSA_MARGEN_COSTE_ENVIO')){

            $coste_margen = floatval(Configuration::get('TIPSA_MARGEN_COSTE_ENVIO'));

        }

    	

    	

        // This example returns shipping cost with overcost set in the back-office, but you can call a webservice or calculate what you want before returning the final value to the Cart

        

        // Filtramos para 14horas

        if ($this->id_carrier == (int)(Configuration::get('MYCARRIER1_CARRIER_ID')) && ($usuario_pais == 'ES' || $usuario_pais == 'PT' || $usuario_pais == 'AD')){

        	// Es gratuito???

        	if(Configuration::get('TIPSA_ENVIO_GRAT') && $total > Configuration::get('TIPSA_IMP_MIN_ENVIO_GRA')){

        		if(Configuration::get('TIPSA_SERVICIO_GRAT') == '14'){

        			return 0;

        		}

        		// Esta habilitado ver resto de servicios NOTA AARON: AÑADIR EL RESTO DE TIPOS DE SERVICIO

        		if(Configuration::get('TIPSA_RESTO') && Configuration::get('TIPSA_SERVICIO_GRAT') == '10' && Configuration::get('TIPSA_SERVICIO_GRAT') == 'MV' && Configuration::get('TIPSA_SERVICIO_GRAT') == '48'){

        			$importe = 0;

        			$subimporte=0;

        			if(Configuration::get('TIPSA_COSTE_FIJO_ENVIO')){

        				$subimporte = $coste_fijo_envio;

        				$coste_envio = $coste_fijo_envio + ($coste_fijo_envio * $iva);

        				$importe = floatval($coste_envio + $coste_manipulacion);

        			}

        			else{

        				//Si no hay coste_fijo buscamos en el csv el precio

        				//necesitamos el tipo_servicio, cp_cliente, pais y peso

        				if($tipo_tarifa){

        					$coste_envio = $this->dame_tarifa2($tarifas2, "14", $usuario_pais, $usuario_cp, $total);

        				}

        				else{

        					$coste_envio = $this->dame_tarifa($tarifas, "14", $usuario_pais, $usuario_cp, $peso);

        				}

        				$subimporte=$coste_envio;

        				//Sumamos el MARGEN SOBRE COSTE DE ENVÍO

        				if(Configuration::get('TIPSA_MARGEN_COSTE_ENVIO')){

        					$coste_envio=$coste_envio+$coste_margen;

        				}

        				$coste_envio = $coste_envio +($coste_envio*$iva);

        				$importe = floatval($coste_envio+$coste_manipulacion);

        			}

        			//formateamos el importe

        			$importe = number_format($importe,2,".","");

        			return (float)$importe;

        		}

        		return false;        		

        	}

            // No es gratuito 

            if(Configuration::get('TIPSA_14H')){        	

                $importe = 0;

                $subimporte=0;

                if(Configuration::get('TIPSA_COSTE_FIJO_ENVIO')){

                    $subimporte = $coste_fijo_envio;

                    $coste_envio = $coste_fijo_envio + ($coste_fijo_envio * $iva);

                    $importe = floatval($coste_envio + $coste_manipulacion);

                }

                else{

                    //Si no hay coste_fijo buscamos en el csv el precio

                    //necesitamos el tipo_servicio, cp_cliente, pais y peso

                	if($tipo_tarifa){

                		$coste_envio = $this->dame_tarifa2($tarifas2, "14", $usuario_pais, $usuario_cp, $total);

                	}

                	else{

                		$coste_envio = $this->dame_tarifa($tarifas, "14", $usuario_pais, $usuario_cp, $peso);

                	}                	

                    $subimporte=$coste_envio;

                    //Sumamos el MARGEN SOBRE COSTE DE ENVÍO

                    if(Configuration::get('TIPSA_MARGEN_COSTE_ENVIO')){

                        $coste_envio=$coste_envio+$coste_margen;

                    }

                    $coste_envio = $coste_envio +($coste_envio*$iva);

                    $importe = floatval($coste_envio+$coste_manipulacion);

                }

                //formateamos el importe

                $importe = number_format($importe,2,".","");

                return (float)$importe;

            }        	

            return false;

        }

        // Filtramos para 10HORAS

        if ($this->id_carrier == (int)(Configuration::get('MYCARRIER2_CARRIER_ID')) && ($usuario_pais == 'ES' || $usuario_pais == 'PT' || $usuario_pais == 'AD')){

        	// Es gratuito???

        	if(Configuration::get('TIPSA_ENVIO_GRAT') && $total > Configuration::get('TIPSA_IMP_MIN_ENVIO_GRA')){

        		if(Configuration::get('TIPSA_SERVICIO_GRAT') == '10'){

        			return 0;

        		}

        		// Esta habilitado ver resto de servicios

         		if(Configuration::get('TIPSA_RESTO') && Configuration::get('TIPSA_SERVICIO_GRAT') == '14' && Configuration::get('TIPSA_SERVICIO_GRAT') == 'MV' && Configuration::get('TIPSA_SERVICIO_GRAT') == '48'){

       		

        			$importe = 0;

        			$subimporte=0;

        			if(Configuration::get('TIPSA_COSTE_FIJO_ENVIO')){

        				$subimporte = $coste_fijo_envio;

        				$coste_envio = $coste_fijo_envio + ($coste_fijo_envio * $iva);

        				$importe = floatval($coste_envio + $coste_manipulacion);

        			}

        			else{

        				//Si no hay coste_fijo buscamos en el csv el precio

        				//necesitamos el tipo_servicio, cp_cliente, pais y peso

        				if($tipo_tarifa){

        					$coste_envio = $this->dame_tarifa2($tarifas2, "10", $usuario_pais, $usuario_cp, $total);

        				}

        				else{

        					$coste_envio = $this->dame_tarifa($tarifas, "10", $usuario_pais, $usuario_cp, $peso);

        				}

        				$subimporte=$coste_envio;

        				//Sumamos el MARGEN SOBRE COSTE DE ENVÍO

        				if(Configuration::get('TIPSA_MARGEN_COSTE_ENVIO')){

        					$coste_envio=$coste_envio+$coste_margen;

        				}

        				$coste_envio = $coste_envio +($coste_envio*$iva);

        				$importe = floatval($coste_envio+$coste_manipulacion);

        			}

        			//formateamos el importe

        			$importe = number_format($importe,2,".","");

        			return (float)$importe;

        		}

        		return false;

        	}

            // No es gratuito

            if(Configuration::get('TIPSA_10H')){           

                $importe = 0;

                $subimporte=0;

                if(Configuration::get('TIPSA_COSTE_FIJO_ENVIO')){

                    $subimporte = $coste_fijo_envio;

                    $coste_envio = $coste_fijo_envio + ($coste_fijo_envio * $iva);

                    $importe = floatval($coste_envio + $coste_manipulacion);

                }

                else{

                    //Si no hay coste_fijo buscamos en el csv el precio

                    //necesitamos el tipo_servicio, cp_cliente, pais y peso

                	if($tipo_tarifa){

                		$coste_envio = $this->dame_tarifa2($tarifas2, "10", $usuario_pais, $usuario_cp, $total);

                	}

                	else{

                		$coste_envio = $this->dame_tarifa($tarifas, "10", $usuario_pais, $usuario_cp, $peso);

                	}

                	$subimporte=$coste_envio;

                    //Sumamos el MARGEN SOBRE COSTE DE ENVÍO

                    if(Configuration::get('TIPSA_MARGEN_COSTE_ENVIO')){

                        $coste_envio=$coste_envio+$coste_margen;

                    }

                    $coste_envio = $coste_envio +($coste_envio*$iva);

                    $importe = floatval($coste_envio+$coste_manipulacion);

                }

                //formateamos el importe

                $importe = number_format($importe,2,".","");

                return (float)$importe;

            }           

            return false;

        }        

        // Filtramos para MV

        if ($this->id_carrier == (int)(Configuration::get('MYCARRIER3_CARRIER_ID')) && ($usuario_pais == 'ES' || $usuario_pais == 'PT' || $usuario_pais == 'AD')){

        	// Es gratuito???

        	if(Configuration::get('TIPSA_ENVIO_GRAT') && $total > Configuration::get('TIPSA_IMP_MIN_ENVIO_GRA')){

        		if(Configuration::get('TIPSA_SERVICIO_GRAT') == 'MV'){

        			return 0;

        		}

        		// Esta habilitado ver resto de servicios

         		if(Configuration::get('TIPSA_RESTO') && Configuration::get('TIPSA_SERVICIO_GRAT') == '14' && Configuration::get('TIPSA_SERVICIO_GRAT') == '10' && Configuration::get('TIPSA_SERVICIO_GRAT') == '48'){

       		

        			$importe = 0;

        			$subimporte=0;

        			if(Configuration::get('TIPSA_COSTE_FIJO_ENVIO')){

        				$subimporte = $coste_fijo_envio;

        				$coste_envio = $coste_fijo_envio + ($coste_fijo_envio * $iva);

        				$importe = floatval($coste_envio + $coste_manipulacion);

        			}

        			else{

        				//Si no hay coste_fijo buscamos en el csv el precio

        				//necesitamos el tipo_servicio, cp_cliente, pais y peso

        				if($tipo_tarifa){

        					$coste_envio = $this->dame_tarifa2($tarifas2, "MV", $usuario_pais, $usuario_cp, $total);

        				}

        				else{

        					$coste_envio = $this->dame_tarifa($tarifas, "MV", $usuario_pais, $usuario_cp, $peso);

        				}

        				$subimporte=$coste_envio;

        				//Sumamos el MARGEN SOBRE COSTE DE ENVÍO

        				if(Configuration::get('TIPSA_MARGEN_COSTE_ENVIO')){

        					$coste_envio=$coste_envio+$coste_margen;

        				}

        				$coste_envio = $coste_envio +($coste_envio*$iva);

        				$importe = floatval($coste_envio+$coste_manipulacion);

        			}

        			//formateamos el importe

        			$importe = number_format($importe,2,".","");

        			return (float)$importe;

        		}

        		return false;

        	}

            // No es gratuito

            if(Configuration::get('TIPSA_MV')){           

                $importe = 0;

                $subimporte=0;

                if(Configuration::get('TIPSA_COSTE_FIJO_ENVIO')){

                    $subimporte = $coste_fijo_envio;

                    $coste_envio = $coste_fijo_envio + ($coste_fijo_envio * $iva);

                    $importe = floatval($coste_envio + $coste_manipulacion);

                }

                else{

                    //Si no hay coste_fijo buscamos en el csv el precio

                    //necesitamos el tipo_servicio, cp_cliente, pais y peso

                	if($tipo_tarifa){

                		$coste_envio = $this->dame_tarifa2($tarifas2, "MV", $usuario_pais, $usuario_cp, $total);

                	}

                	else{

                		$coste_envio = $this->dame_tarifa($tarifas, "MV", $usuario_pais, $usuario_cp, $peso);

                	}

                	$subimporte=$coste_envio;

                    //Sumamos el MARGEN SOBRE COSTE DE ENVÍO

                    if(Configuration::get('TIPSA_MARGEN_COSTE_ENVIO')){

                        $coste_envio=$coste_envio+$coste_margen;

                    }

                    $coste_envio = $coste_envio +($coste_envio*$iva);

                    $importe = floatval($coste_envio+$coste_manipulacion);

                }

                //formateamos el importe

                $importe = number_format($importe,2,".","");

                return (float)$importe;

            }           

            return false;

        }        

        // Filtramos para 48H



        if ($this->id_carrier == (int)(Configuration::get('MYCARRIER4_CARRIER_ID')) && ($usuario_pais == 'ES' || $usuario_pais == 'PT' || $usuario_pais == 'AD')){

        	// Es gratuito???

        	if(Configuration::get('TIPSA_ENVIO_GRAT') && $total > Configuration::get('TIPSA_IMP_MIN_ENVIO_GRA')){

        		if(Configuration::get('TIPSA_SERVICIO_GRAT') == '48'){

        			return 0;

        		}

        		// Esta habilitado ver resto de servicios

         		if(Configuration::get('TIPSA_RESTO') && Configuration::get('TIPSA_SERVICIO_GRAT') == '14' && Configuration::get('TIPSA_SERVICIO_GRAT') == 'MV' && Configuration::get('TIPSA_SERVICIO_GRAT') == '10'){

       		

        			$importe = 0;

        			$subimporte=0;

        			if(Configuration::get('TIPSA_COSTE_FIJO_ENVIO')){

        				$subimporte = $coste_fijo_envio;

        				$coste_envio = $coste_fijo_envio + ($coste_fijo_envio * $iva);

        				$importe = floatval($coste_envio + $coste_manipulacion);

        			}

        			else{

        				//Si no hay coste_fijo buscamos en el csv el precio

        				//necesitamos el tipo_servicio, cp_cliente, pais y peso

        				if($tipo_tarifa){

        					$coste_envio = $this->dame_tarifa2($tarifas2, "48", $usuario_pais, $usuario_cp, $total);

        				}

        				else{

        					$coste_envio = $this->dame_tarifa($tarifas, "48", $usuario_pais, $usuario_cp, $peso);

        				}

        				$subimporte=$coste_envio;

        				//Sumamos el MARGEN SOBRE COSTE DE ENVÍO

        				if(Configuration::get('TIPSA_MARGEN_COSTE_ENVIO')){

        					$coste_envio=$coste_envio+$coste_margen;

        				}

        				$coste_envio = $coste_envio +($coste_envio*$iva);

        				$importe = floatval($coste_envio+$coste_manipulacion);

        			}

        			//formateamos el importe

        			$importe = number_format($importe,2,".","");

        			return (float)$importe;

        		}

        		return false;

        	}

            // No es gratuito

            if(Configuration::get('TIPSA_48H')){           

                $importe = 0;

                $subimporte=0;

                if(Configuration::get('TIPSA_COSTE_FIJO_ENVIO')){

                    $subimporte = $coste_fijo_envio;

                    $coste_envio = $coste_fijo_envio + ($coste_fijo_envio * $iva);

                    $importe = floatval($coste_envio + $coste_manipulacion);

                }

                else{

                    //Si no hay coste_fijo buscamos en el csv el precio

                    //necesitamos el tipo_servicio, cp_cliente, pais y peso

                	if($tipo_tarifa){

                		$coste_envio = $this->dame_tarifa2($tarifas2, "48", $usuario_pais, $usuario_cp, $total);

                	}

                	else{

                		$coste_envio = $this->dame_tarifa($tarifas, "48", $usuario_pais, $usuario_cp, $peso);

                	}

                	$subimporte=$coste_envio;

                    //Sumamos el MARGEN SOBRE COSTE DE ENVÍO

                    if(Configuration::get('TIPSA_MARGEN_COSTE_ENVIO')){

                        $coste_envio=$coste_envio+$coste_margen;

                    }

                    $coste_envio = $coste_envio +($coste_envio*$iva);

                    $importe = floatval($coste_envio+$coste_manipulacion);

                }

                //formateamos el importe

                $importe = number_format($importe,2,".","");

                return (float)$importe;

            }           

            return false;

        }        

        

        // If the carrier is not known, you can return false, the carrier won't appear in the order process

        return false;

    }



    public function getOrderShippingCostExternal($params) {}



    function agregar_impuesto($pais){



        $paises = Array("AT","BE","BG","CC","CY","CZ","DK","EE","FI",

                        "FR","DE","GR","HU","IE","IT","LV","LT","LU",

                        "MT","NL","PL","PT","RO","SK","SI","ES","SE","GB");

        $max=count($paises);

        for($i=0;$i<$max;$i++){

            if($pais == $paises[$i]){

                return true;

            }

        }

        return false;

    }

    

    // Esta funcion devuelve un array con las tarifas segun PESO pedido

    protected function tarifas(){

    	$archivo = _PS_MODULE_DIR_.'tipsacarrier/tipsa.tarifas.csv';

        

        $tarifas = Array();



        if($fp = fopen ( $archivo , "r" )){

            while (( $data = fgetcsv ( $fp , 1000 , ";" )) !== FALSE ) { // Mientras hay líneas que leer...

                $tarifas[] = Array( "servicio"      => $data[0],

                                    "pais"          => $data[1],

                                    "cp_origen"     => $data[2],

                                    "cp_destino"    => $data[3],

                                    "peso"          => $data[4],

                                    "importe"       => $data[5]);

            }

            fclose ( $fp );

            return $tarifas;

        }

        else{

            return false;

        }

    }

    

    // Esta funcion devuelve un array con las tarifas segun IMPORTE DEL CARRITO DE COMPRAS pedido

    protected function tarifas2(){

    	$archivo = _PS_MODULE_DIR_.'tipsacarrier/tipsa.tarifas.importe.carrito.csv';

    

    	$tarifas = Array();

    

    	if($fp = fopen ( $archivo , "r" )){

    		while (( $data = fgetcsv ( $fp , 1000 , ";" )) !== FALSE ) { // Mientras hay líneas que leer...

    			$tarifas[] = Array( "servicio"      => $data[0],

    					"pais"          => $data[1],

    					"cp_origen"     => $data[2],

    					"cp_destino"    => $data[3],

    					"precio_carrito"=> $data[4],

    					"importe"       => $data[5]);

    		}

    		fclose ( $fp );

    		return $tarifas;

    	}

    	else{

    		return false;

    	}

    }

    // Devulve la tarifa segun el peso del pedido

    function dame_tarifa($tarifas,$servicio,$pais,$cp,$peso){

        $max=count($tarifas);

        $cp=intval($cp);

        $peso=ceil($peso); //redondeo para arriba

        $segmento = Array();



        for($i=1;$i<$max;$i++){

            // Si es un envio para ES-PT-AD

            if($servicio == '14' || $servicio == '10' || $servicio == 'MV' || $servicio == '48'){

                if($tarifas[$i]['servicio'] == $servicio){

                    if($tarifas[$i]['pais'] == $pais){

                        $cp_origen=intval($tarifas[$i]['cp_origen']);

                        $cp_destino=intval($tarifas[$i]['cp_destino']);

                        if($cp >= $cp_origen){

                            if($cp <= $cp_destino){

                                $segmento[]=Array("peso" => floatval($tarifas[$i]['peso']),"precio" => floatval($tarifas[$i]['importe']));

                            }

                        }

                    }

                }

            }

            //Servico Europeo o Internacional

            else{

                if($tarifas[$i]['servicio'] == $servicio){

                    if($tarifas[$i]['pais'] == $pais){

                        $segmento[]=Array("peso" => floatval($tarifas[$i]['peso']),"precio" => floatval($tarifas[$i]['importe']));

                    }

                }

            }

        }

        // ya tenemos el segmento



        // Metodo para ordenar arrays con arrays asociativos dentro

        if(!function_exists('ordenar')){

            function ordenar($x, $y){

                if ( $x['peso'] == $y['peso'] ){

                    return 0;

                }

                //ordenar de menor a mayor

                else if ( $x['peso'] < $y['peso'] ){

                    return -1;

                }

                else{

                    return 1;

                }

            }

        }



        // Ordenamos el segmento

        usort($segmento,'ordenar');

        // Preparamos los datos para el peso minimo y maximo

        $precio_envio = 0;

        $max=count($segmento);

        $peso_min = floatval($segmento[0]['peso']);

        $precio_min = floatval($segmento[0]['precio']);

        $peso_max = floatval($segmento[$max-2]['peso']);

        $precio_max = floatval($segmento[$max-2]['precio']);

        $precio_despues_max = floatval($segmento[$max-1]['precio']);



        if($peso <= $peso_min){

            $precio_envio = $precio_min;

        }

        else if($peso >= $peso_max){

            $peso_restante = $peso-$peso_max;

            $precio_restante = $peso_restante*$precio_despues_max;

            $precio_envio = $precio_max+$precio_restante;

        }

        else{

            for($i=0;$i<$max;$i++){

                if($peso != $segmento[$i]['peso']){

                    if($peso < $segmento[$i]['peso']){

                        $precio_envio = $segmento[$i]['precio'];

                        $i=$max;

                    }

                }

                else{ //es igual

                    $precio_envio = $segmento[$i]['precio'];

                    $i=$max;

                }

            }

        }

        return $precio_envio;

    }

    

    // Devulve la tarifa segun el importe del carrito

    function dame_tarifa2($tarifas,$servicio,$pais,$cp,$importe_carrito){

    	$max=count($tarifas);

    	$cp=intval($cp);

    	$importe_carrito=ceil($importe_carrito); //redondeo para arriba

    	$segmento = Array();

    

    	for($i=1;$i<$max;$i++){

    		// Si es un envio para ES-PT-AD

    		if($servicio == '14' || $servicio == '10' || $servicio == '48' || $servicio == 'MV'){

    			if($tarifas[$i]['servicio'] == $servicio){

    				if($tarifas[$i]['pais'] == $pais){

    					$cp_origen=intval($tarifas[$i]['cp_origen']);

    					$cp_destino=intval($tarifas[$i]['cp_destino']);

    					if($cp >= $cp_origen){

    						if($cp <= $cp_destino){

    							$segmento[]=Array("carrito" => floatval($tarifas[$i]['precio_carrito']),"precio" => floatval($tarifas[$i]['importe']));

    						}

    					}

    				}

    			}

    		}

    		//Servico Europeo o Internacional

    		else{

    			if($tarifas[$i]['servicio'] == $servicio){

    				if($tarifas[$i]['pais'] == $pais){

    					$segmento[]=Array("carrito" => floatval($tarifas[$i]['precio_carrito']),"precio" => floatval($tarifas[$i]['importe']));

    				}

    			}

    		}

    	}

    	// ya tenemos el segmento

    

    	// Metodo para ordenar arrays con arrays asociativos dentro

    	if(!function_exists('ordenar2')){

    		function ordenar2($x, $y){

    			if ( $x['carrito'] == $y['carrito'] ){

    				return 0;

    			}

    			//ordenar de menor a mayor

    			else if ( $x['carrito'] < $y['carrito'] ){

    				return -1;

    			}

    			else{

    				return 1;

    			}

    		}

    	}

    

    	// Ordenamos el segmento

    	usort($segmento,'ordenar2');

    	// Preparamos los datos para el peso minimo y maximo

    	$precio_envio = 0;

    	$max=count($segmento);

    	$carrito_min = floatval($segmento[0]['carrito']);

    	$precio_min = floatval($segmento[0]['precio']);

    	$carrito_max = floatval($segmento[$max-2]['carrito']);

    	$precio_max = floatval($segmento[$max-2]['precio']);

    

    	if($importe_carrito <= $carrito_min){

    		$precio_envio = $precio_min;

    	}

    	else{

    		for($i=0;$i<$max;$i++){

    			if($importe_carrito != $segmento[$i]['carrito']){

    				if($importe_carrito < $segmento[$i]['carrito']){

    					$precio_envio = $segmento[$i]['precio'];

    					$i=$max;

    				}

    			}

    			else{ //es igual

    				$precio_envio = $segmento[$i]['precio'];

    				$i=$max;

    			}

    		}

    	}

    	return $precio_envio;

    }

    

    function es_europeo($pais){

        $paises = Array("DE","AT","BE","BG","CC","DK","SK","SI","EE","FI","FR","GR","GG",

                        "NL","HU","IE","IT","LV","LI","LT","LU","MC","NO","PL","GB","CZ",

                        "RO","SM","SE","CH","VA");

        $max=count($paises);

        for($i=0;$i<$max;$i++){

            if($pais == $paises[$i]){

                return true;

            }

        }

        return false;

    }

    function pedidosTabla(){

	    global $cookie, $smarty;

	    

	    // primero inicializamos la tabla de tipsa envios

	    $this->inicializarTipsaEnvios();

	    // pasamos el token a la vista

	    $smarty->assign('tokenOrder', Tools::getAdminToken('AdminOrders'.(int)Tab::getIdFromClassName('AdminOrders').(int)$cookie->id_employee));

	    // preparamos el paginador

	    $countQuery = Db::getInstance()->ExecuteS('SELECT COUNT(o.id_order) AS allCmd FROM '._DB_PREFIX_.'orders o JOIN '._DB_PREFIX_.'carrier c ON c.id_carrier = o.id_carrier WHERE c.external_module_name = "tipsacarrier"');

	    // set pager

	    $perPage = 20;

	    $allPages = ceil($countQuery[0]['allCmd']/$perPage);

	    if($_GET['p'] == '')

	    {

	      $_GET['p'] = 1;

	    }

	    require_once(_PS_MODULE_DIR_.'tipsacarrier/lib/Pager.php');

	    $pager = new Pager(array('before' => 5,

	      'after' => 5, 'all' => $allPages,

	      'page' => $_GET['p'], 'perPage' => $perPage

	    ));      

	    $start = ((int)$_GET['p']-1)*$perPage;

	    $smarty->assign('pager', $pager->setPages());

	    $smarty->assign('page', (int)$_GET['p']);

	    // obtenemos todos los pedidos relacionados con TIPSA

	    $pedidos = Db::getInstance()->ExecuteS('SELECT o.id_order,o.module,o.total_paid_real,o.valid,o.date_add,c.name,e.*,

	       u.firstname,u.lastname FROM '._DB_PREFIX_.'orders o 

	       JOIN '._DB_PREFIX_.'carrier c ON c.id_carrier = o.id_carrier 

	       JOIN '._DB_PREFIX_.'tipsa_envios e ON e.id_envio_order = o.id_order 

	       JOIN '._DB_PREFIX_.'customer u ON u.id_customer = o.id_customer 

	       WHERE c.external_module_name = "tipsacarrier" 

	       ORDER BY o.id_order DESC 

	       LIMIT '.$start.', '.$perPage);

	    // creamos los diferentes enlaces para la vista

	    $pedidos2 = array();

	    $i=0;

	    foreach ($pedidos as $pedido){

	       //index.php?tab=AdminEnvoiMoinsCher&id_order={$order.idOrder}&option=tracking&token={$token}

	       if($pedido['valid']){

	           $pedidos[$i]['link_etiqueta'] = 'index.php?tab=AdminTipsa&id_order_envio='.$pedido['id_envio_order'].'&option=etiqueta&token='.Tools::getValue('token');

	           if($pedido['codigo_envio']){

	               $pedidos[$i]['link_cancelar'] = 'index.php?tab=AdminTipsa&id_order_envio='.$pedido['id_envio_order'].'&option=cancelar&token='.Tools::getValue('token');

	               $pedidos[$i]['link_envio_mail'] = 'index.php?tab=AdminTipsa&id_order_envio='.$pedido['id_envio_order'].'&option=envio&token='.Tools::getValue('token');

	           }

	           else{

	               $pedidos[$i]['link_cancelar'] = '';

	               $pedidos[$i]['link_envio_mail'] = '';

	           }

	       }

	       else{

	           $pedidos[$i]['link_etiqueta'] = '';

	           $pedidos[$i]['link_cancelar'] = '';

	       }

	       $pedidos[$i]['num_pedido'] = sprintf('%06d', $pedido['id_order']);	       

	       $i++;

	    }

	    // premaramos los path de los iconos

	    $smarty->assign('path_img_logo', $this->_path.'img/logo_tipsa.jpg');

	    $smarty->assign('path_img_track', $this->_path.'img/track.gif');

	    $smarty->assign('path_img_email', $this->_path.'img/email.gif');

	    $smarty->assign('path_img_cod_barras', $this->_path.'img/cod_barras.gif');

	    $smarty->assign('path_img_cancelar', $this->_path.'img/cancelar.gif');

	    $smarty->assign('token', Tools::getValue('token'));

        $smarty->assign('pedidos', $pedidos);

	    //$smarty->assign('isAdmin', true);

	    $smarty->assign('pagerTemplate', _PS_MODULE_DIR_.'tipsacarrier/pager_template.tpl');

	    

	    return $this->display(__FILE__, 'pedidos.tpl');    

    }

    

    function imprimirEtiquetas($id_pedido=0)

    {

        global $smarty, $cookie, $currentIndex;	

    

        // Antes de guardar verificamos que no este guardado este envio

        if($id_pedido){

            $resultado = Db::getInstance()->ExecuteS('SELECT codigo_barras FROM '._DB_PREFIX_.'tipsa_envios WHERE id_envio_order = "'.$id_pedido.'"');

                                    

            if($resultado[0]['codigo_barras'] == ""){

                $hay_track=false;

            }

            else{

            	$hay_track=true;

            }

        }

        else{

            // Si no llego por GET el id_order redireccionamos

            Tools::redirectAdmin($currentIndex.'&token='.Tools::getValue('token'));	

        }     

        

        if(!$hay_track){

            // Primero de todo nos logueamos si esto falla todo lo demas no tiene sentido

            $urlTipsa = Configuration::get('TIPSA_URL');

            $URL = $urlTipsa;



            $tipsaCodigoAgencia = Configuration::get('TIPSA_CODIGO_AGENCIA');

            $tipsaCodigoCliente = Configuration::get('TIPSA_CODIGO_CLIENTE');

            $tipsaPasswordCliente = Configuration::get('TIPSA_PASSWORD_CLIENTE');

            

            $XML='<?xml version="1.0" encoding="utf-8"?>

                  <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">

                      <soap:Body>

                         <LoginWSService___LoginCli>

                             <strCodAge>'.$tipsaCodigoAgencia.'</strCodAge>

                             <strCod>'.$tipsaCodigoCliente.'</strCod>

                             <strPass>'.$tipsaPasswordCliente.'</strPass>

                         </LoginWSService___LoginCli>

                      </soap:Body>

                  </soap:Envelope>';

            

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            curl_setopt($ch, CURLOPT_HEADER, FALSE);

            curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);

            curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);

            curl_setopt($ch, CURLOPT_URL, $URL );

            curl_setopt($ch, CURLOPT_POSTFIELDS, $XML );

            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));



            $postResult = curl_exec($ch);



            if (curl_errno($ch)) {

                TipsaLog::error('No se pudo llamar al ws de TIPSA para el metodo LoginCli.');

            }

            $xml = simplexml_load_string($postResult, NULL, NULL, "http://http://www.w3.org/2003/05/soap-envelope");

            $xml->registerXPathNamespace("abc","http://tempuri.org/");

            // hay excepciones desde el WS

            if($xml->xpath('//abc:faultstring')){

                foreach($xml->xpath('//abc:faultstring') as $error){

                    TipsaLog::error('TIPSA ERROR : '.$error);                    

                }

            }

            else{

                foreach ($xml->xpath('//abc:strSesion') as $item){

                    $id_sesion_cliente=$item;

                }

                // Necesario para el tracking

                foreach ($xml->xpath('//abc:strURLDetSegEnv') as $item){

                	$tipsa_url_seguimiento=$item;

            	}

                

            }

            // Ya tenemos el id_sesion

            // Vamos a por todos los datos necesarios para realizar el pedido

            $datos = Db::getInstance()->ExecuteS(

                'SELECT o.id_order,o.module,o.total_paid_real,c.name,u.email,a.firstname,

                a.lastname,a.address1,a.address2,a.postcode,a.other,a.city,a.phone,a.phone_mobile,z.iso_code

                FROM '._DB_PREFIX_.'orders AS o 

                JOIN '._DB_PREFIX_.'carrier AS c 

                JOIN '._DB_PREFIX_.'customer AS u 

                JOIN '._DB_PREFIX_.'address AS a 

                JOIN '._DB_PREFIX_.'country AS z  

                WHERE o.id_order = "'.$id_pedido.'" 

                AND c.id_carrier=o.id_carrier 

                AND u.id_customer = o.id_customer 

                AND a.id_address = o.id_address_delivery 

                AND a.id_country = z.id_country');

            

            // Obtenemos el tipo de servicio

            switch($datos[0]['name']){

                  case 'TIPSA-14H': 

                    $tipsa_tipo_servicio = '14';

                  break;

                  case 'TIPSA-10H': 

                    $tipsa_tipo_servicio = '10';

                  break;

                  case 'TIPSA-MV': 

                    $tipsa_tipo_servicio = 'MV';

                  break;

                  case 'TIPSA-48H': 

                    $tipsa_tipo_servicio = '48';

                  break;

            }

            //Obtenemos el peso y numero de productos

            $productos = Db::getInstance()->ExecuteS(

	            'SELECT product_quantity, product_weight FROM '._DB_PREFIX_.'order_detail 

	            where id_order = "'.$id_pedido.'"');

            $peso = 0;

            $num_productos = 0;

            foreach ($productos as $producto){

                $peso += floatval($producto['product_quantity'] * $producto['product_weight']);

                $num_productos += $producto['product_quantity'];

            }

            if($peso < 1){

                $peso=1;

            }

            $tipsa_peso_origen = $peso;

            

            // Calculamos el numero de paquetes para tipsa segun el num de articulos

            $tipsa_numero_paquetes = 1;

            $bultos = Configuration::get('TIPSA_BULTOS');

            //bultos fijos

            if($bultos == 0){

            	$num_articulos = Configuration::get('TIPSA_FIJO_BULTOS');

            	if($num_articulos == '' || $num_articulos == 0){

            		$num_articulos = 1;

            	}

            	$tipsa_numero_paquetes = intval($num_articulos);

            }

            

            //bultos variables

            if($bultos == 1){

            	$num_articulos = Configuration::get('TIPSA_NUM_BULTOS');

            	if($num_articulos == '' || $num_articulos == 0){

            		$num_articulos = 1;

            	}

            	$tipsa_numero_paquetes = ceil($num_productos / $num_articulos);

            }

            

            // Obtenemos el num de pedido

            $tipsa_referencia = sprintf('%06d', $datos[0]['id_order']);

            

            //Obtenemos el importe total del pedido

            $tipsa_importe_servicio = $datos[0]['total_paid_real'];

            

            //Datos del comprador

            $tipsa_nombre_destinatario       = $datos[0]['firstname'].' '.$datos[0]['lastname'];

            $tipsa_nombre_via_destinatario   = $datos[0]['address1'].'/'.$datos[0]['address2'];;

            $tipsa_poblacion_destinatario    = $datos[0]['city'];

            $tipsa_CP_destinatario           = $datos[0]['postcode'];

            //$tipsa_cod_provincia_destinatario= $dir_pedido->getRegion();

            $tipsa_telefono_destinatario     = $datos[0]['phone'];

			$tipsa_telefono_destinatarioMV	 = $datos[0]['phone_mobile'];

            $tipsa_email_destinatario        = $datos[0]['email'];

            $tipsa_pais                      = $datos[0]['iso_code'];

			

            $observa = Db::getInstance()->ExecuteS(

                'SELECT o.id_order,o.module,o.total_paid_real,c.name,u.email,a.firstname,

                a.lastname,a.address1,a.address2,a.postcode,a.other,a.city,a.phone,a.phone_mobile,z.iso_code,m.message 

                FROM '._DB_PREFIX_.'orders AS o 

                JOIN '._DB_PREFIX_.'carrier AS c 

                JOIN '._DB_PREFIX_.'customer AS u 

                JOIN '._DB_PREFIX_.'address AS a 

                JOIN '._DB_PREFIX_.'country AS z 

				JOIN '._DB_PREFIX_.'message AS m 

                WHERE o.id_order = "'.$id_pedido.'" 

                AND c.id_carrier=o.id_carrier 

                AND u.id_customer = o.id_customer 

                AND a.id_address = o.id_address_delivery 

                AND a.id_country = z.id_country

				AND m.id_order = o.id_order');

			

			$observaciones                   = $observa[0]['message'];



            //HAY QUE CONTROLAR SI EL COMPRADOR A ELEGIDO CONTRAREEMBOLSO Y PONERLO EN EL PARAMETRO

            $metodo_pago = $datos[0]['module'];

            if($metodo_pago == 'cashondelivery'){

                $tipsa_reembolso=floatval($tipsa_importe_servicio);

            }

            else{

                $tipsa_reembolso = 0;

            }



            //controlar de que pais es el comprador

            if($tipsa_pais == 'ES' || $tipsa_pais == 'PT' || $tipsa_pais == 'AD'){

               // $tipsa_pais = '';

            }

            else{

                $tipsa_pais='<strCodPais>'.$tipsa_pais.'</strCodPais>';

            }

            

			

			if($tipsa_pais=='PT'){

				 

				 

				 $tipsa_port= explode ("-",$tipsa_CP_destinatario);

				 $int=$tipsa_port[0];

				  $tipsa_CP_destinatario="6".$int;

				

			}

				

				

			

			

			

			

			

			

			

            // Datos del vendedor o la tienda

            $vendedor = Configuration::getMultiple(array('PS_SHOP_NAME','PS_SHOP_EMAIL','PS_SHOP_ADDR1','PS_SHOP_ADDR2','PS_SHOP_CODE','PS_SHOP_CITY','PS_SHOP_COUNTRY_ID','PS_SHOP_STATE_ID','PS_SHOP_PHONE','PS_SHOP_FAX'));

            $tipsa_nombre_remitente          = $vendedor['PS_SHOP_NAME'];

            $tipsa_nombre_via_remitente      = $vendedor['PS_SHOP_ADDR1'];

            $tipsa_poblacion_remitente       = $vendedor['PS_SHOP_CITY'];

            //$tipsa_cod_provincia_remitente   = $vendedor['PS_SHOP_STATE_ID'];

            $tipsa_telefono_remitente        = $vendedor['PS_SHOP_PHONE'];

            // comprobar si tenemos que sobreescribir el CP

            if(Configuration::get('TIPSA_SOBRE_CP')){

                $tipsa_CP_remitente = Configuration::get('TIPSA_SOBRE_CP');

            }

            else{

                $tipsa_CP_remitente = $vendedor['PS_SHOP_CODE'];

            }

            

        	$URL = "HTTP://webservices.tipsa-dinapaq.com:8099/SOAP?service=WebServService";

			//Realizamos el pedido

            $XML='<?xml version="1.0" encoding="utf-8"?>

                <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">

                    <soap:Header>

                        <ROClientIDHeader xmlns="http://tempuri.org/">

                            <ID>'.$id_sesion_cliente.'</ID>

                        </ROClientIDHeader>

                    </soap:Header>

                    <soap:Body>

                        <WebServService___GrabaEnvio4 xmlns="http://tempuri.org/">

                            <strCodAgeCargo>'.$tipsaCodigoAgencia.'</strCodAgeCargo>

                            <strCodAgeOri>'.$tipsaCodigoAgencia.'</strCodAgeOri>

                            <dtFecha>'.date("Y/m/d").'</dtFecha>

                            <strCodTipoServ>'.$tipsa_tipo_servicio.'</strCodTipoServ>

                            <strCodCli>'.$tipsaCodigoCliente.'</strCodCli>

							<strCodCliDep>'.$tipsaDepartamentoCliente.'</strCodCliDep>

                            <strNomOri>'.$tipsa_nombre_remitente.'</strNomOri>

                            <strDirOri>'.$tipsa_nombre_via_remitente.'</strDirOri>

                            <strPobOri>'.$tipsa_poblacion_remitente.'</strPobOri>

                            <strCPOri>'.$tipsa_CP_remitente.'</strCPOri>

                            <strTlfOri>'.$tipsa_telefono_remitente.'</strTlfOri>

                            <strNomDes>'.$tipsa_nombre_destinatario.'</strNomDes>

                            <strDirDes>'.$tipsa_nombre_via_destinatario.'</strDirDes>

                            <strPobDes>'.$tipsa_poblacion_destinatario.'</strPobDes>

                            <strCPDes>'.$tipsa_CP_destinatario.'</strCPDes>

                            <strTlfDes>'.$tipsa_telefono_destinatario.'</strTlfDes>

                            <intPaq>'.$tipsa_numero_paquetes.'</intPaq>

                            <dPesoOri>'.$tipsa_peso_origen.'</dPesoOri>

                            <dReembolso>'.$tipsa_reembolso.'</dReembolso>

                            <strRef>'.$tipsa_referencia.'</strRef>

							<strObs>'.$observaciones.'</strObs>

                            <boDesEmail>1</boDesEmail>

                            <strDesMoviles>'.$tipsa_telefono_destinatarioMV.'</strDesMoviles>

                            <strDesDirEmails>'.$tipsa_email_destinatario.'</strDesDirEmails>

                            <boInsert>'.TRUE.'</boInsert>

                        </WebServService___GrabaEnvio4>

                    </soap:Body>

                </soap:Envelope>';

            

            TipsaLog::info("\n\rWS PETICION PARA PEDIDO\n\r".$XML);

            

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            curl_setopt($ch, CURLOPT_HEADER, FALSE);

            curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);

            curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);

            curl_setopt($ch, CURLOPT_URL, $URL );

            curl_setopt($ch, CURLOPT_POSTFIELDS, $XML );

            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));



            $postResult = curl_exec($ch);

            TipsaLog::info("\n\rWS RESPUESTA REALIZAR PEDIDO\n\r".$postResult);

            

            if (curl_errno($ch)) {

				TipsaLog::error('No se pudo llamar al ws de TIPSA para el metodo GrabaEnvio4.');

            }

            $xml = simplexml_load_string($postResult, NULL, NULL, "http://http://www.w3.org/2003/05/soap-envelope");

            $xml->registerXPathNamespace("abc","http://tempuri.org/");

            // hay excepciones desde el WS

            if($xml->xpath('//abc:faultstring')){

                foreach($xml->xpath('//abc:faultstring') as $error){

                    TipsaLog::error('TIPSA ERROR : '.$error);

                }

            }

            else{

                foreach ($xml->xpath('//abc:strAlbaranOut') as $item)

                {

                    $tipsa_num_albaran=$item;

                }

                foreach ($xml->xpath('//abc:strGuidOut') as $item)

                {

                    $tipsa_num_seguimiento=$item;

                }

                // primero tenemos que transformar el codigo de seguimiento



                $cod_tracking = substr($tipsa_num_seguimiento,1,36);

                if(!$cod_tracking){

                    TipsaLog::error('TIPSA ERROR : limpiarNumTrack');

                }

            }

            

			TipsaLog::error('NUMERO DE ALBARAN = '.$tipsa_num_albaran);			

            

            

            //Ahora podemos obtener el codigo de barras en PDF codificado en base64

					

					$XML='<?xml version="1.0" encoding="utf-8"?>

                    <soap:Envelope 

						xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" 	

						xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 

						xmlns:xsd="http://www.w3.org/2001/XMLSchema">

                        <soap:Header>

                            <ROClientIDHeader xmlns="http://tempuri.org/">

                                <ID>'.$id_sesion_cliente.'</ID>

                            </ROClientIDHeader>

                            </soap:Header>

                        <soap:Body>

                            <WebServService___ConsEtiquetaEnvio2>

                                <strAlbaran>'.$tipsa_num_albaran.'</strAlbaran>

								<intIdRepDet>99</intIdRepDet>

                            </WebServService___ConsEtiquetaEnvio2>

                        </soap:Body>

                    </soap:Envelope>';





            TipsaLog::info("\n\rWS PETICION DE ETIQUETA \n\r".$postResult);



            $ch = curl_init();

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            curl_setopt($ch, CURLOPT_HEADER, FALSE);

            curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);

            curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);

            curl_setopt($ch, CURLOPT_URL, $URL );

            curl_setopt($ch, CURLOPT_POSTFIELDS, $XML );

            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));



            $postResult = curl_exec($ch);



            TipsaLog::info("\n\rWS RESPUESTA CON ETIQUETA\n\r".$postResult);



            if (curl_errno($ch)) {

             	TipsaLog::error('No se pudo llamar al ws de TIPSA para el metodo ConsEtiquetaEnvio.');

            }

            $xml = simplexml_load_string($postResult, NULL, NULL, "http://http://www.w3.org/2003/05/soap-envelope");

            $xml->registerXPathNamespace("abc","http://tempuri.org/");

            // hay excepciones desde el WS

            if($xml->xpath('//abc:faultstring')){

                foreach($xml->xpath('//abc:faultstring') as $error){

                    TipsaLog::error('TIPSA ERROR : '.$error);

                }

            }

            else{

                foreach ($xml->xpath('//abc:strEtiqueta') as $item)

                {

                    $tipsa_etiqueta=$item;

                }

            }

            

			// Ya tenemos todos los datos necesarios para guardar en la tabla de envios

			if($ruta=$this->guardarEnvio($id_pedido, $cod_tracking, $tipsa_url_seguimiento, $tipsa_num_albaran, $tipsa_etiqueta)){

				//despues enviamos pdf codigo barras

				$smarty->assign('download_pdf', $ruta);				

			}

			else{

				$error = "";

				$ruta = "../modules/tipsacarrier/PDF";

				// comprobamos si la carpeta existe

				$existe = file_exists($ruta);				

				$error .= "<p>La carpeta modules/tipsacarrier/PDF existe = $existe</p>";

				// comprobamos los permisos

				$permisos = substr(sprintf('%o', fileperms($ruta)), -4);

				$error .= "<p>La carpeta modules/tipsacarrier/PDF permisos = $permisos</p>";				

				

				$smarty->assign('errores',$error);

			}

        }

        else{

        	//obtenemos la url de la etiqueta PDF ya registrado

        	$resultado = Db::getInstance()->ExecuteS('SELECT codigo_barras FROM '._DB_PREFIX_.'tipsa_envios where id_envio_order = "'.$id_pedido.'"');

	        $smarty->assign('download_pdf', $resultado[0]['codigo_barras']);        	

        }

        $smarty->assign('volver', '<a href="index.php?tab=AdminTipsa&token='.Tools::getValue('token').'"><strong>Volver</strong></a>');

       	$smarty->assign('path_img_logo', $this->_path.'img/logo_tipsa.jpg');



       	return $this->display(__FILE__, 'etiqueta.tpl');        

    }

    function cancelarEnvio($id_order)    

    {

    	global $smarty;

    	

    	//obtenemos el numero de albaran para poder cancelar pedido

    	$albaran = Db::getInstance()->ExecuteS('SELECT num_albaran FROM '._DB_PREFIX_.'tipsa_envios where id_envio_order = "'.$id_order.'"');

    	$tipsa_num_albaran = $albaran[0]['num_albaran'];

    	$html_error = false;

    	$mensaje = false;

    	

            // Primero de todo nos logueamos si esto falla todo lo demas no tiene sentido

            $urlTipsa = Configuration::get('TIPSA_URL');

            $URL = $urlTipsa;



            $tipsaCodigoAgencia = Configuration::get('TIPSA_CODIGO_AGENCIA');

            $tipsaCodigoCliente = Configuration::get('TIPSA_CODIGO_CLIENTE');

            $tipsaPasswordCliente = Configuration::get('TIPSA_PASSWORD_CLIENTE');

            

            $XML='<?xml version="1.0" encoding="utf-8"?>

                  <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">

                      <soap:Body>

                         <LoginWSService___LoginCli>

                             <strCodAge>'.$tipsaCodigoAgencia.'</strCodAge>

                             <strCod>'.$tipsaCodigoCliente.'</strCod>

                             <strPass>'.$tipsaPasswordCliente.'</strPass>

                         </LoginWSService___LoginCli>

                      </soap:Body>

                  </soap:Envelope>';

            TipsaLog::info($XML);

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            curl_setopt($ch, CURLOPT_HEADER, FALSE);

            curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);

            curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);

            curl_setopt($ch, CURLOPT_URL, $URL );

            curl_setopt($ch, CURLOPT_POSTFIELDS, $XML );

            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));



            $postResult = curl_exec($ch);



            //TipsaLog::info($postResult);

            

            if (curl_errno($ch)) {

                TipsaLog::error('No se pudo llamar al ws de TIPSA para el metodo LoginCli.');

                $html_error .= "<p>No se pudo llamar al ws de TIPSA para el metodo LoginCli.</p>";

            }

            $xml = simplexml_load_string($postResult, NULL, NULL, "http://http://www.w3.org/2003/05/soap-envelope");

            $xml->registerXPathNamespace("abc","http://tempuri.org/");

            // hay excepciones desde el WS            

            if($xml->xpath('//abc:faultstring')){

                foreach($xml->xpath('//abc:faultstring') as $error){

                    TipsaLog::error('TIPSA ERROR : '.$error);

                    $html_error .= '<p style="color:red;">'.$error.'</p>';                    

                }

            }

            else{

                foreach ($xml->xpath('//abc:strSesion') as $item){

                    $id_sesion_cliente=$item;

                }

                // Necesario para el tracking

                foreach ($xml->xpath('//abc:strURLDetSegEnv') as $item){

                $tipsa_url_seguimiento=$item;

            }

                

            }

			// Ya tenemos el id_sesion

            // Ahora obtenemos el estado



          	$URL = "HTTP://webservices.tipsa-dinapaq.com:8099/SOAP?service=WebServService";

		    $XML  ='<?xml version="1.0" encoding="utf-8"?>';

            $XML .='<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">';

            $XML .='<soap:Header>';

            $XML .='	<ROClientIDHeader xmlns="http://tempuri.org/">';

            $XML .='		<ID>'.$id_sesion_cliente.'</ID>';

            $XML .='	</ROClientIDHeader>';

            $XML .='</soap:Header>';

            $XML .='<soap:Body>';

            $XML .='	<WebServService___ConsEnvEstados xmlns="http://tempuri.org/">';

            $XML .='		<strCodAgeCargo>'.$tipsaCodigoAgencia.'</strCodAgeCargo>';

            $XML .='		<strCodAgeOri>'.$tipsaCodigoAgencia.'</strCodAgeOri>';

            $XML .='		<strAlbaran>'.$tipsa_num_albaran.'</strAlbaran>';

            $XML .='	</WebServService___ConsEnvEstados>';

            $XML .='</soap:Body>';

            $XML .='</soap:Envelope>';

            

			//TipsaLog::info($XML);

			

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            curl_setopt($ch, CURLOPT_HEADER, FALSE);

            curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);

            curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);

            curl_setopt($ch, CURLOPT_URL, $URL );

            curl_setopt($ch, CURLOPT_POSTFIELDS, $XML );

            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));



            $postResult = curl_exec($ch);

            //TipsaLog::info("\n\r RESPUESTA3333 \n\r".$postResult);

            

             if (curl_errno($ch)) {

                TipsaLog::error('No se pudo llamar al ws de TIPSA para el metodo ConsEnvEstados.');

                $html_error .= "<p>No se pudo llamar al ws de TIPSA para el metodo ConsEnvEstados.</p>";

            }





            $xml = simplexml_load_string($postResult, NULL, NULL, "http://http://www.w3.org/2003/05/soap-envelope");

            $xml->registerXPathNamespace("abc","http://tempuri.org/");

            // hay excepciones desde el WS

            if($xml->xpath('//abc:faultstring')){

                foreach($xml->xpath('//abc:faultstring') as $error){

                    TipsaLog::error('TIPSA ERROR : '.$error);

                    $html_error .= '<p style="color:red;">'.$error.'</p>';                    

            	}            	

            }

			foreach($xml->xpath('//abc:strEnvEstados') as $Estados){

				$estenv = explode('V_COD_TIPO_EST', $Estados);

				$elements = count($estenv);

				$esten = explode('"', $estenv[$elements-1]);

				if ($esten[1] == 1 || $esten[1] == 3)

				{

					if ($esten[1] == 3){

						Db::getInstance()->ExecuteS('INSERT INTO '._DB_PREFIX_.'order_history (id_employee, id_order,						                    	id_order_state,date_add) VALUES ("1", "'.$id_order.'", "5","'.date('Y-m-d H:i:s').'")');

        				Db::getInstance()->ExecuteS(

        				'UPDATE '._DB_PREFIX_.'orders SET  

        				current_state = "5"

        				WHERE id_order = "'.$id_order.'"');



						$mensaje = '<h3>Se ha actualizado el estado del envio a Entregado.</h3><p><br/><br/><br/></p>';

					}

					if ($esten[1] == 1){

						Db::getInstance()->ExecuteS('INSERT INTO '._DB_PREFIX_.'order_history (id_employee, id_order,						                    	id_order_state,date_add) VALUES ("1", "'.$id_order.'", "4","'.date('Y-m-d H:i:s').'")');

        				Db::getInstance()->ExecuteS(

        				'UPDATE '._DB_PREFIX_.'orders SET  

        				current_state = "4"

        				WHERE id_order = "'.$id_order.'"');



						

						$mensaje = '<h3>Se ha actualizado el estado del envio a transito.</h3><p><br/><br/><br/></p>';

					}

				}else{

					if ($esten[1] == "")

						$mensaje = '<h3>NO SE HA ENCONTRADO EN ENVIO.</h3><p><br/><br/><br/></p>';	

					else

						$mensaje = '<h3>EL ENVIO ESTA EN ESTADO: "'.$esten[1].'", NO SE ACTUALIZARA EN PRESTASHOP .</h3><p><br/><br/><br/></p>';		

				

				}//TipsaLog::info("\n\r ESTADO===== \n\r".$esten[1]);

				//TipsaLog::info("\n\r movida===== \n\r".$Estados);

            }



           

    	$smarty->assign('volver', '<a href="index.php?tab=AdminTipsa&token='.Tools::getValue('token').'"><strong>Volver</strong></a>');

        $smarty->assign('error', $html_error);	   

		$smarty->assign('mensaje', $mensaje);

		$smarty->assign('path_img_logo', $this->_path.'img/logo_tipsa.jpg');

		        

	    return $this->display(__FILE__, 'cancelar.tpl');

    }

    // Funcion encargada de insertar/actualizar el estado de un envio

    function guardarEnvio($id_order,$codigo_envio,$url_track,$num_albaran,$codigo_barras)

    {

    	

    	// preparamos para guardar el archivo pdf

	    $nombre = "etiqueta_".$id_order.".pdf"; 

    	$ruta   = "../modules/tipsacarrier/PDF/".$nombre;

    	$descodificar = base64_decode($codigo_barras);



		if(!$fp2 = fopen($ruta,"wb+")){

			TipsaLog::error("IMPOSIBLE ABRIR EL ARCHIVO $ruta \n\r");

			return false;

		} 

		if(!fwrite($fp2, trim($descodificar))){TipsaLog::error("IMPOSIBLE escribir EL ARCHIVO $ruta \n\r");} 

		fclose($fp2);		 	

    	

    	//preparamos la URL para el track

    	$fecha = date('d/m/y');

    	$cortar=split("\?",$url_track);

        $url_seguimiento=$cortar[0];

        $enlace=$url_seguimiento."?servicio=".$codigo_envio."&fecha=".$fecha;

        

        Db::getInstance()->ExecuteS(

        	'UPDATE '._DB_PREFIX_.'tipsa_envios SET  

        	codigo_envio = "'.$codigo_envio.'",

        	url_track = "'.$enlace.'",

        	num_albaran = "'.$num_albaran.'",

        	codigo_barras = "'.$ruta.'",

        	fecha = "'.date('Y-m-d H:i:s').'" 

        	WHERE id_envio_order = "'.$id_order.'"');

        

		Db::getInstance()->ExecuteS('UPDATE '._DB_PREFIX_.'orders SET shipping_number="'.$this->comprimir_num_track($codigo_envio).'" WHERE id_order = "'.$id_order.'"');



		return $ruta;

    }

    function comprimir_num_track($codigo)

    {

        $separar = split("-",$codigo);

        $comprimir="";

        foreach($separar as $linea){

            $comprimir.=$linea;

        }

        return $comprimir;

    }    

    function inicializarTipsaEnvios()

    {

    	// verificamos si hay pedidos sin registro de envio nuevo

        $envios = Db::getInstance()->ExecuteS('SELECT o.id_order FROM '._DB_PREFIX_.'orders o JOIN '._DB_PREFIX_.'carrier c ON c.id_carrier = o.id_carrier WHERE c.external_module_name = "tipsacarrier"');

        if(!$envios){

            return false;

        }

        foreach ($envios as $envio){

        	if(!Db::getInstance()->ExecuteS('SELECT id_envio_order FROM '._DB_PREFIX_.'tipsa_envios where id_envio_order = "'.$envio['id_order'].'"')){

        	   Db::getInstance()->ExecuteS('INSERT INTO '._DB_PREFIX_.'tipsa_envios (id_envio_order,codigo_envio,url_track,num_albaran) VALUES ("'.$envio['id_order'].'","","","")');

        	}            

        }

        return true;        

    }    

   /* function limpiarNumTrack($codigo)

    {

        if(!$codigo){

            return false;

        }

        $codigo = substr($codigo,1,36);

        return $codigo;

    }*/

    function enviarEmailTrack($id_pedido=false)

    {

    	global $smarty, $cookie;

		

    	$error = false;

		$resultado = false;

		$mensaje = false;

		

		if(!isset($_POST['mensaje'])){

			//cargamos mensaje anterior

            $datos = Db::getInstance()->ExecuteS('SELECT mensaje FROM '._DB_PREFIX_.'tipsa_email');

            $mensaje = $datos[0]['mensaje'];

            $url_form = 'index.php?tab=AdminTipsa&id_order_envio='.$id_pedido.'&option=envio&token='.Tools::getValue('token');

	        $smarty->assign('mensaje', $mensaje);

			$smarty->assign('formulario', true);

			$smarty->assign('url_formulario', $url_form);			

		}

		else{

			if($id_pedido){

		    	//obtenemos los datos necesarios del usuario

		            $datos = Db::getInstance()->ExecuteS(

		            	'SELECT o.id_order,u.firstname,u.lastname,u.email,e.url_track 

		            	FROM '._DB_PREFIX_.'orders AS o 

		            	JOIN '._DB_PREFIX_.'customer AS u 

		            	JOIN '._DB_PREFIX_.'tipsa_envios AS e 

		            	WHERE o.id_order = "'.$id_pedido.'" AND 

		            	u.id_customer = o.id_customer AND 

		            	e.id_envio_order = "'.$id_pedido.'"');

		            	    	

				$usuario_nombre    = $datos[0]['firstname'];

				$usuario_apellidos = $datos[0]['lastname'];

				$usuario_email     = $datos[0]['email'];

				$orden_pedido      = sprintf('%06d', $id_pedido);

				$asunto            = "Codigo seguimiento del pedido num. ".$orden_pedido;

				$enlace            = '<p><a href="'.$datos[0]['url_track'].'">Ver seguimiento</a></p>';

				$mensaje = $_POST['mensaje'].'<p>'.$enlace.'</p>';

				    	

		        if (Mail::Send(intval($cookie->id_lang),'order_customer_comment',$asunto,array('{firstname}' => $usuario_nombre,'{lastname}' => $usuario_apellidos,'{id_order}' => $orden_pedido,'{message}' => $mensaje),$usuario_email)){

		        	// Guardamos el nuevo mensaje

		        	Db::getInstance()->ExecuteS('UPDATE '._DB_PREFIX_.'tipsa_email SET mensaje="'.$_POST['mensaje'].'" WHERE id = "1"');		        	

		        	$resultado = '<p>Se envio la URL de seguimiento del pedido <b>'.$orden_pedido.'</b> correctamente al siguiente destinatario <b>'.$usuario_nombre.' '.$usuario_apellidos.'</b> al email <b>'.$usuario_email.'</b></p>';

		        }

		        else{

		            $error = Tools::displayError('Hubo un error al intentar enviar el mensaje a: '.$usuario_nombre.' '.$usuario_apellidos.' con el email: '.$usuario_email);

		        }

		        $smarty->assign('formulario', false);

			}

		}

    	

		$smarty->assign('volver', '<a href="index.php?tab=AdminTipsa&token='.Tools::getValue('token').'"><strong>Volver</strong></a>');

        $smarty->assign('error', $error);	   

		$smarty->assign('resultado', $resultado);

		$smarty->assign('path_img_logo', $this->_path.'img/logo_tipsa.jpg');

		        

	    return $this->display(__FILE__, 'seguimiento.tpl');

    }

}

