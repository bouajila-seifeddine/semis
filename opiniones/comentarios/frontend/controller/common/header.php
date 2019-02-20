<?php
namespace Commentics;

class CommonHeaderController extends Controller {
	public function index() {
		$this->data['commentics_url'] = $this->url->getCommenticsUrl();

		switch ($this->setting->get('jquery_source')) {
			case '':
				$this->data['jquery'] = '';
				break;
			case 'local':
				$this->data['jquery'] = $this->loadJavascript('jquery.min.js');
				break;
			case 'google':
				$this->data['jquery'] = '//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js';
				break;
			default:
				$this->data['jquery'] = '//code.jquery.com/jquery-1.12.4.min.js';
		}

		switch ($this->setting->get('jquery_ui_source')) {
			case '':
				$this->data['jquery_ui'] = '';
				$this->data['jquery_theme'] = '';
				break;
			case 'local':
				$this->data['jquery_ui'] = $this->loadJavascript('jquery-ui/jquery-ui.min.js');
				$this->data['jquery_theme'] = $this->loadJavascript('jquery-ui/jquery-ui.min.css');
				break;
			case 'google':
				$this->data['jquery_ui'] = '//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js';
				$this->data['jquery_theme'] = '//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.min.css';
				break;
			default:
				$this->data['jquery_ui'] = '//code.jquery.com/ui/1.12.1/jquery-ui.min.js';
				$this->data['jquery_theme'] = '//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.min.css';
		}

		switch ($this->setting->get('font_awesome_source')) {
			case '':
				$this->data['font_awesome'] = '';
				break;
			case 'local':
				$this->data['font_awesome'] = $this->data['commentics_url'] . '3rdparty/font_awesome/css/font-awesome.min.css';
				break;
			default:
				$this->data['font_awesome'] = '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';
		}

		if ($this->setting->get('show_read_more')) {
			$this->data['read_more'] = $this->data['commentics_url'] . '3rdparty/read_more/read_more.js';
		} else {
			$this->data['read_more'] = '';
		}

		if ($this->setting->get('enabled_upload')) {
			$this->data['filer'] = $this->data['commentics_url'] . '3rdparty/filer/filer.js';
		} else {
			$this->data['filer'] = '';
		}

		if ($this->setting->get('date_auto')) {
			$this->data['timeago'] = $this->data['commentics_url'] . '3rdparty/timeago/timeago.js';
		} else {
			$this->data['timeago'] = '';
		}

		if ($this->setting->get('enabled_captcha') && $this->setting->get('captcha_type') == 'recaptcha') {
			$this->data['recaptcha_api'] = 'https://www.google.com/recaptcha/api.js' . (($this->setting->get('recaptcha_language') == 'auto') ? '' : '?hl=' . $this->setting->get('recaptcha_language'));
		} else {
			$this->data['recaptcha_api'] = '';
		}

		if ($this->setting->get('colorbox_source') != '' && ($this->setting->get('enabled_upload') || ($this->setting->get('enabled_privacy') || $this->setting->get('enabled_terms')))) {
			$this->data['colorbox'] = true;
		} else {
			$this->data['colorbox'] = false;
		}

		if ($this->setting->get('enabled_bb_code') && ($this->setting->get('enabled_bb_code_code') || ($this->setting->get('enabled_bb_code_php')))) {
			$this->data['highlight'] = true;
		} else {
			$this->data['highlight'] = false;
		}

		$this->data['common'] = $this->loadJavascript('common.js');

		$this->data['stylesheet'] = $this->loadStylesheet('stylesheet.css');

		$this->data['custom'] = $this->loadCustomCss();
		$categoria1 = $this->security->encode(CMTX_CATEGORIA);
		$categoria2 = $this->security->encode(CMTX_CATEGORIA2);
		$categoria3 = $this->security->encode(CMTX_CATEGORIA3);



		$query = $this->db->query("SELECT DISTINCT p.`id_product`, p.`active`, pl.`link_rewrite`, pl.`name`, cl.`link_rewrite` AS `category_link`,c.`id_category`, c.`position`, i.`id_image`, cl.`name` AS `category_name`, pa.`id_product_attribute`, pa.`price` AS `price_attribute`, p.`price` FROM `ps_product` p LEFT JOIN `ps_product_lang` pl ON (p.`id_product` = pl.`id_product`) LEFT JOIN `ps_category_product` c ON (c.`id_product` = p.`id_product`) LEFT JOIN `ps_category_lang` cl ON (c.`id_category` = cl.`id_category`) LEFT JOIN `ps_image_shop` i ON (i.`id_product` = p.`id_product`) LEFT JOIN `ps_product_attribute` pa ON (pa.`id_product` = p.`id_product`) WHERE (c.`id_category` = ".$categoria1." OR c.`id_category` = ".$categoria2." OR c.`id_category` = ".$categoria3.") AND p.`active` = 1 AND pa.`default_on` = 1 AND cl.`id_lang` = 4 AND pl.`id_lang` = 4 GROUP BY p.`id_product` ORDER BY pl.`name` ");



		$result = $this->db->rows($query);


		
		$this->data['test'] = $result;
		$this->data['test1'] = $categoria1;
		$this->data['test2'] = $categoria2;
		$this->data['test3'] = $categoria3;





		return $this->data;
	}
}
?>