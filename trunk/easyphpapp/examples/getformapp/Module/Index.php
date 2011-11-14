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
		// set the page title
		$this->getPage()->setTitle('GET form');
	}
	
	public function actionIndex()
	{
		// declare new form
		$form=new Ea_Layout_Form('form1', 'get');
		// the goal of GET form is to produce an url from a form.
		
		$form->add($table=new Ea_Layout_Table);
		
		// draw the form
		$table->addRow();
		$table->addHeader('text1');
		$table->addCell(new Ea_Layout_Input_Text('text1'));
		
		$table->addRow();
		$table->addHeader('select1[titi]');
		// ids can be string, array or "array as string"
		// ie 'select1[titi]' is equivalent of array('select1', 'titi')
		$table->addCell(new Ea_Layout_Input_Select('select1[titi]', array(1=>'one', 2=>'two', 3=>'three'), 2));

		$form['select1[titi]']=3;
		// or $form[array('select1', 'titi')]=3;
		
		// now to create a EasyPhpRoute with EasyPhpParams...
		$table->addRow();
		
		$idText2=$this->getInputId('text2');
		$table->addHeader('text2');
		$table->addCell(new Ea_Layout_Input_Text($idText2));
		
		$form[$idText2]='foo';
		// now you can use Ea_App::getParam('text2') and get 'foo'...

		$table->addRow();
		
		$idText3=$this->getInputId('text3');
		$table->addHeader('text3');
		$table->addCell(new Ea_Layout_Input_Text($idText3));
		
		$form[$idText3]='bar';
		// now you can use Ea_App::getParam('text2') and get 'foo'...
		
		$table->addRow();
		$table->addHeader('submit');
		$table->addCell(new Ea_Layout_Input_Submit('send', 'Send'));
		
		// add the form to the page
		$this->getPage()->add($form);
		
		// NB : inside EasyPhpApp application engine, the form will try to complete the form with all the get params as hidden inputs
		// see $form->setAction()	
		// application will call render
	}
}
?>