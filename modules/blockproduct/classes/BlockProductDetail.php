<?php


class BlockProductDetail extends ObjectModel
{
    public $id;
    public $id_product;
    public $block_ip;
    public $block_country;
    public $active;

    public static $definition = array(
            'table' => 'block_product_detail',
            'primary' => 'id_product',
            'fields' => array(
                'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt' ,'required' => true),
                'block_ip' => array('type' => self::TYPE_STRING),
                'block_country' => array('type' => self::TYPE_STRING),
                'active' => array('type' => self::TYPE_STRING),
            ),
        );

    public function checkBlockProduct($id_product)
    {
        return Db::getInstance()->getRow(
            'SELECT * FROM `'._DB_PREFIX_.'block_product_detail`
   			WHERE `id_product` = '.(int) $id_product
        );
    }

    public function getAllBlockProductId()
    {
        return Db::getInstance()->ExecuteS(
            'SELECT * FROM `'._DB_PREFIX_.'block_product_detail`'
        );
    }
}
