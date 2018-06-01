<?php
/*
* 2007-2014 PrestaShop
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
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class Inix2TreeToolbar  implements IInix2TreeToolbar
{
	const DEFAULT_TEMPLATE_DIRECTORY = 'helpers/tree';
	const DEFAULT_TEMPLATE           = 'tree_toolbar.tpl';

	private $_actions;
	private $_context;
	private $_data;
	private $_template;
	private $_template_directory;
	/**
	 * @var Inix2Module
	 */
	private $module;

	/**
	 * @param Inix2Module $module
	 *
	 * @return  Inix2TreeToolbar $this
	 */
	public function setModule( $module) {
		$this->module = $module;
		return $this;
	}

	/**
	 * @return Inix2Module
	 */
	public function getModule() {
		return $this->module;
	}
	public function __toString()
	{
		return $this->render();
	}

	public function setActions($actions)
	{
		if (!is_array($actions) && !$actions instanceof Traversable)
			throw new PrestaShopException('Action value must be an traversable array');

		foreach($actions as $action)
			$this->addAction($action);


		return $this;
	}

	public function getActions()
	{
		if (!isset($this->_actions))
			$this->_actions = array();

		return $this->_actions;
	}

	public function setContext($value)
	{
		$this->_context = $value;
		return $this;
	}

	public function getContext()
	{
		if (!isset($this->_context))
			$this->_context = Context::getContext();

		return $this->_context;
	}

	public function setData($value)
	{
		if (!is_array($value) && !$value instanceof Traversable)
			throw new PrestaShopException('Data value must be an traversable array');

		$this->_data = $value;
		return $this;
	}

	public function getData()
	{
		return $this->_data;
	}

	public function setTemplate($value)
	{
		$this->_template = $value;
		return $this;
	}

	public function getTemplate()
	{
		if (!isset($this->_template))
			$this->setTemplate(self::DEFAULT_TEMPLATE);

		return $this->_template;
	}

	public function setTemplateDirectory($value)
	{
		$this->_template_directory = $this->_normalizeDirectory($value);
		return $this;
	}

	public function getTemplateDirectory()
	{
		if (!isset($this->_template_directory))
			$this->_template_directory = $this->_normalizeDirectory(
				self::DEFAULT_TEMPLATE_DIRECTORY);

		return $this->_template_directory;
	}


	public function getTemplateFile($template)
	{
		if (preg_match_all('/((?:^|[A-Z])[a-z]+)/', get_class($this->getContext()->controller), $matches) !== FALSE)
			$controllerName = strtolower($matches[0][1]);

		if ($this->getContext()->controller instanceof Inix2AdminController AND  file_exists(
				$this->_normalizeDirectory(
					$this->getContext()->controller->getTemplatePath()).
				$controllerName.DIRECTORY_SEPARATOR.$this->getTemplateDirectory().$template
			)) {
			return $this->_normalizeDirectory(
				$this->getContext()->controller->getTemplatePath()).
			$controllerName.DIRECTORY_SEPARATOR.$this->getTemplateDirectory().$template;

		} elseif (file_exists(
			$this->_normalizeDirectory(
				$this->module->getLocalPath().'views/templates/inixframe/'
			).$this->getTemplateDirectory().$template
		)) {
			return $this->_normalizeDirectory(
				$this->module->getLocalPath().'views/templates/inixframe/'
			).$this->getTemplateDirectory().$template;

		} else {
			return 	$this->_normalizeDirectory(
				$this->module->getFrameLocalPath().'template/'
			).$this->getTemplateDirectory().$template;
		}
	}

	public function addAction($action)
	{
		if (!is_object($action))
			throw new PrestaShopException('Action must be a class object');

		$reflection = new ReflectionClass($action);

		if (!$reflection->implementsInterface('IInix2TreeToolbarButton'))
			throw new PrestaShopException('Action class must implements ITreeToolbarButtonCore interface');

		if (!isset($this->_actions))
			$this->_actions = array();

		if (isset($this->_template_directory))
			$action->setTemplateDirectory($this->getTemplateDirectory());

		$this->_actions[] = $action;
		return $this;
	}

	public function removeActions()
	{
		$this->_actions = null;
		return $this;
	}

	public function render()
	{
		foreach ($this->getActions() as $action)
			$action->setAttribute('data', $this->getData());

		return $this->getContext()->smarty->createTemplate(
			$this->getTemplateFile($this->getTemplate()),
			$this->getContext()->smarty
		)->assign('actions', $this->getActions())->fetch();
	}

	private function _normalizeDirectory($directory)
	{
		$last = $directory[strlen($directory) - 1];
        
        if (in_array($last, array('/', '\\'))) {
            $directory[strlen($directory) - 1] = DIRECTORY_SEPARATOR;
            return $directory;
        }
        
        $directory .= DIRECTORY_SEPARATOR;
        return $directory;
	}
}