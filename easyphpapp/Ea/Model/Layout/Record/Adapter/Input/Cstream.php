<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.2-20081201
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Model/Layout/Record/Adapter/Input/Text.php';

/**
 * Text input for cstream column.
 */
class Ea_Model_Layout_Record_Adapter_Input_Cstream extends Ea_Model_Layout_Record_Adapter_Input_Text
{
	/**
	 * This function must return field content from record.
	 * 
	 * @param array $record
	 * @return string
	 */
	public function getValue($record)
	{
		$value=parent::getValue($record);
		if($value) return stream_get_contents($value);
		return $value;
	}
}

?>