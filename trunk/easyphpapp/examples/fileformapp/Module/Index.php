<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  formapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.2.6.20081022
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

require_once 'Ea/Module/Abstract.php';
require_once 'Ea/Layout/Form.php';
require_once 'Ea/Layout/Input/File.php';
require_once 'Ea/Layout/Input/Submit.php';
require_once 'Ea/Layout/Table.php';

/**
 * My basic module.
 *
 */
class Module_Index extends Ea_Module_Abstract
{
	public function init()
	{
		// we will use session to display our inputs
		// do not use session_start()
		Zend_Session::start();
		
		// set the page title
		$this->getPage()->setTitle('File form');
	}
	
	public function actionIndex()
	{
		// declare new form
		$form=new Ea_Layout_Form('form3');
		
		// catch if some datas were send
		if($form->catchInput())
		{
			// do you form stuff
			//echo((string)$form['file1']);
			
			echo $form['file1']->getName()."=".$form['file1']->getFileName()."\n------------------\n";
			echo $form['file1']->getFileContents(256);
			echo "\n------------------\n";
			
			echo $form['record']['file2']->getName()."=".$form['record']['file2']->getFileName()."\n------------------\n";
			echo $form['record']['file2']->getFileContents(256);
			echo "\n------------------\n";
			
			echo $form['records'][0]['file3']->getName()."=".$form['records'][0]['file3']->getFileName()."\n------------------\n";
			echo $form['records'][0]['file3']->getFileContents(256);
			echo "\n------------------\n";
			
			// ok it's testing ;p
			die;
			
			return;
		}
		
		$form->add($table=new Ea_Layout_Table);
		
		// draw the form
		$table->addRow();
		$table->addHeader('file1');
		$table->addCell(new Ea_Layout_Input_File('file1'));

		// draw the form
		$table->addRow();
		$table->addHeader('file2');
		$table->addCell(new Ea_Layout_Input_File(array('record', 'file2')));
		
		// draw the form
		$table->addRow();
		$table->addHeader('file3');
		$table->addCell(new Ea_Layout_Input_File(array('records', 0,'file3')));
		
		$table->addRow();
		$table->addHeader('submit');
		$table->addCell(new Ea_Layout_Input_Submit('send', 'Send'));
		
		// add the form to the page
		$this->getPage()->add($form);
		
		// store the form structure
		// store() is mandatory if you want to use file upload support
		$form->store();
		
		// router will call render
	}
}