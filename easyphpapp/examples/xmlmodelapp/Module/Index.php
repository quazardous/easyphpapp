<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  modelapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.2-20081209
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

require_once 'Ea/Model/Data/Xml.php';
require_once 'Ea/Model/Layout.php';
require_once 'Ea/Layout/Record/Table.php';

/**
 * My basic module.
 *
 */
class Module_Index extends Ea_Module_Abstract
{
	public function init()
	{
		// set the page title
		$this->getPage()->setTitle('Data model from XML');
	}
		
	public function actionIndex()
	{
		$records=array(
			array('name'=>'David', 'age'=>31, 'gender'=>'M'),
			array('name'=>'Alice', 'age'=>25, 'gender'=>'F'),
			array('name'=>'John',  'age'=>45, 'gender'=>'M'),
			array('name'=>'Mia',   'age'=>21, 'gender'=>'F'),
		);
		
		// build the layout model
		$model1=new Ea_Model_Layout(new Ea_Model_Data_Xml('model/myrecord.xml'));
		
		// define a table layout
		$table1=new Ea_Layout_Record_Table;

		// construct columns from model
		$table1->applyModel($model1);
				
		// add some record to display
		$table1->setRecords($records);
		
		$this->add($table1);
		
		// application will call render
	}
}
?>