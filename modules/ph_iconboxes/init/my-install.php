<?php

$icon_box = new PrestaHomeIconBox();
$icon_box->title = prepareValueForLangs('<strong>free</strong> shipping');
$icon_box->content = prepareValueForLangs('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse maximus mauris id.');
$icon_box->hook = 'displayTopColumn';
$icon_box->active = 1;
$icon_box->icon = 'car';
$icon_box->url = '';
$icon_box->columns = '4';
$icon_box->add();
$icon_box->associateTo(Shop::getCompleteListOfShopsID());
unset($icon_box);

$icon_box = new PrestaHomeIconBox();
$icon_box->title = prepareValueForLangs('<strong>all day</strong> online chat');
$icon_box->content = prepareValueForLangs('Suspendisse elementum volutpat viverra. In sodales mauris ut dolor interdum.');
$icon_box->hook = 'displayTopColumn';
$icon_box->active = 1;
$icon_box->icon = 'weixin';
$icon_box->url = '';
$icon_box->columns = '4';
$icon_box->add();
$icon_box->associateTo(Shop::getCompleteListOfShopsID());
unset($icon_box);

$icon_box = new PrestaHomeIconBox();
$icon_box->title = prepareValueForLangs('<strong>gifts</strong> from $1000');
$icon_box->content = prepareValueForLangs('Pellentesque nulla ex, suscipit vitae condimentum eget, sagittis sed orci et posuere.');
$icon_box->hook = 'displayTopColumn';
$icon_box->active = 1;
$icon_box->icon = 'gift';
$icon_box->url = '';
$icon_box->columns = '4';
$icon_box->add();
$icon_box->associateTo(Shop::getCompleteListOfShopsID());
unset($icon_box);



function prepareValueForLangs($value)
{
    $languages = Language::getLanguages(false);

    $output = array();

    foreach($languages as $lang)
    {
        $output[$lang['id_lang']] = $value;
    }

    return $output;
}