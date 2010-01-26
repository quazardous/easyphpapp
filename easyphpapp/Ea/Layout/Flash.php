<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Base
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.2-20091218
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
		$this->protectedAttribute('width');
		$this->protectedAttribute('height');
		$this->protectedAttribute('movie');
		$this->protectedAttribute('src', 'setMovie', 'getMovie');
	}
	
	protected $_movie=null;
	public function setMovie($movie)
	{
		$this->_movie=$movie;
	}

	public function getMovie()
	{
		return $this->_movie;
	}
	
	protected $_width=null;
	public function setWidth($width)
	{
		$this->_width=$width;
	}

	public function getWidth()
	{
		return $this->_width;
	}
	
	protected $_height=null;
	public function setHeight($height)
	{
		$this->_height=$height;
	}

	public function getHeight()
	{
		return $this->_height;
	}
		
	public function setParam($name, $value)
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
				$this->_params[$name]=$value;
		}
	}
	
	public function getParam($name)
	{
		$name=strtolower($name);
		switch($name)
		{
			case 'width':
				return $this->getWidth();
			case 'height':
				return $this->getHeight();
			case 'src': case 'movie':
				return $this->getMovie();
			default:
				if(isset($this->_params[$name]))return $this->_params[$name];
				return null;
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
		if(isset($this->_params[$name])) $this->setParam($name, $value);
		else parent::setAttribute($name, $value);
	}	

	public function getAttribute($name)
	{
		$name=strtolower($name);
		if(isset($this->_params[$name])) return $this->getParam($name);
		return parent::getAttribute($name);
	}
	
	protected function preRender()
	{
		//TODO optimize for multiple rendering
		$render=parent::preRender();
		$this->resetSubLayouts();
		$this->_add($p=new Ea_Layout_Single('param'));
		$p->setAttribute('name', 'movie');
		$p->setAttribute('value', $this->getMovie());
		$embed=new Ea_Layout_Single('embed');
		$embed->setAttribute('src', $this->getMovie());
		$embed->setAttribute('type', 'application/x-shockwave-flash');
		
		if($this->getWidth()!==null)
		{
			$this->_setAttribute('width', $this->getWidth());
			$embed->setAttribute('width', $this->getWidth());
			$this->_add($p=new Ea_Layout_Single('param'));
			$p->setAttribute('name', 'width');
			$p->setAttribute('value', $this->getWidth());
		}
		if($this->getHeight()!==null)
		{
			$this->_setAttribute('height', $this->getHeight());
			$embed->setAttribute('height', $this->getHeight());
			$this->_add($p=new Ea_Layout_Single('param'));
			$p->setAttribute('name', 'height');
			$p->setAttribute('value', $this->getHeight());
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