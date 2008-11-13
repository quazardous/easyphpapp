<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  formapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.1-20081113
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
		$this->getPage()->setTitle('GET form');
	}
	
	public function actionIndex()
	{
		// declare new form
		$form=new Ea_Layout_Form('form1');
		
		$form->setMethod('get');
		
		$form->add($table=new Ea_Layout_Table);
		
		// draw the form
		$table->addRow();
		$table->addHeader('text');
		$table->addCell(new Ea_Layout_Input_Text('text1'));
		
		$table->addRow();
		$table->addHeader('select');
		$table->addCell(new Ea_Layout_Input_Select('select1', array(1=>'one', 2=>'two', 3=>'three'), 2));

		$table->addRow();
		$table->addHeader('textarea');
		$table->addCell(new Ea_Layout_Input_Textarea('textarea1', 5, 20));
		
		$table->addRow();
		$table->addHeader('radio');
		$cell=$table->addCell(new Ea_Layout_Input_Radio('radio1', 'One'));
		$cell->add(new Ea_Layout_Input_Radio('radio1', 'Two', true)); // you can use $form['radio1']='Two';
		$cell->add(new Ea_Layout_Input_Radio('radio1', 'Three'));

		$table->addRow();
		$table->addHeader('submit');
		$table->addCell(new Ea_Layout_Input_Submit('send', 'Send'));
		
		// add the form to the page
		$this->getPage()->add($form);
		
		
		// router will call render
	}
}
?>