<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  formapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.2.4.20081020
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
		session_start();		
		
		// set the page title
		$this->getPage()->setTitle('Simple form');
	}
	
	public function actionIndex()
	{
		// declare new form
		$form=new Ea_Layout_Form('form1');
		
		// catch if some datas were send
		if($form->catchInput())
		{
			// do you form stuff
			$_SESSION['text1']=$form['text1'];
			$_SESSION['select1']=$form['select1'];
			$_SESSION['textarea1']=$form['textarea1'];
			$_SESSION['radio1']=$form['radio1'];
			
			// nothing else to do, router will redirect to current URL
			return;
		}
		
		$form->add($table=new Ea_Layout_Table);
		
		// draw the form
		$table->addRow();
		$table->addHeader('text');
		$table->addCell(new Ea_Layout_Input_Text('text1'));
		// if session, it means we hit send last time...
		if(isset($_SESSION['text1']))
		{
			$table->addCell($_SESSION['text1']);
			// clean up
			unset($_SESSION['text1']);
		}
		
		$table->addRow();
		$table->addHeader('select');
		$table->addCell(new Ea_Layout_Input_Select('select1', array(1=>'one', 2=>'two', 3=>'three'), 2));
		// if session, it means we hit send last time...
		if(isset($_SESSION['select1']))
		{
			$table->addCell($_SESSION['select1']);
			// clean up
			unset($_SESSION['select1']);
		}

		$table->addRow();
		$table->addHeader('textarea');
		$table->addCell(new Ea_Layout_Input_Textarea('textarea1'));
		// if session, it means we hit send last time...
		if(isset($_SESSION['textarea1']))
		{
			$table->addCell($_SESSION['textarea1']);
			// clean up
			unset($_SESSION['textarea1']);
		}
		
		$table->addRow();
		$table->addHeader('radio');
		$cell=$table->addCell($toto=new Ea_Layout_Input_Radio('radio1', 'One'));
		$cell->add(new Ea_Layout_Input_Radio('radio1', 'Two', true)); // you can use $form['radio1']='Two';
		$cell->add(new Ea_Layout_Input_Radio('radio1', 'Three'));
		// if session, it means we hit send last time...
		if(isset($_SESSION['radio1']))
		{
			$table->addCell($_SESSION['radio1']);
			// clean up
			unset($_SESSION['radio1']);
		}

		$table->addRow();
		$table->addHeader('submit');
		$table->addCell(new Ea_Layout_Input_Submit('send', 'Send'));
		
		// add the form to the page
		$this->getPage()->add($form);
		
		
		// router will call render
	}
}
?>