<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Base
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id$
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
		parent::__construct(null, $config);
		if($content) $this->add($content);
		if($target) $this->setAttribute('target', $target);
		$this->setUrl($url);
		$this->protectAttribute('href', 'setUrl', 'getUrlString');
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
		if($this->_url instanceof Ea_Route)
		{
			$page=$this->getPage();
			if(!$page instanceof Ea_Page)
			{
				require_once 'Ea/Layout/Link/Exception.php';
				throw new Ea_Layout_Link_Exception('Cannot use route outside EasyPhpApp application engine !');
			}
			return $page->getApp()->url($this->_url);
		}
		return $this->_url;
	}

	protected function preRender()
	{
		$render=parent::preRender();
		$this->_setAttribute('href', $this->getUrlString());
		return $render;
	}
	
}