<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.3.5-20090209
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Table.php';
require_once 'Ea/Layout/Record/Adapter/Interface.php';
require_once 'Ea/Layout/Record/Config/Modifier/Interface.php';
require_once 'Ea/Model/Layout.php';
require_once 'Ea/Layout/Record/Table/Exception.php';

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
	
	/**
	 * Table is multi records by default.
	 * 
	 * @var boolean
	 */
	protected $_multiple=true; 

	public function setMultiple($multiple=true)
	{
		$this->_multiple=$multiple;
	}
	
	public function isMultiple()
	{
		return $this->_multiple;
	}
	
	/**
	 * Table layout constructor.
	 * 
	 * @param array $config associative array of parameters
	 *  
	 */
	public function __construct($config=null)
	{
		parent::__construct($config);
		if($config instanceof Ea_Model_Layout)
		{
			$config=array('model'=>$config);
		}
		if(is_array($config))
		{
			if(array_key_exists('model', $config))
			{
				$this->applyModel($config['model']);
			}
			if(array_key_exists('orientation', $config))
			{
				$this->setOrientation($config['orientation']);
			}
		}
	}
	
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
		$this->setMultiple(true);
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
		$this->setMultiple(false);
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
		
	protected $_displayHeader=false;
	
	/**
	 * Toggle header row display.
	 * 
	 * @param boolean $display
	 */
	public function displayHeader($display=true)
	{
		$this->_displayHeader=$display;
	}
	
	/**
	 * Add a column to the table.
	 * 
	 * @param Ea_Layout_Record_Adapter_Interface $column an object that take record and return Ea_Layout_Abstract
	 * @param mixed $content a header content if given
	 * @param numeric|string $idCol a column id, if null auto increment
	 * @param array $config the header class constructor config array
	 * @param string $class the header class
	 * @param string $mode append|before|after
	 * @param string $seekCol reference col for before and after
	 * @see Ea_Layout_Record_Adapter_Interface
	 */
	public function addColumn(Ea_Layout_Record_Adapter_Interface $column, $content=null, $idCol=null, $headerConfig=null, $recordConfig=null, $headerClass='Ea_Layout_Table_Header', $recordClass='Ea_Layout_Table_Cell', $mode='append', $seekCol=null)
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
		$ndata=array('record'=>array('adapter'=>$column, 'config'=>$recordConfig, 'class'=>$recordClass), 'header'=>array('content'=>$content, 'config'=>$headerConfig, 'class'=>$headerClass));
		switch($mode)
		{
			case 'before': case 'after':
				if((!array_key_exists($seekCol, $this->_columns))||($seekCol===null))
				{
					throw new Ea_Layout_Record_Table_Exception("$seekCol : column does not exist");
				}
				$cols=array();
				foreach($this->_columns as $cid=>$cdata)
				{
					if($cid==$seekCol)
					{
						if($mode=='before')
						{
							$cols[$idCol]=$ndata;
						}	
					}
					$cols[$cid]=$cdata;
					if($cid==$seekCol)
					{
						if($mode=='after')
						{
							$cols[$idCol]=$ndata;
						}
					}
				}
				$this->_columns=$cols;
				break;
			default:
				$this->_columns[$idCol]=$ndata;
		}		
		if($content) $this->displayHeader(true);
		
		return $idCol;
	}

	public function addColumnAfter($seekCol, Ea_Layout_Record_Adapter_Interface $column, $content=null, $idCol=null, $headerConfig=null, $recordConfig=null, $headerClass='Ea_Layout_Table_Header', $recordClass='Ea_Layout_Table_Cell')
	{
		$this->addColumn($column, $content, $idCol, $headerConfig, $recordConfig, $headerClass, $recordClass, 'after', $seekCol);
	}

	public function addColumnBefore($seekCol, Ea_Layout_Record_Adapter_Interface $column, $content=null, $idCol=null, $headerConfig=null, $recordConfig=null, $headerClass='Ea_Layout_Table_Header', $recordClass='Ea_Layout_Table_Cell')
	{
		$this->addColumn($column, $content, $idCol, $headerConfig, $recordConfig, $headerClass, $recordClass, 'before', $seekCol);
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

	public function setColumnAdapter($idCol, Ea_Layout_Record_Adapter_Interface $adapter)
	{
		if(!array_key_exists($idCol, $this->_columns))
		{
			throw new Ea_Layout_Record_Table_Exception("$idCol : unknown column");
		}
		$this->_columns[$idCol]['record']['adapter']=$adapter;
	}
	
	public function setColumnConfig($idCol, $config)
	{
		if(!array_key_exists($idCol, $this->_columns))
		{
			throw new Ea_Layout_Record_Table_Exception("$idCol : unknown column");
		}
		$this->_columns[$idCol]['record']['config']=$column;
	}

	public function setColumnClass($idCol, $class)
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
	 * Callback called to modify the row config depending on record.
	 * $config=callback($record, $i, $config)
	 * 
	 * @var array(Ea_Layout_Record_Config_Modifier_Interface)
	 */
	protected $_recordConfigRowModifier=array();
	
	public function addRecordConfigRowModifier($modifier)
	{
		$this->_recordConfigRowModifier[]=$modifier;	
	}

	/**
	 * Callback called to modify the cell config depending on record.
	 * $config=callback($record, $i, $column, $config)
	 * 
	 * @var array(Ea_Layout_Record_Config_Modifier_Interface)
	 */
	protected $_recordConfigCellModifier=array();
	
	public function addRecordConfigCellModifier($modifier)
	{
		$this->_recordConfigCellModifier[]=$modifier;	
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
		if(!$this->_orientation)
		{
			if($this->isMultiple()) $this->_orientation=self::orientation_vertical;
			else $this->_orientation=self::orientation_horizontal;
		}
		if($this->_orientation==self::orientation_vertical)
		{
			if($this->_displayHeader)
			{
				$this->addRow($headerConfig, true, $headerRowClass);
				foreach($this->_columns as $column)
				{
					$this->addCell($column['header']['content'], $column['header']['config'], true, $column['header']['class']);
				}
			}
			if($this->isMultiple()) $i=0;
			else $i=null;
			foreach($this->_records as $record)
			{
				$config=$recordConfig;
				foreach($this->_recordConfigRowModifier as $modifier)
				{
					$config=$modifier->modify($config, $record, $i);
				}
				$this->addRow($config, true, $recordRowClass);
				foreach($this->_columns as $colName=>$column)
				{
					$config=$column['record']['config'];
					foreach($this->_recordConfigCellModifier as $modifier)
					{
						$config=$modifier->modify($config, $record, $i, $colName);
					}
					$this->addCell($column['record']['adapter']->getContent($record, $i), $column['record']['config'], true, $column['record']['class']);
				}
				if($this->isMultiple()) $i++;
			}
		}
		else
		{
			foreach($this->_columns as $colName=>$column)
			{
				$this->addRow($headerConfig, true, $headerRowClass);
				if($this->_displayHeader) $this->addCell($column['header']['content'], $column['header']['config'], true, $column['header']['class']);
				
				if($this->isMultiple()) $i=0;
				else $i=null;
				foreach($this->_records as $record)
				{
					$config=$column['record']['config'];
					foreach($this->_recordConfigCellModifier as $modifier)
					{
						$config=$modifier->modify($config, $record, $i, $colName);
					}
					$this->addCell($column['record']['adapter']->getContent($record, $i), $config, true, $column['record']['class']);
					if($this->isMultiple()) $i++;
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