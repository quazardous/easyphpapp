<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.2.4.20081017
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Input/Abstract.php';

/**
 * Select input layout class.
 */
class Ea_Layout_Input_Select extends Ea_Layout_Input_Abstract
{
	protected $_tag='select';

	/**
	 * Ea_Layout_Input_Abstract constructor.
	 *
	 * @param string|array(string) $id the id of the input
	 * @param array(string=>string) options (value=>label)
	 * @param string|numeric $value
	 * @param array $config
	 * 
	 * @see $_id
	 */
	public function __construct($id, array $options=array(), $value=null, $config=null)
	{
		parent::__construct($id, $value, $config);
		$this->_options=$options;
	}
	

	public function render()
	{
		echo '<';
		echo $this->_tag;
		$this->renderAttributes();
		echo '>';

		foreach($this->_options as $value=>$label)
		{
			echo '<option value="'.$this->escape($value).'"';
			if($value==$this->_value) echo ' selected="selected"';
			echo '>';
			echo $this->escape($label);
			echo "</option>\n";
		}

		echo '</';
		echo $this->_tag;
		echo '>';
	}
	
	/**
	 * Select options.
	 * 
	 * @var array
	 */
	protected $_options=array();
	
	/**
	 * Add an option.
	 * 
	 * @param string $value
	 * @param string $label
	 * @param boolean $selected
	 */
	public function addOption($value, $label)
	{
		$this->_options[$value]=$label;
	}
}

?>