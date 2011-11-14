<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Base
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.5.3-20111114
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
	
	/**
	 * The id of the input or the input itself.
	 * @var Ea_Layout_Input_Abstract|string
	 */
	protected $_for=null;

	/**
	 * Set the id of the input or the input itself.
	 * @param Ea_Layout_Input_Abstract|string $for
	 */
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
		if ($this->_for instanceof Ea_Layout_Input_Abstract) {
		  $id = $this->_for->getAttributeId();
		}
		else {
		  $id = $this->_for;
		}
		$this->_setAttribute('for', $id);
		return $render;
	}
	
}