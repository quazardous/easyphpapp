<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      berlioz [$Author$]
 * @version     0.4.6-20101007 [$Id$]
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Record/Adapter/Field/Input/Abstract.php';

/**
 * Field value text input from record (array or object).
 */
class Ea_Layout_Record_Adapter_Field_Input_Checkbox extends Ea_Layout_Record_Adapter_Field_Input_Abstract
{
	
	protected $_value=null;
	protected $_label=null;
	
	public function __construct($field, $label=null, $value='X', $baseId=null, $indexColumn=null, $config=null)
	{
		parent::__construct($field, $baseId, $indexColumn, $config);
		$this->_value=$value?$value:'X';
		$this->_label=$label;
	}
	
	/**
	 * This function must return content from record.
	 * 
	 * @param array $record
	 * @return string
	 */
	public function getContent($record, $i)
	{
		require_once 'Ea/Layout/Input/Checkbox.php';
		return new Ea_Layout_Input_Checkbox($this->getId($record, $i), $this->_label, $this->getRawValue($record)!=null, $this->_value, $this->_config);
	}
}
