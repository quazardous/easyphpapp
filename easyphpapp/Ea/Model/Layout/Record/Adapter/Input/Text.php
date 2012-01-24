<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Record/Adapter/Field/Input/Textarea.php';
require_once 'Ea/Model/Layout/Record/Adapter/Interface.php';

/**
 * Text input for column.
 */
class Ea_Model_Layout_Record_Adapter_Input_Text extends Ea_Layout_Record_Adapter_Field_Input_Textarea implements Ea_Model_Layout_Record_Adapter_Interface
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
			require_once 'Ea/Model/Layout/Exception.php';
			throw new Ea_Model_Layout_Exception('Must be an Ea_Model_Layout_Form');
		}
		$config=$model->getColumnAdapterConfig($column);
		$rows=null;
		if((!isset($config['rows'])&&(!isset($config['attributes']['rows']))))
		{
			if($model->getColumnStringLength($column))
			{
				$rows=round(log($model->getColumnStringLength($column))/2);
			}
		}
		$cols=null;
		if((!isset($config['cols'])&&(!isset($config['attributes']['cols']))))
		{
			if($model->getColumnStringLength($column))
			{
				$cols=40;
			}
		}
		parent::__construct($column, $rows, $cols, $model->getBaseId(), $model->getRecordIndexColumn(), $config);
		$this->_filter=array($model, 'filterRecordValue');
	}	
}
