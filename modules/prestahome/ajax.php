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

include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('prestahome.php');

$context = Context::getContext();

$action = Tools::getValue('action');

if($action == 'getSearchResults')
{
	$ajax_search = Tools::getValue('ajaxSearch');
	$query = urldecode(Tools::getValue('q'));

    if ($ajax_search)
    {
        $searchResults = Search::find((int)(Tools::getValue('id_lang')), $query, 1, 10, 'position', 'desc', true);
	                
        foreach ($searchResults as &$product)
        {
            $cover = Product::getCover($product['id_product']);           
            $product['product_link'] = $context->link->getProductLink($product['id_product'], $product['prewrite'], $product['crewrite']);
            $product['product_image'] = $context->link->getImageLink($product['prewrite'], $cover['id_image'], ImageType::getFormatedName('cart'));
        }
        
		die(Tools::jsonEncode($searchResults));
    }
}