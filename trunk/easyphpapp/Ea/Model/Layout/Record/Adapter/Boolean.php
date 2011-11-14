<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id:$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Model/Layout/Record/Adapter/Interface.php';
require_once 'Ea/Layout/Record/Adapter/Field/Boolean.php';

/**
 * Boolean layout for column.
 */
class Ea_Model_Layout_Record_Adapter_Boolean extends Ea_Layout_Record_Adapter_Field_Boolean implements Ea_Model_Layout_Record_Adapter_Interface
{
	public function __construct($column, Ea_Model_Layout $model)
	{
		parent::__construct($column, $model->getColumnBooleanTrue($column), $model->getColumnBooleanFalse($column));
		$this->_filter=array($model, 'filterRecordValue');
	}
}

