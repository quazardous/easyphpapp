<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      berlioz [$Author$]
 * @version     0.5.2-20110701 [$Id]
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Grid.php';
require_once 'Ea/Layout/Grid/Populate/Adapter/Interface.php';

/**
 * Default implementation for row/cell populate adapter.
 *
 */
class Ea_Layout_Grid_Populate_Adapter_Default implements Ea_Layout_Grid_Populate_Adapter_Interface
{
	/**
	 * Table ref.
	 * 
	 * @var Ea_Layout_Grid
	 */
	protected $_grid=null;

	/**
	 * Return table..
	 * 
	 * @return Ea_Layout_Grid
	 * @deprecated
	 */
	public function getTable()
	{
		return $this->_grid;
	}

	/**
	 * Return grid..
	 * 
	 * @return Ea_Layout_Grid
	 */
	public function getGrid()
	{
		return $this->_grid;
	}	
	
	/**
	 * Acknowledge the grid.
	 * 
	 * @param Ea_Layout_Grid $table
	 */
	public function acknowledge(Ea_Layout_Grid $grid)
	{
		$this->_grid=$grid;
		$this->_grid->assertRow($this->_strictTable);
		if($this->_tableTag)
		{
			$this->_grid->setTag($this->_tableTag);
		}
	}
	
	protected $_headerRowConfig=null;
	
	protected $_recordRowConfig=null;
	
	protected $_headerRowClass='Ea_Layout_Table_Row';
	
	protected $_recordRowClass='Ea_Layout_Table_Row';

	protected $_headerCellConfig=null;
	
	protected $_recordCellConfig=null;
	
	protected $_headerCellClass='Ea_Layout_Table_Header';
	
	protected $_recordCellClass='Ea_Layout_Table_Cell';
	
	protected $_rawRecordValues=false;
	
	protected $_tableTag=null;
	
	protected $_strictTable=true;
		
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
		if(isset($config['raw_record_values']))
		{
			$this->_rawRecordValues=$config['raw_record_values'];
		}
		if(isset($config['table_tag']))
		{
			$this->_tableTag=$config['table_tag'];
		}
		if(isset($config['strict_table']))
		{
			$this->_strictTable=$config['strict_table'];
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
		foreach($this->getGrid()->getRecordConfigRowModifiers() as $modifier)
		{
			//FIXME modifiers like that sucks => put it in adapter
			$config=$modifier->modify($config, $record, $i);
		}
		$class=$this->_recordRowClass;
		require_once 'Zend/Loader.php';
		Zend_Loader::loadClass($class);
		$row=new $class($config);
		
	  foreach($this->getGrid()->getRecordContainerAlters() as $alter)
		{
			$alter->alter($row, $record, $i, 'vertical');
		}
		
		foreach($this->getGrid()->getListColumns() as $idCol)
		{
			$row->add($this->getRecordCell($idCol, $record, $i, 'vertical'));
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
		require_once 'Zend/Loader.php';
		Zend_Loader::loadClass($class);
		$row=new $class($this->_headerRowConfig);
	  foreach($this->getGrid()->getRecordContainerAlters() as $alter)
		{
			$alter->alter($row, null, null, 'vertical', null, true);
		}
		
		foreach($this->getGrid()->getListColumns() as $idCol)
		{
			$cell = $row->addCell(
				$this->getGrid()->getColumnHeader($idCol),
				$this->_headerCellConfig, 
				true, 
				$this->_headerCellClass);
		  
  		foreach($this->getGrid()->getRecordContainerAlters() as $alter)
  		{
  			$alter->alter($cell, null, null, 'vertical', $idCol, true);
  		}
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
		require_once 'Zend/Loader.php';
		Zend_Loader::loadClass($class);
		$row=new $class($this->_headerRowConfig);
	  foreach($this->getGrid()->getRecordContainerAlters() as $alter)
		{
			$alter->alter($row, null, null, 'horizontal', $idCol, null);
		}
		
		if($this->getGrid()->isDisplayheader())
		{
			$cell = $row->addCell(
				$this->getGrid()->getColumnHeader($idCol), 
				$this->_headerCellConfig, 
				true, 
				$this->_headerCellClass);
			foreach($this->getGrid()->getRecordContainerAlters() as $alter)
  		{
  			$alter->alter($cell, null, null, 'horizontal', $idCol, true);
  		}				
		}
		
		foreach($records as $i => $record)
		{
			$row->add($this->getRecordCell($idCol, $record, $i, 'horizontal'));
		}
		
		return $row;
	}

	/**
	 * Return the record cell.
	 * 
	 * @param string $idCol
	 * @param mixed $record
	 * @param int $i
	 * @param 'vertical'|'horizontal' $spread
	 * @return Ea_Layout_Table_Cell
	 */
	public function getRecordCell($idCol, $record, $i, $spread)
	{
		$adapter=$this->getGrid()->getRecordAdapter($i);
		if($adapter&&$adapter!==$this)
		{
			// be carefull not to provoque recursion here
			return $adapter->getRecordCell($idCol, $record, $i, $spread);
		}
		$class=$this->_recordCellClass;
		require_once 'Zend/Loader.php';
		Zend_Loader::loadClass($class);
		$config=$this->_recordCellConfig;
		foreach($this->getGrid()->getRecordConfigCellModifiers() as $modifier)
		{
			$config=$modifier->modify($config, $record, $i, $idCol);
		}
		$cell = new $class($config);
	  foreach($this->getGrid()->getRecordContainerAlters() as $alter)
		{
			$alter->alter($cell, $record, $i, $spread, $idCol);
		}
		
		if($this->_rawRecordValues)
		{
			$content=$this->getGrid()->getColumnAdapter($idCol)->getRawValue($record);
		}
		else
		{
			$content=$this->getGrid()->getColumnAdapter($idCol)->getContent($record, $i);
		}
		$cell->add($content);
		return $cell;
	}
	
}