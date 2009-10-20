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

require_once 'Ea/Layout/Table.php';
require_once 'Ea/Layout/Record/Adapter/Interface.php';
require_once 'Ea/Layout/Record/Config/Modifier/Interface.php';
require_once 'Ea/Model/Layout.php';
require_once 'Ea/Layout/Record/Table/Exception.php';
require_once 'Ea/Layout/Record/Table/Populate/Adapter/Default.php';

/**
 * Table of records layout class.
 * This class let you create a table of records.
 * 
 */
class Ea_Layout_Record_Table extends Ea_Layout_Table
{
	const spread_vertical   = 'vertical';
	const spread_horizontal = 'horizontal';
	
	protected $_spread=null;
	
	public function setSpread($spread)
	{
		if(strtolower($spread)==self::spread_horizontal) $this->_spread=self::spread_horizontal;
		else $this->_spread=self::spread_vertical;
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
			if(array_key_exists('spread', $config))
			{
				$this->setSpread($config['spread']);
			}
		}
	}
	
	public function applyModel(Ea_Model_Layout $model)
	{
		//, $headerConfig=null, $recordConfig=null, $headerClass='Ea_Layout_Table_Header', $recordClass='Ea_Layout_Table_Cell'
		foreach($model->getOrderedColumns() as $column)
		{
			if($model->getColumnDisplay($column))
			{
				$this->addColumn(
					$model->getColumnAdapter($column),
					$model->getColumnHeader($column),
					$column);/*,
					$headerConfig, $recordConfig, $headerClass, $recordClass);*/
			}
		}
	} 
	
	/**
	 * You can set the array of records.
	 * The array you pass must implement Iterator inteface.
	 * 
	 * @param Iterator $records
	 * 
	 */
	public function setRecords($records, $adapter=null)
	{
		$this->setMultiple(true);
		if(!$this->_spread) $this->setSpread(self::spread_vertical);
		$this->resetRecords();
		$this->addRecords($records, $adapter);
	}

	/**
	 * You can add an array of records.
	 * The array you pass must implement Iterator inteface.
	 * 
	 * @param Iterator $records
	 * @param Ea_Layout_Record_Table_Populate_Adapter_Interface $adapter
	 */
	public function addRecords($records, $adapter=null)
	{
		$this->setMultiple(true);
		if(!$this->_spread) $this->setSpread(self::spread_vertical);
		if($records===null)$records=array();
		foreach($records as $record)
		{
			$this->addRecord($record, $adapter);
		}
	}
	
	/**
	 * You can set only one record.
	 * 
	 * @param mixed $record
	 * @param Ea_Layout_Record_Table_Populate_Adapter_Interface $adapter
	 */
	public function setRecord($record, $adapter=null)
	{
		$this->setMultiple(false);
		$this->resetRecords();
		if(!$this->_spread) $this->setSpread(self::spread_horizontal);
		$this->addRecord($record, $adapter);
	}
	
	/**
	 * Enter description here...
	 * 
	 */
	public function resetRecords()
	{
		$this->_records=array();
		$this->setMultiple(false);
	}
	
	/**
	 * Add a record.
	 * 
	 * @param record $record
	 * @param Ea_Layout_Record_Table_Populate_Adapter_Interface $adapter
	 * @param 
	 */
	public function addRecord($record, $adapter=null)
	{
		if(!$this->_spread) $this->setSpread(self::spread_vertical);
		array_push($this->_records, array('data'=>$record, 'adapter'=>$adapter));
		if(count($this->_records)>1)
		{
			$this->setMultiple(true);
		}
		if($adapter)
		{
			$adapter->acknowledge($this);
		}
	}

	/**
	 * Columns to display
	 * @var array(array(Ea_Layout_Record_Adapter_Interface, ...))
	 */
	protected $_columns=array();
	
	/**
	 * Return the list of columns.
	 * 
	 * @return array
	 */
	public function getListColumns()
	{
		return array_keys($this->_columns);
	}
	
	/**
	 * Get the column record content adapter.
	 * 
	 * @param string $idCol
	 * @return Ea_Layout_Record_Adapter_Interface
	 */
	public function getColumnAdapter($idCol)
	{
		return $this->_columns[$idCol]['record']['adapter'];
	}
	
	/**
	 * Get the column header content.
	 * 
	 * @param string $idCol
	 * @return Ea_Layout_Record_Adapter_Interface
	 */
	public function getColumnHeader($idCol)
	{
		return $this->_columns[$idCol]['header']['content'];
	}
	
	protected $_autoIdCol=0;
		
	public function isDisplayheader()
	{
		return $this->_displayHeader;
	}
	
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
	 * @param string $mode append|before|after
	 * @param string $seekCol reference col for before and after
	 * @see Ea_Layout_Record_Adapter_Interface
	 */
	public function addColumn(Ea_Layout_Record_Adapter_Interface $column, $content=null, $idCol=null, $mode='append', $seekCol=null)
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
		$ndata=array('record'=>array('adapter'=>$column), 'header'=>array('content'=>$content));
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

	public function addColumnAfter($seekCol, Ea_Layout_Record_Adapter_Interface $column, $content=null, $idCol=null)
	{
		$this->addColumn($column, $content, $idCol, 'after', $seekCol);
	}

	public function addColumnBefore($seekCol, Ea_Layout_Record_Adapter_Interface $column, $content=null, $idCol=null)
	{
		$this->addColumn($column, $content, $idCol, 'before', $seekCol);
	}
	
	/**
	 * Set the column.
	 * Works only on existing columns.
	 * 
	 * @param numeric|string $idCol a column id, if null auto increment
	 * @param Ea_Layout_Record_Adapter_Interface $column an object that take record and return Ea_Layout_Abstract
	 * @param mixed $content a header content if given
	 * @see Ea_Layout_Record_Adapter_Interface
	 */
	public function setColumn($idCol, Ea_Layout_Record_Adapter_Interface $column, $content=null)
	{
		if(!array_key_exists($idCol, $this->_columns))
		{
			throw new Ea_Layout_Record_Table_Exception("$idCol : unknown column");
		}
		$this->_columns[$idCol]['record']=array('adapter'=>$column);
		if($content)$this->_columns[$idCol]['header']['content']=$content;
	}

	public function setColumnAdapter($idCol, Ea_Layout_Record_Adapter_Interface $adapter)
	{
		if(!array_key_exists($idCol, $this->_columns))
		{
			throw new Ea_Layout_Record_Table_Exception("$idCol : unknown column");
		}
		$this->_columns[$idCol]['record']['adapter']=$adapter;
	}
	
	/**
	 * Set the column header.
	 * Works only on existing columns.
	 * 
	 * @param numeric|string $idCol a column id, if null auto increment
	 * @param mixed $content a header content if given
	 */
	public function setColumnHeader($idCol, $content)
	{
		if(!array_key_exists($idCol, $this->_columns))
		{
			throw new Ea_Layout_Record_Table_Exception("$idCol : unknown column");
		}
		$this->_columns[$idCol]['header']['content']=$content;
	}
		
	/**
	 * Callback called to modify the row config depending on record.
	 * $config=callback($record, $i, $config)
	 * 
	 * @var array(Ea_Layout_Record_Config_Modifier_Interface)
	 */
	protected $_recordConfigRowModifiers=array();
	
	public function getRecordConfigRowModifiers()
	{
		return $this->_recordConfigRowModifiers;
	}
	
	// TODO => adapter populate
	public function addRecordConfigRowModifier($modifier)
	{
		$this->_recordConfigRowModifiers[]=$modifier;	
	}

	public function getRecordConfigCellModifiers()
	{
		return $this->_recordConfigCellModifiers;
	}
	
	/**
	 * Callback called to modify the cell config depending on record.
	 * $config=callback($record, $i, $column, $config)
	 * 
	 * @var array(Ea_Layout_Record_Config_Modifier_Interface)
	 */
	protected $_recordConfigCellModifiers=array();
	
	public function addRecordConfigCellModifier($modifier)
	{
		$this->_recordConfigCellModifiers[]=$modifier;	
	}
	
	/**
	 * To know if table was populated.
	 * @var boolean
	 */
	protected $_populated=false;

	/**
	 * Return populate adapter.
	 * 
	 * @param int $i
	 * @return Ea_Layout_Record_Table_Populate_Adapter_Interface
	 */
	public function getRecordAdapter($i)
	{
		if($this->isMultiple())
		{
			return $this->_records[$i]['adapter'];
		}
		foreach($this->_records as $record)
		{
			return $record['adapter'];
		}
		return null;
	}
	
	/**
	 * This method will populate the table from the records, filling the table with record rows.
	 * If you don't call populate(), preRender() will do it for you.
	 * 
	 * @param Ea_Layout_Record_Table_Populate_Adapter_Interface $adapter
	 */
	public function populate($adapter=null)
	{
		if(!$adapter)
		{
			$adapter=new Ea_Layout_Record_Table_Populate_Adapter_Default;
		}
		if(!$adapter instanceof Ea_Layout_Record_Table_Populate_Adapter_Interface)
		{
			throw new Ea_Layout_Record_Table_Exception("adapter must implements Ea_Layout_Record_Table_Populate_Adapter_Interface");
		}
		$adapter->acknowledge($this);
		
		$this->_populated=true;
		if(!$this->_spread)
		{
			if($this->isMultiple()) $this->_spread=self::spread_vertical;
			else $this->_spread=self::spread_horizontal;
		}
		if($this->_spread==self::spread_vertical)
		{
			if($this->_displayHeader)
			{
				$this->add($adapter->getHeaderRow());
			}
			// if only one record adapter gets a null
			if($this->isMultiple()) $i=0;
			else $i=0;
			foreach($this->_records as $record)
			{
				if($record['adapter'])
				{
					$a=$record['adapter'];
				}
				else
				{
					$a=$adapter;
				}
				$this->add($a->getRecordRow($record['data'], $this->isMultiple()?$i:null));
				if($this->isMultiple()) $i++;
			}
		}
		else
		{
			if($this->isMultiple()) $i=0;
			else $i=null;
			$records=array();
			foreach($this->_records as $i=>$record)
			{
				// FIXME $i null => '' string(0)
				if($this->isMultiple()) $records[$i]=$record['data'];
				else $records[]=$record['data'];
			}
			
			foreach($this->getListColumns() as $idCol)
			{
				$this->add($adapter->getColRow($idCol, $records));
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