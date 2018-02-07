<?php

class flashsaleproflashSaleProductsModuleFrontController extends ModuleFrontController
{
	public function initContent()
	{
		parent::initContent();
		$flash_sale = new FlashSalePro();

		$flash_sale->checkExpiredSales();
		$flash_sale_info = $flash_sale->getFlashSaleInfo();
		$this->context->smarty->assign('path', $flash_sale->l('Flash Sale').' - '.$flash_sale_info['name']);

		$flash_sale_items = '';

		if ($flash_sale_info != null)
		{
			$flash_sale_items = $flash_sale->getFlashSaleItems($flash_sale_info['id_flashsalespro']);
			
			$keys = array(
				'FLASHSALEPRO_TIMER_BG_COLOR',
				'FLASHSALEPRO_TIMER_TEXT_COLOR',
				'FLASHSALEPRO_TIMER_DOT_COLOR'
				);
			$configs = Configuration::getMultiple($keys);

			$image_default = $flash_sale->ps_url.'modules/'.$flash_sale->name.'/views/img/flash_sale_logo.png';
			$this->context->smarty->assign(array(
				'flash_sale_info' => $flash_sale_info,
				'flash_sale_items' => $flash_sale_items,
				'image_default' => $image_default,
				'timer_bg_color' => pSQL($configs['FLASHSALEPRO_TIMER_BG_COLOR']),
				'timer_text_color' => pSQL($configs['FLASHSALEPRO_TIMER_TEXT_COLOR']),
				'timer_dot_color' => pSQL($configs['FLASHSALEPRO_TIMER_DOT_COLOR']),
				'ps_url' => $flash_sale->ps_url,
				'ps_version' => (bool)version_compare(_PS_VERSION_, '1.6', '>'),
				'ps17' => $flash_sale->ps17
				));
		}
		$tpl = 'productList';
		if (!$flash_sale->ps17)
			$tpl = $tpl.'_16';
		$this->template = dirname(__FILE__).'/../../views/templates/front/'.$tpl.'.tpl';
	}
}
