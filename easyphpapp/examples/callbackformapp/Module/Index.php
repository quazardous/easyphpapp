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
		session_start();
		// set the page title
		$this->getPage()->setTitle('Callback form');
	}
	
	public function actionIndex()
	{
		// declare new form
		$form=new Ea_Layout_Form('form2');
				
		// add a submit callback for the send button
		// by default the callback is a method of the module
		$form->addSubmitCallback('onSubmitIndexSend', 'send2');

		// add a submit callback for any submit
		// by default the callback is a method of the module
		$form->addSubmitCallback('onSubmitIndex');
		
		// catch if some datas were send
		if($form->triggerSubmitCallbacks())
		{
			return;
		}
		
		$form->add($table=new Ea_Layout_Table);
		
		// draw the form
		$table->addRow();
		$table->addHeader('text');
		$table->addCell($input=new Ea_Layout_Input_Text('text1'));
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
		if(isset($_SESSION['select1']))
		{
			$table->addCell($_SESSION['select1']);
			unset($_SESSION['select1']);
		}

		$table->addRow();
		$table->addHeader('textarea');
		$table->addCell($input=new Ea_Layout_Input_Textarea('textarea1', 5, 20));
		if(isset($_SESSION['textarea1']))
		{
			$table->addCell($_SESSION['textarea1']);
			unset($_SESSION['textarea1']);
		}
		
		$table->addRow();
		$table->addHeader('radio');
		$cell=$table->addCell($input=new Ea_Layout_Input_Radio('radio1', 'One', 'One'));
		$cell->add(new Ea_Layout_Input_Radio('radio1', 'Two', 'Two'));
		$cell->add(new Ea_Layout_Input_Radio('radio1', 'Three', 'Three'));
		if(isset($_SESSION['radio1']))
		{
			$table->addCell($_SESSION['radio1']);
			unset($_SESSION['radio1']);
		}

		$table->addRow();
		$table->addHeader('submit');
		$table->addCell(new Ea_Layout_Input_Submit('send', 'Send'));
		
		$table->addRow();
		$table->addHeader('submit 2');
		$table->addCell(new Ea_Layout_Input_Submit('send2', 'Send 2'));
		if(isset($_SESSION['submit']))
		{
			$table->addCell($_SESSION['submit']);
			unset($_SESSION['submit']);
		}
		
		// add the form to the page
		$this->getPage()->add($form);

		// application will call render
	}
	
	public function onSubmitIndexSend(Ea_Layout_Form $form, $id)
	{
		$_SESSION['submit']=$id;
	}

	public function onSubmitIndex(Ea_Layout_Form $form, $id)
	{
		// do you form stuff
		$_SESSION['text1']=(string)$form['text1'];
		$_SESSION['select1']=(string)$form['select1'];
		$_SESSION['textarea1']=(string)$form['textarea1'];
		$_SESSION['radio1']=(string)$form['radio1'];
	}
	
}