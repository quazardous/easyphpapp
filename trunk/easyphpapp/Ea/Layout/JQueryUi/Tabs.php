<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  jQueryUi
 * @author      berlioz [$Author$]
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/JQueryUi/Abstract.php';

/**
 * jQuery UI Tabs element layout class.
 * 
 */
class Ea_Layout_JQueryUi_Tabs extends Ea_Layout_JQueryUi_Abstract
{
	/**
	 * jQueryUi Tabs layout constructor.
	 * 
	 * @param array $tabs content layouts for tabs
	 * @param string $id
	 * @param array $config associative array of parameters
	 * 
	 */
	public function __construct($id=null, $config=null)
	{
		parent::__construct($id, 'div', $config);
	}
	
	protected $_tabs=array();
	
	protected $_ul=null;
	
	/**
	 * Add a tab.
	 * 
	 * @param string|Ea_Layout_Abstract $title
	 * @param string|Ea_Layout_Abstract $content
	 * @param string $id
	 * @return Ea_Layout_Container the new li
	 */
	public function addTab($title, $content, $id=null)
	{
		require_once 'Ea/Layout/Link.php';
		require_once 'Ea/Layout/Container.php';
		if(!$id) $id=$this->nextAutoId('jq-tab');
		if(!$this->_ul)
		{
			$this->add($this->_ul=new Ea_Layout_Container('ul'));
		}
		$this->_ul->add($li=new Ea_Layout_Container('li'));
		$li->add($a=new Ea_Layout_Link("#{$id}"));
		$a->add($title);
		$this->add($div=new Ea_Layout_Container('div'));
		$this->_tabs[$id]=$div;
		$div->add($content);
		$div->setAttribute('id', $id);
		return $li;
	}
	
	protected function preRender()
	{
		$render=parent::preRender();
		$this->_setAttribute('id', $this->getId());
		return $render;
	}
	
	protected function render()
	{
		parent::render();
		$id=$this->getId();
		$script="$('#{$id}').tabs();";
		require_once 'Ea/Layout/Script.php';
		$script=new Ea_Layout_Script($script, true);
		$script->display();
	}
}
