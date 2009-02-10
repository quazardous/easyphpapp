<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.3.6-20090209
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Record/Adapter/Field/Input/Abstract.php';
require_once 'Ea/Layout/Input/Checkbox.php';

/**
 * Field value text input from record (array or object).
 */
class Ea_Layout_Record_Adapter_Field_Input_Checkbox extends Ea_Layout_Record_Adapter_Field_Input_Abstract
{
	
	protected $_value=null;
	
	public function __construct($field, $value='X', $baseId=null, $indexColumn=null, $config=null)
	{
		parent::__construct($field, $baseId, $indexColumn, $config);
		$this->_value=$value;
	}
	
	
	/**
	 * This function must return content from record.
	 * 
	 * @param array $record
	 * @return string
	 */
	public function getContent($record, $i)
	{
		return new Ea_Layout_Input_Checkbox($this->getId($record, $i), $this->getValue($record)==$this->_value, $this->_value, $this->_config);
	}
}

?>