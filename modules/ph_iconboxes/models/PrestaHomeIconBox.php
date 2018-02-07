<?php
/**
* @author    Krystian Podemski <podemski.krystian@gmail.com>
* @copyright  Copyright (c) 2014 Krystian Podemski - www.PrestaHome.com
* @license    You only can use module, nothing more!
*/
require_once _PS_MODULE_DIR_ . 'ph_iconboxes/ph_iconboxes.php';

class PrestaHomeIconBox extends ObjectModel
{
	public $id;
	public $id_prestahome_iconbox;

	public $position;

	public $active = 1;
	public $access;
	public $columns;
	public $class;
	public $hook;
	public $icon;

	public $url;
	public $title;
	public $content;

	public static $definition = array(
		'table' => 'prestahome_iconbox',
		'primary' => 'id_prestahome_iconbox',
		'multilang' => true,
		'fields' => array(
			'position'          => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
			'active'            => array('type' => self::TYPE_BOOL),
			'access' 			=> array('type' => self::TYPE_STRING),
			'columns'           => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
			'class'             => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 50),
			'hook'              => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255),
			'icon'              => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255),
			
			'title'             => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 3999999999999),
			'content'           => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 3999999999999),
			'url'               => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'size' => 3999999999999, 'required' => false),
		),
	);

	public function __construct($id = null, $id_lang = null, $id_shop = null)
	{
		parent::__construct($id, $id_lang, $id_shop);
		if($this->id)
		{
		}
	}

	public  function add($autodate = true, $null_values = false)
	{
		$this->position = self::getNewLastPosition();

		$ret = parent::add($autodate, $null_values);
		return $ret;
	}

	public function delete()
	{
		if(parent::delete())
		{
			return $this->cleanPositions();
		}
		return false;
	}

	public function update($null_values = false)
	{
		return parent::update($null_values);
	}

	public static function getNbIcons()
	{
		return (int)Db::getInstance()->getValue('
			SELECT COUNT(*)
			FROM `'._DB_PREFIX_.'prestahome_iconbox` pib'
		);
	}

	public static function getNewLastPosition()
	{
		return (Db::getInstance()->getValue('SELECT IFNULL(MAX(position),0)+1 FROM `'._DB_PREFIX_.'prestahome_iconbox`'));
	}

	public function move($direction)
	{
		$nb_iconbox = self::getNbIcons();
		if ($direction != 'l' && $direction != 'r')
			return false;
		if ($nb_iconbox <= 1)
			return false;
		if ($direction == 'l' && $this->position <= 1)
			return false;
		if ($direction == 'r' && $this->position >= $nb_iconbox)
			return false;

		$new_position = ($direction == 'l') ? $this->position - 1 : $this->position + 1;
		Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'prestahome_iconbox` pib
			SET position = '.(int)$this->position.'
			WHERE position = '.(int)$new_position
		);
		$this->position = $new_position;
		return $this->update();
	}

	public function cleanPositions()
	{
		$result = Db::getInstance()->executeS('
			SELECT `id_prestahome_iconbox`
			FROM `'._DB_PREFIX_.'prestahome_iconbox`
			ORDER BY `position`
		');
		$sizeof = count($result);
		for ($i = 0; $i < $sizeof; ++$i)
			Db::getInstance()->execute('
				UPDATE `'._DB_PREFIX_.'prestahome_iconbox`
				SET `position` = '.($i + 1).'
				WHERE `id_prestahome_iconbox` = '.(int)$result[$i]['id_prestahome_iconbox']
			);
		return true;
	}

	public function updatePosition($way, $position)
	{
		if (!$res = Db::getInstance()->executeS('
			SELECT pib.`id_prestahome_iconbox`, pib.`position`
			FROM `'._DB_PREFIX_.'prestahome_iconbox` pib
			ORDER BY pib.`position` ASC'
		))
			return false;

		foreach ($res as $boxes)
			if ((int)$boxes['id_prestahome_iconbox'] == (int)$this->id)
				$moved_boxes = $boxes;

		if (!isset($moved_boxes) || !isset($position))
			return false;

		$result = (Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'prestahome_iconbox`
			SET `position`= `position` '.($way ? '- 1' : '+ 1').'
			WHERE `position`
			'.($way
				? '> '.(int)$moved_boxes['position'].' AND `position` <= '.(int)$position
				: '< '.(int)$moved_boxes['position'].' AND `position` >= '.(int)$position))
		&& Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'prestahome_iconbox`
			SET `position` = '.(int)$position.'
			WHERE `id_prestahome_iconbox`='.(int)$moved_boxes['id_prestahome_iconbox']));
		return $result;
	}

	public function isAccessGranted()
    {
        if ($userGroups = Context::getContext()->customer->getGroups())
        {
            if (!isset($this->id_prestahome_iconbox))
                return false;

            $tmpBoxGroups = unserialize($this->access);
            $boxes = array();

            foreach ($tmpBoxGroups as $groupID => $status)
            {
                if ($status)
                    $boxes[] = $groupID;
            }

            $intersect = array_intersect($userGroups, $boxes);
            if (count($intersect))
                return true;
            else
                return false;
        }
    }

    public static function getByHook($hook, $id_lang = null, $id_shop = null, $active = false) 
    {
        $id_lang = Context::getContext()->language->id;

		$boxes = DB::getInstance()->executeS('
			SELECT *
			FROM `'._DB_PREFIX_.'prestahome_iconbox` pib
			INNER JOIN `'._DB_PREFIX_.'prestahome_iconbox_lang` pibl
				ON (pib.`id_prestahome_iconbox` = pibl.`id_prestahome_iconbox` AND pibl.`id_lang` = '.(int)$id_lang.')
			INNER JOIN `'._DB_PREFIX_.'prestahome_iconbox_shop` pibs
				ON (pib.`id_prestahome_iconbox` = pibs.`id_prestahome_iconbox` AND pibs.`id_shop` = '.Context::getContext()->shop->id.')
			WHERE pib.`hook` = \''.$hook.'\' AND pib.active = 1
			ORDER BY pib.`position` ASC
		');
        
        return $boxes;
    }
}