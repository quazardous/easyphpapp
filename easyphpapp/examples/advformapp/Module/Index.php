<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  formapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.2.5.20081020
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

require_once 'Ea/Module/Abstract.php';
require_once 'Ea/Layout/Form.php';
require_once 'Ea/Layout/Input/Text.php';
require_once 'Ea/Layout/Input/Textarea.php';
require_once 'Ea/Layout/Input/Select.php';
require_once 'Ea/Layout/Input/Submit.php';
require_once 'Ea/Layout/Input/Radio.php';
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
		$this->getPage()->setTitle('Advanced form');
	}
	
	public function actionIndex()
	{
		// declare new form
		$form=new Ea_Layout_Form('form2');
		
		// catch if some datas were send
		if($form->catchInput())
		{
			// do you form stuff
			$_SESSION['text1']=(string)$form['text1'];
			$_SESSION['select1']=(string)$form['select1'];
			$_SESSION['textarea1']=(string)$form['textarea1'];
			$_SESSION['radio1']=(string)$form['radio1'];
			
			// There is two ways to use forms.

			// The optimistic way :
			// You draw the form at display time and af submit time you don't and call catchInput().
			// In this case $form[] will just map $_POST[].
			// The "pessimistic" way :
			// If you draw the form (or store the structure with ->store()) at submit time, $form[] will access each input and get the correspondng value from $_POST.
			// $form[] will return the input layout, it's why we cast with string to get the value.

			// This example demonstrate the "pessimistic" way.
			// Using this way, you can use $iput->rememberValue() to automatically get the submitted data and display id (if no value set).
			
			// nothing else to do, router will redirect to current URL
			return;
		}

		// You can use rememberAllInputsValues() to remember all inputs values
		// automatically call $input->rememberValue() when adding input
		// $form->rememberAllInputsValues();
		
		$form->add($table=new Ea_Layout_Table);
		
		// draw the form
		$table->addRow();
		$table->addHeader('text');
		$table->addCell($input=new Ea_Layout_Input_Text('text1'));
		// if no value is set, try to get stored value
		$input->rememberValue();
		
		// if session, it means we hit send last time...
		if(isset($_SESSION['text1']))
		{
			$table->addCell($_SESSION['text1']);
			// clean up
			unset($_SESSION['text1']);
		}
		
		$table->addRow();
		$table->addHeader('select');
		$table->addCell($input=new Ea_Layout_Input_Select('select1', array(1=>'one', 2=>'two', 3=>'three')));
		$input->rememberValue();
		if(isset($_SESSION['select1']))
		{
			$table->addCell($_SESSION['select1']);
			unset($_SESSION['select1']);
		}

		$table->addRow();
		$table->addHeader('textarea');
		$table->addCell($input=new Ea_Layout_Input_Textarea('textarea1', 5, 20));
		$input->rememberValue();
		if(isset($_SESSION['textarea1']))
		{
			$table->addCell($_SESSION['textarea1']);
			unset($_SESSION['textarea1']);
		}
		
		$table->addRow();
		$table->addHeader('radio');
		$cell=$table->addCell($input=new Ea_Layout_Input_Radio('radio1', 'One'));
		$cell->add(new Ea_Layout_Input_Radio('radio1', 'Two')); // you can use $form['radio1']='Two';
		$cell->add(new Ea_Layout_Input_Radio('radio1', 'Three'));
		$input->rememberValue();
		if(isset($_SESSION['radio1']))
		{
			$table->addCell($_SESSION['radio1']);
			unset($_SESSION['radio1']);
		}

		$table->addRow();
		$table->addHeader('submit');
		$table->addCell(new Ea_Layout_Input_Submit('send', 'Send'));
		
		// add the form to the page
		$this->getPage()->add($form);
		
		// store the form structure
		// store() is mandatory if you want to use $input->rememberValue()
		$form->store();
		
		// router will call render
	}
}
?>