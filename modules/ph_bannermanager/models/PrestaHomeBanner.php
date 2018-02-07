<?php
/**
* @author    Krystian Podemski <podemski.krystian@gmail.com>
* @copyright  Copyright (c) 2014 Krystian Podemski - www.PrestaHome.com
* @license    You only can use module, nothing more!
*/
require_once _PS_MODULE_DIR_ . 'ph_bannermanager/ph_bannermanager.php';

class PrestaHomeBanner extends ObjectModel
{
	public $id;
	public $id_prestahome_banner;

	public $position;

	public $new_window;
	public $active = 1;
	public $access;
	public $columns;
	public $class;
	public $hook;

	public $url;
	public $title;
	public $image;

	public static $definition = array(
		'table' => 'prestahome_banner',
		'primary' => 'id_prestahome_banner',
		'multilang' => true,
		'fields' => array(
			'position'          => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
			'new_window'        => array('type' => self::TYPE_BOOL),
			'active'            => array('type' => self::TYPE_BOOL),
			'access' 			=> array('type' => self::TYPE_STRING),
			'columns'           => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
			'class'             => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 50),
			'hook'              => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255),
			
			'title'             => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 255),
			'url'               => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'size' => 3999999999999, 'required' => false),
			'image'             => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'size' => 3999999999999, 'required' => true),
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
			return $this->cleanPositions() && $this->removeBannerImages();
		}
		return false;
	}

	public static function getNbBanners()
	{
		return (int)Db::getInstance()->getValue('
			SELECT COUNT(*)
			FROM `'._DB_PREFIX_.'prestahome_banner` pb'
		);
	}

	public static function getNewLastPosition()
	{
		return (Db::getInstance()->getValue('SELECT IFNULL(MAX(position),0)+1 FROM `'._DB_PREFIX_.'prestahome_banner`'));
	}

	public function move($direction)
	{
		$nb_menus = self::getNbBanners();
		if ($direction != 'l' && $direction != 'r')
			return false;
		if ($nb_menus <= 1)
			return false;
		if ($direction == 'l' && $this->position <= 1)
			return false;
		if ($direction == 'r' && $this->position >= $nb_menus)
			return false;

		$new_position = ($direction == 'l') ? $this->position - 1 : $this->position + 1;
		Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'prestahome_banner` pb
			SET position = '.(int)$this->position.'
			WHERE position = '.(int)$new_position
		);
		$this->position = $new_position;
		return $this->update();
	}

	public function cleanPositions()
	{
		$result = Db::getInstance()->executeS('
			SELECT `id_prestahome_banner`
			FROM `'._DB_PREFIX_.'prestahome_banner`
			ORDER BY `position`
		');
		$sizeof = count($result);
		for ($i = 0; $i < $sizeof; ++$i)
			Db::getInstance()->execute('
				UPDATE `'._DB_PREFIX_.'prestahome_banner`
				SET `position` = '.($i + 1).'
				WHERE `id_prestahome_banner` = '.(int)$result[$i]['id_prestahome_banner']
			);
		return true;
	}

	public function updatePosition($way, $position)
	{
		if (!$res = Db::getInstance()->executeS('
			SELECT pb.`id_prestahome_banner`, pb.`position`
			FROM `'._DB_PREFIX_.'prestahome_banner` pb
			ORDER BY pb.`position` ASC'
		))
			return false;

		foreach ($res as $banners)
			if ((int)$banners['id_prestahome_banner'] == (int)$this->id)
				$moved_banners = $banners;

		if (!isset($moved_banners) || !isset($position))
			return false;

		$result = (Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'prestahome_banner`
			SET `position`= `position` '.($way ? '- 1' : '+ 1').'
			WHERE `position`
			'.($way
				? '> '.(int)$moved_banners['position'].' AND `position` <= '.(int)$position
				: '< '.(int)$moved_banners['position'].' AND `position` >= '.(int)$position))
		&& Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'prestahome_banner`
			SET `position` = '.(int)$position.'
			WHERE `id_prestahome_banner`='.(int)$moved_banners['id_prestahome_banner']));
		return $result;
	}

	public function update($null_values = false)
	{
		// $banner = new PrestaHomeBanner($this->id);
		// $this->position = self::getNewLastPosition();

		return parent::update($null_values);
	}

	public function isAccessGranted()
    {
        if ($userGroups = Context::getContext()->customer->getGroups())
        {
            if (!isset($this->id_prestahome_banner))
                return false;

            $tmpLinkGroups = unserialize($this->access);
            $linkGroups = array();

            foreach ($tmpLinkGroups as $groupID => $status)
            {
                if ($status)
                    $linkGroups[] = $groupID;
            }

            $intersect = array_intersect($userGroups, $linkGroups);
            if (count($intersect))
                return true;
            else
                return false;
        }
    }

    public static function getRelativeImagePath($append=null, $end_slash=true) {
        return self::getImagePath(false, $append, $end_slash);
    }
    
    public static function getServerImagePath($append = null, $end_slash = true) {
        return self::getImagePath(true, $append, $end_slash);
    }
    
    public static function getImagePath($type = false, $append = null, $end_slash = true) {
        if ($type)
            $path = _PS_MODULE_DIR_.'ph_bannermanager/banners';
        else
            $path = _MODULE_DIR_.'ph_bannermanager/banners';
        
        $dir_sep = $type ? DIRECTORY_SEPARATOR : '/';
        if (is_string($append) && $append !== '') {
            $path .= $dir_sep.$append;
        }
        if ($end_slash) {
            $path .= $dir_sep;
        }
        
        return $path;
    }

    public static function getByHook($hook, $id_lang = null, $id_shop = null, $active = false) {
        $id_lang = Context::getContext()->language->id;

		$banners = DB::getInstance()->executeS('
			SELECT *
			FROM `'._DB_PREFIX_.'prestahome_banner` pb
			INNER JOIN `'._DB_PREFIX_.'prestahome_banner_lang` pbl
				ON (pb.`id_prestahome_banner` = pbl.`id_prestahome_banner` AND pbl.`id_lang` = '.(int)$id_lang.')
			INNER JOIN `'._DB_PREFIX_.'prestahome_banner_shop` pbs
				ON (pb.`id_prestahome_banner` = pbs.`id_prestahome_banner` AND pbs.`id_shop` = '.Context::getContext()->shop->id.')
			WHERE pb.`hook` = \''.$hook.'\' AND pb.active = 1
			ORDER BY pb.`position` ASC
		');
        
        return $banners;
    }

    public function removeBannerImages() {
        if(!is_array($this->image))
        	$this->image = array($this->image);

        foreach ($this->image as $id_lang => $image) {
            $file = self::getServerImagePath(Language::getIsoById($id_lang)).$image;
            if (file_exists($file)) {
                @unlink($file);
            }
        }

        return true;
    }
}