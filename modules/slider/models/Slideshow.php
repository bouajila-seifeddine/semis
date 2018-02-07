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

# TODO: Position by shop

class Slideshow extends ObjectModel
{
    public $id;
    public $id_slider_slides;
    public $id_shop;
    public $title;
    public $link;
    public $blank;
    public $image;
    public $content;
    public $button;
    public $active;
    public $position;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'slider_slides',
        'primary' => 'id_slider_slides',
        'multilang' => true,
        'fields' => array(
            'id_shop' => 		array('type' => self::TYPE_INT),
            'title' => 		    array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 64),
            'link' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'size' => 255),
            'blank' =>          array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'image' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255),
            'content' => 		array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
            'button' => 		array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 64),
            'active' =>         array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'position' => 		array('type' => self::TYPE_INT),
        ),
    );

    /**
     * Moves a product tab
     *
     * @param boolean $way Up (1) or Down (0)
     * @param integer $position
     * @return boolean Update result
     */
    public function updatePosition($way, $position)
    {
        if (!$res = Db::getInstance()->executeS('
			SELECT `id_slider_slides`, `position`
			FROM `'._DB_PREFIX_.'slider_slides`
			ORDER BY `position` ASC'
        ))
            return false;

        foreach ($res as $tab)
            if ((int)$tab['id_slider_slides'] == (int)$this->id)
                $moved_tab = $tab;

        if (!isset($moved_tab) || !isset($position))
            return false;

        $this->cleanPositions();

        // < and > statements rather than BETWEEN operator
        // since BETWEEN is treated differently according to databases
        return (Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'slider_slides`
			SET `position`= `position` '.($way ? '- 1' : '+ 1').'
			WHERE `position`
			'.($way
                    ? '> '.(int)$moved_tab['position'].' AND `position` <= '.(int)$position
                    : '< '.(int)$moved_tab['position'].' AND `position` >= '.(int)$position
                ))
            && Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'slider_slides`
			SET `position` = '.(int)$position.'
			WHERE `id_slider_slides` = '.(int)$moved_tab['id_slider_slides']));
    }

    /**
     * Reorders product tabs positions.
     * Called after deleting a product tab.
     *
     * @return bool $return
     */
    public static function cleanPositions()
    {
        $return = true;

        $sql = '
		SELECT `id_slider_slides`
		FROM `'._DB_PREFIX_.'slider_slides`
		ORDER BY `position` ASC';
        $result = Db::getInstance()->executeS($sql);

        $i = 0;
        foreach ($result as $value)
            $return = Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'slider_slides`
			SET `position` = '.(int)$i++.'
			WHERE `id_slider_slides` = '.(int)$value['id_slider_slides']);
        return $return;
    }

    public function add($autodate = true, $null_values = false)
    {
        if ($this->position <= 0)
            $this->position = $this->getHigherPosition() + 1;

        $this->id_shop = Context::getContext()->shop->id;

        return parent::add($autodate, $null_values);
    }

    /**
     * Gets the highest product tab position
     *
     * @return int $position
     */
    public static function getHigherPosition()
    {
        $sql = 'SELECT MAX(`position`) FROM `'._DB_PREFIX_.'slider_slides`';
        $position = DB::getInstance()->getValue($sql);
        return (is_numeric($position)) ? $position : -1;
    }

    /**
     * Get slides for Front Office display
     */
    public static function getSlides($id_lang = false, $id_shop = false, $active = true)
    {
        if(!$id_lang)
            $id_lang = Context::getContext()->language->id;

        if(!$id_shop)
            $id_shop = Context::getContext()->shop->id;

        $slides = new DbQuery();
        $slides->select('*');
        $slides->from('slider_slides', 's');
        $slides->innerJoin('slider_slides_lang', 'sl', 's.id_slider_slides = sl.id_slider_slides AND sl.id_lang = '.(int)$id_lang);
        if($active)
            $slides->where('s.active = 1');
        $slides->where('s.id_shop = '.$id_shop);
        $slides->orderBy('position');
        return Db::getInstance()->executeS($slides);
    }
}