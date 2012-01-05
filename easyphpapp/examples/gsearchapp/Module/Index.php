<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     examples
 * @subpackage  gsearchapp
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 * @filesource
 */

require_once 'Ea/Module/Abstract.php';

/**
 * My basic Google Map module.
 *
 */
class Module_Index extends Ea_Module_Abstract
{
	public function init()
	{
    parent::init();
		// set the page title
		$this->getPage()->setTitle('Google Search Parser');
	}
	
	public function actionIndex()
	{

		require_once 'Ea/Service/GSearch/Parser.php';
		$gsp=new Ea_Service_GSearch_Parser;
		
		$results = $gsp->search('foo');
		
		require_once 'Ea/Layout/Grid.php';
		// declare new records table
		$this->add($table=new Ea_Layout_Grid);
		
		require_once 'Ea/Layout/Record/Adapter/Field.php';
		// declare the column using the basic record to field adapter.
		$table->addColumn(new Ea_Layout_Record_Adapter_Field('pos'), 'Position');
		$table->addColumn(new Ea_Layout_Record_Adapter_Field('title'), 'Title');
		$table->addColumn(new Ea_Layout_Record_Adapter_Field('link'), 'Link');
		
		// set one record...
		$table->setRecords($results);

	}
}