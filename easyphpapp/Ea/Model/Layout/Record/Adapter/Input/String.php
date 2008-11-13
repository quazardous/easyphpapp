<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.0.20081106
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Record/Adapter/Field/Input/Select.php';
require_once 'Ea/Model/Layout/Record/Adapter/Interface.php';

/**
 * Text input for column.
 */
class Ea_Model_Layout_Record_Adapter_Input_String extends Ea_Layout_Record_Adapter_Field_Input_Text implements Ea_Model_Layout_Record_Adapter_Interface
{
	public function __construct($column, Ea_Model_Layout $model)
	{
		parent::__construct($column, $model->getBaseId(), $model->getMetaData($column, 'adapter', 'config'));
		$this->_filter=array($model, 'filterRecordValue');
	}	
}

?>