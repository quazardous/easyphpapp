<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  autocompleteapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

require_once 'Ea/Module/Abstract.php';
require_once 'Ea/Layout/Script.php';
require_once 'Ea/Layout/Table.php';
require_once 'Ea/Layout/Form.php';
require_once 'Ea/Layout/Input/Autocomplete.php';

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
		$this->getPage()->setTitle('Autocomplete');
	}
	
	public function actionIndex()
	{
		
		$this->add($form=new Ea_Layout_Form('form'));
		
		$form->add($table=new Ea_Layout_Table);
		
		// draw the form
		$table->addRow();
		$table->addHeader('Basic autocomplete');
		$table->addCell($input=new Ea_Layout_Input_Autocomplete('auto1'));
		$table->addCell("Try a 'a'...");
		
		$input->autocomplete(array(
			"ActionScript",
			"AppleScript",
			"Asp",
			"BASIC",
			"C",
			"C++",
			"Clojure",
			"COBOL",
			"ColdFusion",
			"Erlang",
			"Fortran",
			"Groovy",
			"Haskell",
			"Java",
			"JavaScript",
			"Lisp",
			"Perl",
			"PHP",
			"Python",
			"Ruby",
			"Scala",
			"Scheme"));
		
		
		$table->addRow();
		$table->addHeader('AJAX autocomplete');
		$table->addCell($input=new Ea_Layout_Input_Autocomplete('auto2'));
		$table->addCell("Try a 'a'...");
		
		$input->autocomplete($this->getApp()->getRoute(null, 'json'));
	}
	
	public function actionJson()
	{
	  $term = $this->getRawParam('term');
	  $ret = array();
	  foreach (preg_grep("/$term/i", array(
			"ActionScript",
			"AppleScript",
			"Asp",
			"BASIC",
			"C",
			"C++",
			"Clojure",
			"COBOL",
			"ColdFusion",
			"Erlang",
			"Fortran",
			"Groovy",
			"Haskell",
			"Java",
			"JavaScript",
			"Lisp",
			"Perl",
			"PHP",
			"Python",
			"Ruby",
			"Scala",
			"Scheme")) as $item) {
			// jQeury autocomplete needs an array of objects with 'value' and 'label' keys.
	    $ret[] = (object) array('value' => $item, 'label' => $item);
	  }
	  
	  return $ret;
	  // by default action methode does not return data.
	  // if it does standard rendering is disable and data will be sent to navigator
	  //  - Ea_Xml_Element and DOMDocument will generate XML output with correct mime type
	  //  - array() will generate JSON output with correct mime type
	  //  - other will be send as it
	  //
	  // you can specify mime type returning a 2 keys array :
	  // return array(
	  //   'content' => 'raw content',
	  //   'content-type' => 'mime string',
	  // )
    
	}

}
