<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  formapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id:$
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
		// set the page title
		$this->getPage()->setTitle('File form');
	}
	
	public function actionIndex()
	{
		// declare new form
		$form=new Ea_Layout_Form('form4');
		
		// catch if some datas were send
		if($form->catchInput())
		{
			// do you form stuff
			
			foreach(array('file1', 'record[file2]', 'records[0][file3]') as $input)
			{
				echo "$input=".$form->getUploadedFileName($input)."\n------------------\n";
				echo $form->getUploadedFileContents($input, 256);
				echo "\n------------------\n";
			}
			
			// ok it's testing ;p
			// you can use $form->moveUploadedFile($input, $dst) to put your file where you want.
			die;
			
			return;
		}
		
		$table=new Ea_Layout_Table;
		$form->add($table);		
		
		// draw the form
		$table->addRow();
		$table->addHeader('file1');
		$table->addCell(new Ea_Layout_Input_File('file1'));

		$table->addRow();
		$table->addHeader('file2');
		$table->addCell(new Ea_Layout_Input_File('record[file2]'));
		
		$table->addRow();
		$table->addHeader('file3');
		$table->addCell(new Ea_Layout_Input_File('records[0][file3]'));
		
		$table->addRow();
		$table->addHeader('submit');
		$table->addCell(new Ea_Layout_Input_Submit('send', 'Send'));
		
		// add the form to the page
		$this->add($form);
				
		// application will call render
	}
}