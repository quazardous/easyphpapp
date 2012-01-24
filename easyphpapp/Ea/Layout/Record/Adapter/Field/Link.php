<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Table
 * @author      berlioz [$Author$]
 * @version     $Id$
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Record/Adapter/Field.php';

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
	 * The name of the type column if any.
	 * @var string
	 */
	protected $_linkTypeColumn=null;
	
	/**
	 * Constructor.
	 * 
	 * @param array|string $fields of record to specify in the link
	 * 		if simple array : list of fields.
	 * 		if associative array : field=>param
	 * 		each item can be an associative array with 'column', 'module' and 'type' ('param', 'raw', 'action', 'module', 'linktype').
	 *      for linktype the record fied must provide 'route' or something else. for the something else you can specify more type to build the url (user, host, path, etc).
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
			if(is_string($param)&&!isset($column['column'])) $column['column']=$param;
			if(!is_string($param)) $param=$column['column'];
			$this->_params[$param]=$column;
			if(isset($column['type'])&&$column['type']=='linktype')
			{
				$this->_linkTypeColumn=$column['column'];
			}
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
		$target=$this->_target;
		$linktype=false;
		if($this->_linkTypeColumn)
		{
			$linktype=$this->getRawValue($record, $this->_linkTypeColumn);
		}
		if(!$linktype) $linktype='route';

		if($linktype=='route'&&$this->_url_or_route instanceof Ea_Route)
		{
			$route=clone $this->_url_or_route;
			foreach($this->_params as $param=>$column)
			{
				$type='param';
				if(isset($column['type'])) $type=$column['type'];
				$module=null;
				if(isset($column['module'])) $module=$column['module'];
				switch($type)
				{
					case 'raw':
						$route->setRawParam($param, $this->getRawValue($record, $column['column']));
						break;
					case 'action':
						$route->setAction($this->getRawValue($record, $column['column']));
						break;
					case 'module':
						$route->setModule($this->getRawValue($record, $column['column']));
						break;
					case 'target':
						$target=$this->getRawValue($record, $column['column']);
						break;						
					default:
						$route->setParam($param, $this->getRawValue($record, $column['column']), $module);
				}
			}
		}
		else
		{
			$urllist=false;
			if(!($this->_url_or_route instanceof Ea_Route))
			{
				$urllist=parse_url($this->_url_or_route);
			}
			if(!$urllist)
			{
				$urllist=array();
			}
			if(isset($urllist['query']))
			{
				parse_str($urllist['query'], $arr);
			}
			else
			{
				$arr=array();
			}
			$urllistdefault=array();
			foreach($this->_params as $param=>$column)
			{
				$type='param';
				if(isset($column['type'])) $type=$column['type'];
				switch($type)
				{
					case 'target':
						$target=$this->getRawValue($record, $column['column']);
						break;
					case 'param':
						$arr[$param]=$this->getRawValue($record, $column['column']);
						break;
					default:
						$urllistdefault[$column['type']]=$this->getRawValue($record, $column['column']);
				}	
			}			
			$urllist['query']=http_build_query($arr);
			if(!$urllist['query']) unset($urllist['query']);
			$urllist=array_merge($urllist, $urllistdefault);
			
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
		require_once 'Ea/Layout/Link.php';
		return new Ea_Layout_Link($route, $label, $target, $this->_config);
	}
}
