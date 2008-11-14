<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Model
 * @subpackage  Layout
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.1-20081114
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Model/Layout/Header/Adapter/Interface.php';
require_once 'Ea/Layout/Link.php';

/**
 * Basic adapter to get sorting header cell content from field.
 * 
 * @see Ea_Model_Layout
 */
class Ea_Model_Layout_Header_Adapter_Sort implements Ea_Model_Layout_Header_Adapter_Interface
{

	protected $_sortParam=null;
	protected $_url=null;
	protected $_linkClass='Ea_Layout_Link';
	protected $_config=null;
	protected $_target=null;
	
	/**
	 * Constructor.
	 * 
	 * @param string $sortParam name of sorting param
	 * @param $url base url or route
	 * @param $target
	 * @param $config config for link layout constructor
	 * @param $linkClass link layout class or derived class
	 */
	public function __construct($sortParam, $url=null, $target=null, $config=null, $linkClass='Ea_Layout_Link')
	{
		$this->_sortParam=$sortParam;
		$this->_url=$url;
		$this->_linkClass=$linkClass;
		$this->_target=$target;
		$this->_config=$config;
	}
	
	/**
	 * Return the header content.
	 * 
	 * @param string $column
	 * @param Ea_Model_Layout $model
	 * @return Ea_Layout_Abstract|string
	 */
	function getHeader($column, Ea_Model_Layout $model)
	{
		$class=$this->_linkClass;
		if($this->_url instanceof Ea_Route)
		{
			$url=clone $this->_url;
			$url->setParam($this->_sortParam, $column);
		}
		else
		{
			$urlv=parse_url($this->_url);
			parse_str($urlv['query'], $queryv);
			$queryv[$this->_sortParam]=$column;
			$urlv['query']=http_build_query($queryv);
			$url=(isset($urlv["scheme"])?$urlv["scheme"]."://":"").
				(isset($urlv["user"])?$urlv["user"].":":"").
				(isset($urlv["pass"])?$urlv["pass"]."@":"").
				(isset($urlv["host"])?$urlv["host"]:"").
				(isset($urlv["port"])?":".$urlv["port"]:"").
				(isset($urlv["path"])?$urlv["path"]:"").
				(isset($urlv["query"])?"?".$urlv["query"]:"").
				(isset($urlv["fragment"])?"#".$urlv["fragment"]:"");
		}
		return new $class($url, $column, $this->_target, $this->_config);
	}
}
?>