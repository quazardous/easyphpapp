<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.0.20081112
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Table.php';
require_once 'Ea/Layout/Record/Adapter/Interface.php';

require_once 'Ea/Model/Layout.php';

/**
 * Table of records layout class.
 * This class let you create a table of records.
 * 
 */
class Ea_Layout_Record_Table extends Ea_Layout_Table
{
	const orientation_vertical   = 'vertical';
	const orientation_horizontal = 'horizontal';
	
	protected $_orientation=null;
	
	public function setOrientation($orientation)
	{
		if(strtolower($orientation)==self::orientation_horizontal) $this->_orientation=self::orientation_horizontal;
		else $this->_orientation=self::orientation_vertical;
	}
	
	/**
	 * The array of records.
	 * 
	 * @var array(record)
	 */
	protected $_records=array();

	public function applyModel(Ea_Model_Layout $model, $headerConfig=null, $recordConfig=null, $headerClass='Ea_Layout_Table_Header', $recordClass='Ea_Layout_Table_Cell')
	{
		foreach($model->getOrderedColumns() as $column)
		{
			if($model->getColumnDisplay($column))
			{
				$this->addColumn(
					$model->getColumnAdapter($column),
					$model->getColumnHeader($column),
					$column,
					$headerConfig, $recordConfig, $headerClass, $recordClass);
			}
		}
	} 
	
	/**
	 * You can set the array of records.
	 * The array you pass must implement Iterator inteface.
	 * 
	 * @param Iterator $records
	 */
	public function setRecords($records)
	{
		if(!$this->_orientation) $this->setOrientation(self::orientation_vertical);
		if($records===null)$records=array();
		if(!(is_array($records)||($records instanceof Iterator)))
		{
			throw new Ea_Layout_Record_Table_Exception("Array of records must be an array or an Iterator");
		}
		$this->_records=$records;
	}

	/**
	 * You can set only one record.
	 * 
	 * @param mixed $record
	 */
	public function setRecord($record)
	{
		if(!$this->_orientation) $this->setOrientation(self::orientation_horizontal);
		$this->_records=array($record);
	}
	
	/**
	 * Add a record.
	 * 
	 * @param record $record
	 */
	public function addRecord($record)
	{
		if(!$this->_orientation) $this->setOrientation(self::orientation_vertical);
		array_push($this->_records, $record);
	}

	/**
	 * Columns to display
	 * @var array(Ea_Layout_Record_Adapter_Interface)
	 */
	protected $_columns=array();
	
	protected $_autoIdCol=0;
		
	protected $_displayHeaderRow=false;
	
	/**
	 * Toggle header row display.
	 * 
	 * @param boolean $display
	 */
	public function displayHeaderRow($display=true)
	{
		$this->_displayHeaderRow=$display;
	}
	
	/**
	 * Add a column to the table.
	 * 
	 * @param Ea_Layout_Record_Adapter_Interface $column an object that take record and return Ea_Layout_Abstract
	 * @param mixed $content a header content if given
	 * @param numeric|string $idCol a column id, if null auto increment
	 * @param array $config the header class constructor config array
	 * @param string $class the header class
	 * @see Ea_Layout_Record_Adapter_Interface
	 */
	public function addColumn(Ea_Layout_Record_Adapter_Interface $column, $content=null, $idCol=null, $headerConfig=null, $recordConfig=null, $headerClass='Ea_Layout_Table_Header', $recordClass='Ea_Layout_Table_Cell')
	{
		if($idCol===null)
		{
			$idCol=$this->_autoIdCol;
			$this->_autoIdCol++;
		}
		if(array_key_exists($idCol, $this->_columns))
		{
			throw new Ea_Layout_Record_Table_Exception("$idCol : column id already used");
		}
		$this->_columns[$idCol]=array('record'=>array('adapter'=>$column, 'config'=>$recordConfig, 'class'=>$recordClass), 'header'=>array('content'=>$content, 'config'=>$headerConfig, 'class'=>$headerClass));
		
		if($content) $this->displayHeaderRow(true);
		
		return $idCol;
	}

	/**
	 * Set the column adapter.
	 * Works only on existing columns.
	 * 
	 * @param numeric|string $idCol a column id, if null auto increment
	 * @param Ea_Layout_Record_Adapter_Interface $column an object that take record and return Ea_Layout_Abstract
	 * @param array $config the cell class constructor config array
	 * @param string $class the cell class
	 * @see Ea_Layout_Record_Adapter_Interface
	 */
	public function setColumn($idCol, Ea_Layout_Record_Adapter_Interface $column, $config=null,  $class='Ea_Layout_Table_Cell')
	{
		if(!array_key_exists($idCol, $this->_columns))
		{
			throw new Ea_Layout_Record_Table_Exception("$idCol : unknown column");
		}
		$this->_columns[$idCol]['record']=array('adapter'=>$column, 'config'=>$config, 'class'=>$class);
	}

	public function seColumnAdapter($idCol, Ea_Layout_Record_Adapter_Interface $column)
	{
		if(!array_key_exists($idCol, $this->_columns))
		{
			throw new Ea_Layout_Record_Table_Exception("$idCol : unknown column");
		}
		$this->_columns[$idCol]['record']['adapter']=$column;
	}
	
	public function seColumnConfig($idCol, $config)
	{
		if(!array_key_exists($idCol, $this->_columns))
		{
			throw new Ea_Layout_Record_Table_Exception("$idCol : unknown column");
		}
		$this->_columns[$idCol]['record']['config']=$column;
	}

	public function seColumnClass($idCol, $class)
	{
		if(!array_key_exists($idCol, $this->_columns))
		{
			throw new Ea_Layout_Record_Table_Exception("$idCol : unknown column");
		}
		$this->_columns[$idCol]['record']['class']=$class;
	}
	
	/**
	 * Set the column header.
	 * Works only on existing columns.
	 * 
	 * @param numeric|string $idCol a column id, if null auto increment
	 * @param mixed $content a header content if given
	 * @param array $config the header class constructor config array
	 * @param string $class the header class
	 */
	public function setColumnHeader($idCol, $content, $config=null, $class='Ea_Layout_Table_Header')
	{
		if(!array_key_exists($idCol, $this->_columns))
		{
			throw new Ea_Layout_Record_Table_Exception("$idCol : unknown column");
		}
		$this->_columns[$idCol]['header']=array('content'=>$content, 'config'=>$config, 'class'=>$class);
	}
	
	public function setColumnHeaderContent($idCol, $content)
	{
		if(!array_key_exists($idCol, $this->_columns))
		{
			throw new Ea_Layout_Record_Table_Exception("$idCol : unknown column");
		}
		$this->_columns[$idCol]['header']['content']=$content;
	}

	public function setColumnHeaderConfig($idCol, $config)
	{
		if(!array_key_exists($idCol, $this->_columns))
		{
			throw new Ea_Layout_Record_Table_Exception("$idCol : unknown column");
		}
		$this->_columns[$idCol]['header']['config']=$config;
	}

	public function setColumnHeaderClass($idCol, $class)
	{
		if(!array_key_exists($idCol, $this->_columns))
		{
			throw new Ea_Layout_Record_Table_Exception("$idCol : unknown column");
		}
		$this->_columns[$idCol]['header']['class']=$class;
	}
	
	/**
	 * To know if table was populated.
	 * @var boolean
	 */
	protected $_populated=false;

	/**
	 * This method will populate the table from the records, filling the table with record rows.
	 * If you don't call populate(), preRender() will do it for you.
	 * 
	 * @param array $headerConfig the config array for the row constructor 
	 * @param array $recordConfig the config array for the row constructor 
	 * @param string $headerRowClass the row class
	 * @param string $recordRowClass the row class
	 */
	public function populate($headerConfig=null, $recordConfig=null, $headerRowClass='Ea_Layout_Table_Row', $recordRowClass='Ea_Layout_Table_Row')
	{
		$this->_populated=true;
		if($this->_orientation==self::orientation_vertical)
		{
			if($this->_displayHeaderRow)
			{
				$this->addRow($headerConfig, true, $headerRowClass);
				foreach($this->_columns as $column)
				{
					$this->addCell($column['header']['content'], $column['header']['config'], true, $column['header']['class']);
				}
			}
			$i=0;
			foreach($this->_records as $record)
			{
				$this->addRow($recordConfig, true, $recordRowClass);
				foreach($this->_columns as $column)
				{
					$this->addCell($column['record']['adapter']->getContent($record, $i), $column['record']['config'], true, $column['record']['class']);
				}
				$i++;
			}
		}
		else
		{
			foreach($this->_columns as $column)
			{
				$this->addRow($headerConfig, true, $headerRowClass);
				$this->addCell($column['header']['content'], $column['header']['config'], true, $column['header']['class']);
				$i=0;
				foreach($this->_records as $record)
				{
					$this->addCell($column['record']['adapter']->getContent($record, $i), $column['record']['config'], true, $column['record']['class']);
					$i++;
				}
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