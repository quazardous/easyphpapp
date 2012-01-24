<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Record/Config/Modifier/Interface.php';

/**
 * Little modifier to add column classes to rows.
 *
 */
class Ea_Layout_Record_Config_Modifier_Column implements Ea_Layout_Record_Config_Modifier_Interface
{
	protected $_columns=null;
	
	public function __construct($columns=null)
	{
		$this->_columns=null;
		if($columns&&(!is_array($columns)))
		{
			$this->_columns=array();
			$columns=array($columns);
			foreach($columns as $column => $class)
			{
				if(is_string($column))
				{
					$this->_columns[$column]=$class;
				}
				else
				{
					$this->_columns[$class]=$class;
				}
			}
		}
	}
	
	public function modify($config, $record, $i, $column=null)
	{
		if($column!==null)
		{
			if(isset($config['class'])&&is_array($config['class']))
			{
				if($this->_columns===null) $config['class'][]=$column;
				else if(isset($this->_columns[$column])) $config['class'][]=$this->_columns[$column];
			}
			else
			{
				if($this->_columns===null) $config['class'].=" {$column}";
				else if(isset($this->_columns[$column])) $config['class'].=" {$this->_columns[$column]}";
				$config['class']=trim($config['class']);
			}
		}
		return $config;
	}
}
