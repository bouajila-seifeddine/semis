<?php
/**
* NOTICE OF LICENSE
*
*   This file is property of Petr Hucik. You may NOT redistribute the code in any way
*   See license.txt for the complete license agreement
*
* @author    Petr Hucik
* @website   https://www.getdatakick.com
* @copyright Petr Hucik <petr@getdatakick.com>
* @license   see license.txt
* @version   2.1.3
*/
namespace Datakick;

class ImagePathFunction extends Func {
    public function __construct() {
      parent::__construct('imagePath', 'string', array(
        'names' => array('productId'),
        'types' => array('number')
      ), false);
    }

    public function evaluate($args, $argsTypes, Context $context) {
        $imageId = $args[0];
        if ($imageId) {
          return _PS_PROD_IMG_DIR_ . \Image::getImgFolderStatic($imageId) . $imageId . ".jpg";
        } else {
          return '';
        }
    }

    public function jsEvaluate() {
      return 'return (""+productId).split("").join("/")+"/"+productId+".jpg";';
    }
}
