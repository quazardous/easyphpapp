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
 * Little modifier to add alterative classes to rows.
 *
 */
class Ea_Layout_Record_Config_Modifier_Row_Alternate implements Ea_Layout_Record_Config_Modifier_Interface
{
	protected $_class1=null;
	protected $_class2=null;
	
	public function __construct($class1, $class2)
	{
		$this->_class1=$class1;
		$this->_class2=$class2;
	}
	
	public function modify($config, $record, $i, $column=null)
	{
		if($column===null)
		{
			if(isset($config['class'])&&is_array($config['class']))
			{
				if($i%2) $config['class'][]=$this->_class2;
				else $config['class'][]=$this->_class1;
			}
			else
			{
				if($i%2)$config['class']=$config['class']." {$this->_class2}";
				else $config['class']=$config['class']." {$this->_class1}";
				$config['class']=trim($config['class']);
			}
		}
		return $config;
	}
}
