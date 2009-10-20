<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.0-20091020
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Record/Table.php';
require_once 'Ea/Layout/Record/Table/Populate/Adapter/Interface.php';
require_once 'Ea/Layout/Table/Row.php';

/**
 * Default implementation for row/cell populate adapter.
 *
 */
class Ea_Layout_Record_Table_Populate_Adapter_Default implements Ea_Layout_Record_Table_Populate_Adapter_Interface
{
	/**
	 * Table ref.
	 * 
	 * @var Ea_Layout_Record_Table
	 */
	protected $_table=null;

	/**
	 * Return table..
	 * 
	 * @return Ea_Layout_Record_Table
	 */
	public function getTable()
	{
		return $this->_table;
	}
	
	/**
	 * Acknowledge the table.
	 * 
	 * @param Ea_Layout_Record_Table $table
	 */
	public function acknowledge(Ea_Layout_Record_Table $table)
	{
		$this->_table=$table;
	}
	
	protected $_headerRowConfig=null;
	
	protected $_recordRowConfig=null;
	
	protected $_headerRowClass='Ea_Layout_Table_Row';
	
	protected $_recordRowClass='Ea_Layout_Table_Row';

	protected $_headerCellConfig=null;
	
	protected $_recordCellConfig=null;
	
	protected $_headerCellClass='Ea_Layout_Table_Header';
	
	protected $_recordCellClass='Ea_Layout_Table_Cell';
	
	protected $_onlyRecordValue=false;
		
	public function __construct($config=array())
	{
		if(isset($config['header_row_config']))
		{
			$this->_headerRowConfig=$config['header_row_config'];
		}
		if(isset($config['header_row_class']))
		{
			$this->_headerRowClass=$config['header_row_class'];
		}
		if(isset($config['header_cell_config']))
		{
			$this->_headerCellConfig=$config['header_cell_config'];
		}
		if(isset($config['header_cell_class']))
		{
			$this->_headerCellClass=$config['header_cell_class'];
		}
		if(isset($config['record_row_config']))
		{
			$this->_recordRowConfig=$config['record_row_config'];
		}
		if(isset($config['record_row_class']))
		{
			$this->_recordRowClass=$config['record_row_class'];
		}
		if(isset($config['record_cell_config']))
		{
			$this->_recordCellConfig=$config['record_cell_config'];
		}
		if(isset($config['record_cell_class']))
		{
			$this->_recordCellClass=$config['record_cell_class'];
		}
		if(isset($config['raw_record_value']))
		{
			$this->_rawRecordValue=$config['raw_record_value'];
		}
	}

	/**
	 * Return the row for the record if spread is vertical
	 * 
	 * @param mixed $record the data record
	 * @param int $i
	 * @return Ea_Layout_Table_Row
	 */
	public function getRecordRow($record, $i)
	{
		$config=$this->_recordRowConfig;
		foreach($this->getTable()->getRecordConfigRowModifiers() as $modifier)
		{
			//FIXME modifiers like that sucks => put it in adapter
			$config=$modifier->modify($config, $record, $i);
		}
		$class=$this->_recordRowClass;
		Zend_Loader::loadClass($class);
		$row=new $class($config);
		
		foreach($this->getTable()->getListColumns() as $idCol)
		{
			$config=$this->_recordCellConfig;
			foreach($this->getTable()->getRecordConfigCellModifiers() as $modifier)
			{
				$config=$modifier->modify($config, $record, $i, $idCol);
			}
			$row->addCell(
				$this->getTable()->getColumnAdapter($idCol)->getContent($record, $i),
				$config,
				true,
				$this->_recordCellClass);
		}
		
		return $row;
	}

	/**
	 * Return the row for the header if spread is vertical
	 * 
	 * @param array $columns the columns definition
	 * @return Ea_Layout_Table_Row
	 */
	public function getHeaderRow()
	{
		$class=$this->_headerRowClass;
		Zend_Loader::loadClass($class);
		$row=new $class($this->_headerRowConfig);
		foreach($this->getTable()->getListColumns() as $idCol)
		{
			$row->addCell(
				$this->getTable()->getColumnHeader($idCol),
				$this->_headerCellConfig, 
				true, 
				$this->_headerCellClass);
		}
		return $row;
	}
	
	/**
	 * Return the row if spread is horizontal
	 * 
	 * @param string $idCol
	 * @return Ea_Layout_Table_Row
	 */
	public function getColRow($idCol, $records)
	{
		$class=$this->_headerRowClass;
		Zend_Loader::loadClass($class);
		$row=new $class($this->_headerRowConfig);
		
		if($this->getTable()->isDisplayheader())
		{
			$row->addCell(
				$this->getTable()->getColumnHeader($idCol), 
				$this->_headerCellConfig, 
				true, 
				$this->_headerCellClass);
		}
		
		foreach($records as $i => $record)
		{
			$config=$this->_recordCellConfig;
			foreach($this->getTable()->getRecordConfigCellModifiers() as $modifier)
			{
				$config=$modifier->modify($config, $record, $i, $idCol);
			}
			$row->addCell($this->getTable()->getColumnAdapter($idCol)->getContent($record, $i), $config, true, $this->_recordCellClass);
		}
		
		return $row;
	}
}