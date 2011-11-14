<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      berlioz [$Author$]
 * @version     $Id:$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Record/Adapter/Field/Input/Abstract.php';

/**
 * Field value text input from record (array or object).
 */
class Ea_Layout_Record_Adapter_Field_Input_Text extends Ea_Layout_Record_Adapter_Field_Input_Abstract
{	
	/**
	 * This function must return content from record.
	 * 
	 * @param array $record
	 * @return string
	 */
	public function getContent($record, $i)
	{
		require_once 'Ea/Layout/Input/Text.php';
		return new Ea_Layout_Input_Text($this->getId($record, $i), $this->getRawValue($record), null, null, $this->_config);
	}
}
