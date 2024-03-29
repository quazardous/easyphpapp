<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Input/Abstract.php';


/**
 * Textarea input layout class.
 */
class Ea_Layout_Input_Textarea extends Ea_Layout_Input_Abstract
{
	protected $_tag='textarea';

	/**
	 * Ea_Layout_Input_Abstract constructor.
	 *
	 * @param string|array(string) $id the id of the input
	 * @param string|numeric $value
	 * @param array $config
	 * 
	 * @see $_id
	 */
	public function __construct($id=null, $rows=null, $cols=null, $value=null, $config=null)
	{
		parent::__construct($id, $value, $config);
		$this->setId($id);
		$this->setValue($value);
		$this->noRenderAttribute('value', true);
		if ($cols==null) {
		  if (isset($config['cols'])) $cols = $config['cols'];
		  elseif (isset($config['attributes']['cols'])) $cols = $config['attributes']['cols'];
		}
		if ($rows==null) {
		  if (isset($config['rows'])) $rows = $config['rows'];
		  elseif (isset($config['attributes']['rows'])) $rows = $config['attributes']['rows'];
		}
		if($rows) $this->setAttribute('rows', $rows);
		if($cols) $this->setAttribute('cols', $cols);
	}
	
	protected function render()
	{
	  $this->displayLabel($this->_labelPosition == 'before');
		echo '<';
		echo $this->_tag;
		$this->renderAttributes();
		echo '>';

		echo $this->escape($this->getValue());

		echo '</';
		echo $this->_tag;
		echo '>';
		$this->displayLabel($this->_labelPosition == 'after');
	}
}
