<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.1
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Table.php';
require_once 'Ea/Layout/Record/Adapter/Interface.php';

/**
 * Table of records layout class.
 * This class let you create a table of records.
 * 
 */
class Ea_Layout_Record_Table extends Ea_Layout_Table
{
	/**
	 * The array of records.
	 * 
	 * @var array(record)
	 */
	protected $_records=array();

	/**
	 * You can set the array of records.
	 * The array you pass must implement Iterator inteface.
	 * 
	 * @param Iterator $records
	 */
	public function setRecords($records)
	{
		if($records===null)$records=array();
		if(!(is_array($records)||($records instanceof Iterator)))
		{
			throw new Ea_Layout_Record_Table_Exception("Array of records must be an array or an Iterator");
		}
		$this->_records=$records;
	}
	
	/**
	 * Add a record.
	 * 
	 * @param record $record
	 */
	public function addRecord($record)
	{
		array_push($this->_records, $record);
	}

	/**
	 * Columns to display
	 * @var array(Ea_Layout_Record_Adapter_Interface)
	 */
	protected $_columns=array();
	
	/**
	 * Add a column to the table.
	 * 
	 * @param Ea_Layout_Record_Adapter_Interface $column an object that take record and return Ea_Layout_Abstract
	 * @param mixed $content a header content if given
	 * @param array $config the header class constructor config array
	 * @param string $class the header class
	 * @return Ea_Layout_Table_Header
	 * @see Ea_Layout_Record_Adapter_Interface
	 */
	public function addColumn(Ea_Layout_Record_Adapter_Interface $column, $content=null, $config=null, $class='Ea_Layout_Table_Header')
	{
		array_push($this->_columns, $column);
		if($content!==null) $this->addColumnHeader($content, $config, $class);
	}

	/**
	 * Add a column header.
	 * 
	 * @param mixed $content
	 * @param array $config config array for constructor
	 * @param string $class class of the header
	 * @return Ea_Layout_Table_Header
	 */
	protected function addColumnHeader($content, $config, $class)
	{
		return $this->getHeadersRow()->addHeader($content, $config, true, $class);
	}
	
	/**
	 * Add a headers row.
	 * 
	 * @param $config the constructor config array of the headers row
	 * @param string $class the class of the header row
	 * @return Ea_Layout_Table_Row
	 */
	public function addHeadersRow($config=null, $class='Ea_Layout_Table_Row')
	{
		Zend_Loader::loadClass($class);
		$this->_headersRow=new $class($config);
		if(!$this->_headersRow instanceof Ea_Layout_Table_Row)
		{
			throw new Ea_Layout_Record_Table_Exception("$class not an instance of Ea_Layout_Table_Row");
		}
		return $this->_headersRow;
	}
	
	/**
	 * Get the headers row.
	 * May instanciate a default one.
	 * 
	 * @return Ea_Layout_Table_Row
	 */
	protected function getHeadersRow()
	{
		if(!$this->_headersRow) $this->addHeadersRow();
		return $this->_headersRow;
	}
	
	/**
	 * The headers row.
	 * 
	 * @var Ea_Layout_Table_Row
	 */
	protected $_headersRow=null;
	
	/**
	 * To know if table was populated.
	 * @var boolean
	 */
	protected $_populated=false;

	/**
	 * This method will populate the table from the records, filling the table with record rows.
	 * If you don't call populate(), preRender() will do it for you.
	 * 
	 * @param array $config the config array for the row constructor 
	 * @param string $class the row class
	 */
	public function populate($config=null, $class='Ea_Layout_Table_Row')
	{
		$this->_populated=true;
		if($this->_headersRow)$this->add($this->_headersRow);
		foreach($this->_records as $record)
		{
			$this->addRow($config, true, $class);
			foreach($this->_columns as $column)
			{
				$this->addCell($column->getContent($record), $column->getConfig($record), true, $column->getClass($record));
			}
		}
	}
	
	public function preRender()
	{
		parent::preRender();
		if(!$this->_populated) $this->populate();
	}
}

?>