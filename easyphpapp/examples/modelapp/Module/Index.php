<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  modelapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

require_once 'Table/EaTestTable1.php';
require_once 'Ea/Model/Data.php';
require_once 'Ea/Model/Layout.php';
require_once 'Ea/Model/Layout/Form.php';
require_once 'Ea/Layout/Grid.php';
require_once 'Zend/Session.php';
require_once 'Ea/Layout/Form.php';
require_once 'Ea/Layout/Input/Submit.php';
require_once 'Ea/Module/Abstract.php';

/**
 * My basic module.
 *
 */
class Module_Index extends Ea_Module_Abstract
{
	public function init()
	{
	    parent::init();
		// set the page title
		$this->getPage()->setTitle('Basic data model');
	}
	
	public function actionIndex()
	{
		// get some test table
		$ea_test_table1=new Table_EaTestTable1;
		
		// build the data model
		$dataModel1=Ea_Model_Data::factory($ea_test_table1);
		
		// build the layout model
		$model1=new Ea_Model_Layout($dataModel1);
		
		// hide all columns
		$model1->setColumnDisplay('*', false);
		
		// display some columns
		$model1->setColumnDisplay(array('id', 'valdatetime', 'valenum'), true);
		
		// change output format for datetime cols
		$model1->setColumnDateFormat($model1->getColumnsOfType('datetime'), '%d/%m/%Y');
		
		// define a table layout
		$table1=new Ea_Layout_Grid;
		
		// construct columns from model
		$table1->applyModel($model1);
		
		// add some record to display
		$table1->setRecords($ea_test_table1->fetchAll());
		
		$this->add($table1);

		// same with a form
		$form=new Ea_Layout_Form('form4');
		
		$this->add($form);
		
		// now we'll do the same for a form...
		$table2=new Ea_Layout_Grid;
		$form->add($table2);
		
		// build the form model
		// Ea_Model_Layout::factory() can handle db table => it calls Ea_Model_Data_Abstract::factory()
		$model2=new Ea_Model_Layout_Form($ea_test_table1);

		// change output format for datetime cols
		$model2->setColumnDateFormat($model1->getColumnsOfType(array('datetime', 'date')), '%d/%m/%Y');
		
		// you can set the base id for the inputs
		//$model2->setBaseId($id);
		
		// construct columns from model
		$table2->applyModel($model2);
		
		// add one row
		$table2->setRecord($ea_test_table1->fetchRow());
		$table2->populate(); // fill table with records
		
		$table2->addRow();
		$table2->addCell(new Ea_Layout_Input_Submit('send', 'Send', 'onSubmit'));

		global $db;
		
		// build the data model
		$dataModel3=Ea_Model_Data::factory($stmt=$db->query('select * from ea_test_table1'));
		
		// build the layout model
		$model3=new Ea_Model_Layout($dataModel3);
		
		// change output format for datetime cols
		$model3->setColumnDateFormat($model3->getColumnsOfType('datetime'), '%d/%m/%Y');
		
		// define a table layout
		$table3=new Ea_Layout_Grid;
		
		// construct columns from model
		$table3->applyModel($model3);
		
		// add some record to display
		$table3->setRecords($stmt->fetchAll());
		
		$this->add($table3);
		
		
		// application will call render
	}
	
	public function onSubmit(Ea_Layout_Form $form, $id)
	{
		$form->dump();
		die();
		
	}
}