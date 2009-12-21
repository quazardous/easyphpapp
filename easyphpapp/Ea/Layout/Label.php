<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Base
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.2-20091218
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Container.php';

/**
 * Label layout class.
 * 
 */
class Ea_Layout_Label extends Ea_Layout_Container
{
	protected $_tag='label';
	
	protected $_for=null;
	public function setFor($for)
	{
		$this->_for=$for;
	}
	
	public function getFor()
	{
		return $this->_for;
	}
	
	/**
	 * Label layout constructor.
	 * 
	 * @param array $config associative array of parameters
	 * 
	 */
	public function __construct($content=null, $for=null, $config=null)
	{
		parent::__construct(null, $config);
		if($for!==null) $this->setFor($for);
		if($content!==null) $this->add($content);
		$this->protectAttribute('for');
	}
	
	protected function preRender()
	{
		$render=parent::preRender();
		$this->_setAttribute('for', $this->_for);
		return $render;
	}
	
}