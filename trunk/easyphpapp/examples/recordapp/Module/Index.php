<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  basicapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.1
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Module/Abstract.php';
require_once 'Ea/Layout/Record/Table.php';
require_once 'Ea/Layout/Record/Column/Field.php';

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
		// declare new form
		$table=new Ea_Layout_Record_Table;
		
		// declare the column using the basic record to field adapter.
		$table->addColumn(new Ea_Layout_Record_Column_Field('name'),   'Name');
		$table->addColumn(new Ea_Layout_Record_Column_Field('age'),    'Age');
		$table->addColumn(new Ea_Layout_Record_Column_Field('gender'), 'Gender');
		
		// set the records...
		$table->setRecords(
			array(
				array('name'=>'David', 'age'=>31, 'gender'=>'M'),
				array('name'=>'Alice', 'age'=>25, 'gender'=>'F'),
				array('name'=>'John',  'age'=>45, 'gender'=>'M'),
				array('name'=>'Mia',   'age'=>21, 'gender'=>'F'),
			)
		);

		// add the table to the page !
		$this->add($table);
		
		// router will call preRender() and render()
	}
}
?>