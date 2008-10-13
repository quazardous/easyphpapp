<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.1
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Input/Abstract.php';

/**
 * Checkbox input layout class.
 */
class Ea_Layout_Input_Checkbox extends Ea_Layout_Input_Abstract
{
	protected $_type='checkbox';
	
	/**
	 * Ea_Layout_Input_Abstract constructor.
	 *
	 * @param string|array(string) $id the id of the input
	 * @param boolean $checked
	 * @param string|numeric $value
	 * @param array $config
	 * 
	 */
	public function __construct($id, $checked=null, $value='X', $config=null)
	{
		parent::__construct($id, $value, $config);
		if($checked!==null)$this->setChecked($checked);
	}
	
	/**
	 * Checkbox is checked or not.
	 * 
	 * @var boolean
	 */
	protected $_checked=null;
	
	/**
	 * Set checked.
	 * 
	 * @param boolean $checked
	 */
	public function setChecked($checked)
	{
		$this->_checked=$checked;
	}
	
	/**
	 * Get checked.
	 * 
	 * @return boolean
	 */
	public function isChecked()
	{
		return $this->_checked;
	}

	public function setAttribute($name, $value)
	{
		$name=strtolower($name);
		switch($name)
		{
			/*
			 * Some reserved attributes...
			 */
			case 'checked': $this->setChecked(strtolower($value)=='checked'); break;
			default:
				parent::setAttribute($name, $value);
		}
	} 
	
	public function preRender()
	{
		parent::preRender();
		/*
		 * Display the reserved attributes...
		 */
		if($this->isChecked()) parent::setAttribute('checked', 'checked');
	}
	
	/**
	 * Set the value from $_POST.
	 * 
	 */
	public function setFromPost()
	{
		parent::setFromPost();
		$this->setChecked($this->getValue()!=null);
	}
	
}
?>