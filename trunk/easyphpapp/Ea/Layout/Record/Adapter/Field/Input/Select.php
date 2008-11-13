<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.0.20081112
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Record/Adapter/Field/Input/Abstract.php';
require_once 'Ea/Layout/Input/Select.php';

/**
 * Field value select input from record.
 */
class Ea_Layout_Record_Adapter_Field_Input_Select extends Ea_Layout_Record_Adapter_Field_Input_Abstract
{
	protected $_options=array();
	
	public function __construct($field, array $options, $baseId=null, $config=null)
	{
		parent::__construct($field, $baseId, $config);
		$this->_options=$options;
	}
	
	/**
	 * This function must return content from record.
	 * 
	 * @param array $record
	 * @return string
	 */
	public function getContent($record, $i)
	{
		return new Ea_Layout_Input_Select($this->getId($i), $this->_options, $this->getValue($record), $this->_config);
	}
}

?>