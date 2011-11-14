<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  recordapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id:$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

require_once 'Ea/Module/Abstract.php';
require_once 'Ea/Layout/Grid.php';
require_once 'Ea/Layout/Record/Adapter/Field.php';

/**
 * My basic module.
 *
 */
class Module_Index extends Ea_Module_Abstract
{
	public function init()
	{
		// set the page title
		$this->getPage()->setTitle('Table of records');
	}
	
	public function actionIndex()
	{

		$records=array(
			array('name'=>'David', 'age'=>31, 'gender'=>'M'),
			array('name'=>'Alice', 'age'=>25, 'gender'=>'F'),
			array('name'=>'John',  'age'=>45, 'gender'=>'M'),
			array('name'=>'Mia',   'age'=>21, 'gender'=>'F'),
		);
		
		// declare new records table
		$table1=new Ea_Layout_Grid;
		
		// declare the column using the basic record to field adapter.
		$table1->addColumn(new Ea_Layout_Record_Adapter_Field('name'),   'Name');
		$table1->addColumn(new Ea_Layout_Record_Adapter_Field('age'),    'Age');
		$table1->addColumn(new Ea_Layout_Record_Adapter_Field('gender'), 'Gender');
		
		// set the records...
		$table1->setRecords($records);

		// default for multiple records table is vertical
		// $table1->setSpread('vertical');

		// add the table to the page !
		$this->add($table1);

		// declare new records table
		$table2=new Ea_Layout_Grid;
		
		// declare the column using the basic record to field adapter.
		$table2->addColumn(new Ea_Layout_Record_Adapter_Field('name'),   'Name');
		$table2->addColumn(new Ea_Layout_Record_Adapter_Field('age'),    'Age');
		$table2->addColumn(new Ea_Layout_Record_Adapter_Field('gender'), 'Gender');
		
		// set one record...
		$table2->setRecord($records[0]);
		
		// default for 1 record table is horizontal
		// $table2->setSpread('horizontal');
		
		// add the table to the page !
		$this->add($table2);
		
		// application will call preRender() and render()
	}
}
?>