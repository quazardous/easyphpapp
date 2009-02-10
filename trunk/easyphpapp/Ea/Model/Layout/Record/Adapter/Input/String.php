<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.3.6-20090210
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Record/Adapter/Field/Input/Text.php';
require_once 'Ea/Model/Layout/Record/Adapter/Interface.php';
require_once 'Ea/Model/Layout/Form.php';

/**
 * Text input for column.
 */
class Ea_Model_Layout_Record_Adapter_Input_String extends Ea_Layout_Record_Adapter_Field_Input_Text implements Ea_Model_Layout_Record_Adapter_Interface
{
	/**
	 * Constructor.
	 * 
	 * @param $column
	 * @param Ea_Model_Layout_Form $model
	 */
	public function __construct($column, Ea_Model_Layout $model)
	{
		if(!$model instanceof Ea_Model_Layout_Form)
		{
			throw new Ea_Model_Layout_Exception('Must be an Ea_Model_Layout_Form');
		}
		$config=$model->getColumnAdapterConfig($column);
		if((!isset($config['size'])&&(!isset($config['attributes']['size']))))
		{
			$config['attributes']['size']=$model->getColumnStringLength($column);
		}
		if((!isset($config['maxlength'])&&(!isset($config['attributes']['maxlength']))))
		{
			$config['attributes']['maxlength']=$model->getColumnStringLength($column);
		}
		parent::__construct($column, $model->getBaseId(), $model->getRecordIndexColumn(), $config);
		$this->_filter=array($model, 'filterRecordValue');
	}	
}

?>