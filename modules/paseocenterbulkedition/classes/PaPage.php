<?php
/**
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 * @author    Pronimbo.com
 * @copyright Pronimbo.com. all rights reserved.
 * @license   http://www.pronimbo.com/licenses/license_en.pdf http://www.pronimbo.com/licenses/license_es.pdf https://www.pronimbo.com/licenses/license_fr.pdf
 */

if (!defined('_PS_VERSION_')) exit;

class PaPage extends ObjectModel
{
	public $id_meta;
	public $page;
	public $meta_title;
	public $meta_description;
	public $meta_keywords;
	public $link_rewrite;
	public static $definition = array(
		'table' => 'paseocenter_pages',
		'primary' => 'id_paseocenter_pages',
		'multilang' => false,
		'fields' => array(
			'id_meta' => array('type' => self::TYPE_INT),
			'page' => array('type' => self::TYPE_STRING),
		),
	);

	public function __construct($id = null, $id_lang = null, $id_shop = null)
	{
		parent::__construct($id, false, $id_shop);

		if ($id > 0 && $this->id_meta == 0)
		{
			$meta = new Meta((int)self::getIdMetaByPage($this->page));
			$meta->page = $this->page;
			$meta->title = '';
			$meta->description = '';
			$meta->keywords = '';
			$meta->save();
			$this->id_meta = $meta->id;
			$this->save();
		}
		elseif ((int)$this->id_meta > 0)
		{
			$meta = new Meta($this->id_meta, $id_lang, $id_shop);
			$this->meta_title = $meta->title;
			$this->meta_description = $meta->description;
			$this->meta_keywords = (isset($meta->keywords) ? $meta->keywords : '');
			$this->link_rewrite = $meta->url_rewrite;
		}

	}

	public static function fillTable($truncate = false)
	{
		$files = Meta::getPages();
		$params = array();
		foreach ($files as $file)
		{
			$sql = new DBQuery();
			$sql->from('meta');
			$sql->where('page like \''.pSQL($file).'\'');
			$id_meta = (int)DB::getInstance()->getValue($sql);
			$params[] = array('id_meta' => $id_meta, 'page' => $file,);
		}
		if ($truncate) DB::getInstance()->execute('TRUNCATE '._DB_PREFIX_.self::$definition['table']);
		return DB::getInstance()->autoExecute(_DB_PREFIX_.self::$definition['table'], $params, 'REPLACE');
	}

	public function save($null_values = false, $autodate = true)
	{
		$meta = new Meta($this->id_meta);
		$meta->title = $this->meta_title;
		$meta->description = $this->meta_description;
		$meta->keywords = $this->meta_keywords;
		$meta->url_rewrite = $this->link_rewrite;
		$meta->page = $this->page;
		$res = $meta->save($null_values, $autodate);
		if ($res)
			$res = parent::save($null_values, $autodate);
		return $res;
	}

	public static function getIdByPageName($name)
	{
		$query = new DbQueryCore();
		$query->select('id_paseocenter_pages');
		$query->from('paseocenter_pages');
		$query->where('page like \''.pSQL($name).'\'');
		return (int)DB::getInstance()->getValue($query);
	}

	public static function getFormmatedIDByPageName($name)
	{
		return PaMeta::ENTITY_PAGE.str_pad(Context::getContext()->shop->id, 3, 0, STR_PAD_LEFT).self::getIdByPageName($name);
	}

	public static function getIdMetaByPage($page_name)
	{
		$query = new DbQuery();
		$query->select('id_meta');
		$query->from('meta');
		$query->where('page like \''.pSQL($page_name).'\'');
		return (int)DB::getInstance()->getValue($query);
	}
}