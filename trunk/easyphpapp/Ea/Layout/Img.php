<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Base
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.5.2-20110629
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Single.php';

/**
 * Img element layout class.
 * 
 */
class Ea_Layout_Br extends Ea_Layout_Single
{
	protected $_tag='img';

	protected $_src=null;
	public function setSrc($src)
	{
		$this->_src=$src;
	}
	public function getSrc()
	{
		return $this->_src;
	}

	protected $_alt=null;
	public function setAlt($alt)
	{
		$this->_alt=$alt;
	}
	public function getAlt()
	{
		return $this->_alt;
	}	

	protected $_title=null;
	public function setTitle($title)
	{
		$this->_title=$title;
	}
	public function getTitle()
	{
		return $this->_title;
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
	
	/**
	 * Single tag layout constructor.
	 * 
	 * @see addCallback()
	 * 
	 */
	public function __construct($src, $width = null, $height = null, $alt = null, $title = null, $config=null)
	{
	  $this->setSrc($src);
	  if ($width!==null) $this->setWidth($width);
	  if ($height!==null) $this->setHeight($height);
	  if ($alt!==null) $this->setAlt($alt);
	  if ($title!==null) $this->setTitle($title);
		parent::__construct(null, $config);
		$this->protectAttribute('img');
		$this->protectAttribute('alt');
		$this->protectAttribute('title');
		$this->protectAttribute('width');
		$this->protectAttribute('height');
	}
}
