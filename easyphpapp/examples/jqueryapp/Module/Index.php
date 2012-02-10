<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  basicapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

require_once 'Ea/Module/Abstract.php';
require_once 'Ea/Layout/Script.php';
require_once 'Ea/Layout/Tabs.php';
require_once 'Ea/Layout/Table.php';
require_once 'Ea/Layout/Form.php';
require_once 'Ea/Layout/Input/DatePicker.php';

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
		$this->getPage()->setTitle('jQuery');
	}
	
	public function actionIndex()
	{
		
		$this->add($tabs=new Ea_Layout_Tabs('tabs'));
		
		$texts=array(
			'one'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
			'two'=>"Phasellus mattis tincidunt nibh. Cras orci urna, blandit id, pretium vel, aliquet ornare, felis. Maecenas scelerisque sem non nisl. Fusce sed lorem in enim dictum bibendum.",
			'three'=>"Nam dui erat, auctor a, dignissim quis, sollicitudin eu, felis. Pellentesque nisi urna, interdum eget, sagittis et, consequat vestibulum, lacus. Mauris porttitor ullamcorper augue.",
		);
		
		foreach($texts as $title=>$text)
		{
			$tabs->addTab($title, $text);
		}
		
		$this->add($form=new Ea_Layout_Form('form'));
		
		$form->add($table=new Ea_Layout_Table);
		
		// draw the form
		$table->addRow();
		$table->addHeader('date1');
		$table->addCell($input=new Ea_Layout_Input_DatePicker('date1'));
		
	}
}
