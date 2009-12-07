<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Base
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.2-20091203
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Flash/Exception.php';
require_once 'Ea/Layout/Container.php';
require_once 'Ea/Layout/Single.php';

/**
 * Simple flash object layout.
 * 
 */
class Ea_Layout_Flash extends Ea_Layout_Container
{
	protected $_tag='object';
	
	protected $_params=array(
		'allowfullscreen'=>'true',
		'allowscriptaccess'=>'true',
		'wmode'=>'transparent',
		'flashvars'=>'',
		'quality'=>'high',
	);

	protected $_movie=null;
	public function setMovie($movie)
	{
		$this->_movie=$movie;
	}
	
	protected $_width=null;
	public function setWidth($width)
	{
		$this->_width=$width;
	}
	
	protected $_height=null;
	public function setHeight($height)
	{
		$this->_height=$height;
	}
	
	public function setParam($name, $value)
	{
		switch($name)
		{
			case 'width':
				$this->setWidth($value);
				break;
			case 'height':
				$this->setHeight($value);
				break;
			case 'src': case 'movie':
				$this->setMovie($value);
				break;
			default:
				$this->_params[$name]=$value;
		}
	}
	
	/**
	 * Set an attribute.
	 *
	 * @param string $name
	 * @param string|numeric $value
	 * 
	 * @see $_attributes
	 */
	public function setAttribute($name, $value)
	{
		$name=strtolower($name);
		switch($name)
		{
			case 'width':
				$this->setWidth($value);
				break;
			case 'height':
				$this->setHeight($value);
				break;
			case 'src': case 'movie':
				$this->setMovie($value);
				break;
			default:
				if(isset($this->_params[$name])) $this->setParam($name, $value);
				else parent::setAttribute($name, $value);
		}
	}	
	
	public function __construct($movie, $width=null, $height=null, array $params=array(), $config=null)
	{
		$this->_params=array_merge($this->_params, $params);
		if($width!==null)
		{
			$this->setWidth($width);
		}
		if($height!==null)
		{
			$this->setHeight($height);
		}
		$this->setMovie($movie);
		parent::__construct(null, $config);
	}
	
	public function preRender()
	{
		//TODO optimize for multiple rendering
		$render=parent::preRender();
		$this->resetSubLayouts();
		$this->_add($p=new Ea_Layout_Single('param'));
		$p->setAttribute('name', 'movie');
		$p->setAttribute('value', $this->_movie);
		$embed=new Ea_Layout_Single('embed');
		$embed->setAttribute('src', $this->_movie);
		$embed->setAttribute('type', 'application/x-shockwave-flash');
		
		if($this->_width!==null)
		{
			$this->_setAttribute('width', $this->_width);
			$embed->setAttribute('width', $this->_width);
			$this->_add($p=new Ea_Layout_Single('param'));
			$p->setAttribute('name', 'width');
			$p->setAttribute('value', $this->_width);
		}
		if($this->_height!==null)
		{
			$this->_setAttribute('height', $this->_height);
			$embed->setAttribute('height', $this->_height);
			$this->_add($p=new Ea_Layout_Single('param'));
			$p->setAttribute('name', 'height');
			$p->setAttribute('value', $this->_height);
		}
		
		foreach($this->_params as $param=>$value)
		{
			$this->_add($p=new Ea_Layout_Single('param'));
			$p->setAttribute('name', $param);
			$p->setAttribute('value', $value);
			$embed->setAttribute($param, $value);
		}
		$this->_add($embed);
		return true;
	}
	
	public function add($content, $append=true)
	{
		throw new Ea_Layout_Flash_Exception('Adding sub layout is not allowed');
	}
	
}
?>