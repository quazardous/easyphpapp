<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.3.5-20090209
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
	
	public function __construct($field, array $options, $baseId=null, $indexColumn=null, $config=null)
	{
		parent::__construct($field, $baseId, $indexColumn, $config);
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
		return new Ea_Layout_Input_Select($this->getId($record, $i), $this->_options, $this->getValue($record), $this->_config);
	}
}

?>