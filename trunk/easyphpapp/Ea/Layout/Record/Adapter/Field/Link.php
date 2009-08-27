<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.3.7-20090702
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Record/Adapter/Field.php';
require_once 'Ea/Route.php';
require_once 'Ea/Layout/Link.php';

/**
 * Field link from record (array or object).
 */
class Ea_Layout_Record_Adapter_Field_Link extends Ea_Layout_Record_Adapter_Field
{
	
	/**
	 * The link label.
	 * 
	 * @var Ea_Layout_Abstract|string
	 */
	protected $_label=null;
	
	/**
	 * Name of param in the url.
	 * 
	 * @var string|array
	 */
	protected $_params=array();
	
	/**
	 * Base url or route.
	 * 
	 * @var Ea_Route|string
	 */
	protected $_url_or_route=null;
	
	/**
	 * Link layout config.
	 * 
	 * @var array
	 */
	protected $_config=null;
	
	/**
	 * Link target.
	 * 
	 * @var string
	 */
	protected $_string=null;
	
	/**
	 * Constructor.
	 * 
	 * @param array|string $fields of record to specify in the link
	 * 		if simple array : list of fields.
	 * 		if associative array : field=>param
	 * 		each item can be an associative array with 'column', 'module' and 'type' ('param', 'raw', 'action', 'module').
	 * @param Ea_Layout_Record_Adapter_Interface|Ea_Layout_Abstract|string $label 
	 * @param Ea_Route|string $url_or_route base url or route
	 */
	public function __construct($fields, $label, $url_or_route, $target=null, $config=null)
	{
		//parent::__construct($field);
		if(!is_array($fields)) $fields=array($fields);
		foreach($fields as $param=>$column)
		{
			if(!is_array($column)) $column=array('column'=>$column);
			if(!is_string($param)) $param=$column['column'];
			$this->_params[$param]=$column;
		}
		$this->_url_or_route=$url_or_route;
		$this->_config=$config;
		$this->_target=$target;
		$this->_label=$label;
	}
	
	/**
	 * This function must return content from record.
	 * 
	 * @param array $record
	 * @param integer $i record number
	 * @return string
	 */
	public function getContent($record, $i)
	{
		if($this->_url_or_route instanceof Ea_Route)
		{
			$route=clone $this->_url_or_route;
			foreach($this->_params as $param=>$column)
			{
				$module=null;
				$type='param';
				if(isset($column['type'])) $type=$column['type'];
				if(isset($column['module'])) $module=$column['module'];
				switch($type)
				{
					case 'raw':
						$route->setRawParam($param, $this->getValue($record, $column['column']));
						break;
					case 'action':
						$route->setAction($this->getValue($record, $column['column']));
						break;
					case 'module':
						$route->setModule($this->getValue($record, $column['column']));
						break;
					default:
						$route->setParam($param, $this->getValue($record, $column['column']), $module);
				}
			}
		}
		else
		{
			$urllist=parse_url($this->_url_or_route);
			if($urllist&&isset($urllist['query']))
			{
				parse_str($urllist['query'], $arr);
			}
			else
			{
				$urllist=array();
				$arr=array();
			}
			foreach($this->_params as $param=>$column)
			{
				$arr[$param]=$this->getValue($record, $column['column']);
			}			
			$urllist['query']=http_build_query($arr);
			$route=(isset($urllist["scheme"])?$urllist["scheme"]."://":"").
	           (isset($urllist["user"])?$urllist["user"].":":"").
	           (isset($urllist["pass"])?$urllist["pass"]."@":"").
	           (isset($urllist["host"])?$urllist["host"]:"").
	           (isset($urllist["port"])?":".$urllist["port"]:"").
	           (isset($urllist["path"])?$urllist["path"]:"").
	           (isset($urllist["query"])?"?".$urllist["query"]:"").
	           (isset($urllist["fragment"])?"#".$urllist["fragment"]:"");
		}
		if($this->_label instanceof Ea_Layout_Record_Adapter_Interface)
		{
			$label=$this->_label->getContent($record, $i);
		}
		else
		{
			$label=$this->_label;
		}
		return new Ea_Layout_Link($route, $label, $this->_target, $this->_config);
	}
}

?>