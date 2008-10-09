<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Base
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.1
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Container.php';

/**
 * Link tag element layout class.
 * In HTML : the <a href="...">...</a> tag.
 * 
 */
class Ea_Layout_Link extends Ea_Layout_Container
{
	protected $_tag='a';
	
	/**
	 * Link layout constructor.
	 * 
	 * @param Ea_Route|string Url or route to link
	 * @param Ea_Layout_Abstract|mixed $content label of the link
	 * @param string $target target window
	 * @param array(string=>mixed) $config associative array of parameters
	 * 
	 * - 'callbacks' : an array of "on add layout" callbacks
	 * @see addCallback()
	 * 
	 */
	public function __construct($url, $content=null, $target=null, $config=null)
	{
		parent::__construct($config);
		if($content) $this->add($content);
		if($target) $this->setAttribute('target', $target);
		$this->setUrl($url);
	}
	
	/**
	 * Url to link.
	 * 
	 * @var Ea_Route|string
	 */
	protected $_url=null;
	
	/**
	 * Set URL.
	 * 
	 * @param Ea_Route|string $url
	 */
	public function setUrl($url)
	{
		$this->_url=$url;
	}

	/**
	 * Get URL.
	 * 
	 * @return Ea_Route|string
	 */
	public function getUrl()
	{
		return $this->_url;
	}

	/**
	 * Get URL string.
	 * 
	 * @return Ea_Route|string
	 */
	public function getUrlString()
	{
		if($this->_url instanceof Ea_Route) return $this->getPage()->getRouter()->url($this->_url);
		return $this->_url;
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
			case 'href':
				$this->setUrl($value);
				break;
			default:
				parent::setAttribute($name, $value);
		}
	} 

	/**
	 * Render the container and the sublayouts.
	 * 
	 */
	public function render()
	{
		/*
		 * Display the reserved attributes...
		 */
		parent::setAttribute('href', $this->getUrlString());
		parent::render();
	}
	
}
?>