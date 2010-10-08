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

require_once 'Ea/Layout/Abstract.php';

/**
 * Basic text layout class.
 * This class let you put free text, escaped or not.
 * 
 */
class Ea_Layout_Text extends Ea_Layout_Abstract
{
	/**
	 * Link layout constructor.
	 * 
	 * @param array(string=>mixed)|string|numeric $config associative array of parameters or text
	 * 
	 * - 'text' : text to add
	 * 
	 * - 'escape' : is the text escaped
	 * 
	 */
	public function __construct($text=null, $escape=null, $config=null)
	{
		parent::__construct($config);
		if($text!==null)$this->setText($text);
		if($escape!==null)$this->setEscape($escape);
		if(is_array($config))
		{
			if(array_key_exists('text', $config))
			{
				$this->setText($config['text']);
			}
			if(array_key_exists('escape', $config))
			{
				$this->setEscape($config['escape']);
			}
		}
		else if(is_string($config)||is_numeric($config))
		{
			$this->setText($config);
		}
	}
	
	/**
	 * The text.
	 * 
	 * @var string
	 */
	protected $_text=null;

	/**
	 * Set the text.
	 * 
	 * @param string $text
	 */
	public function setText($text)
	{
		$this->_text=$text;
	}
	
	/**
	 * Is the text escaped.
	 * 
	 * @var boolean
	 */
	protected $_escape=true;

	/**
	 * Set if must escape.
	 * 
	 * @param boolean $escape
	 */
	public function setEscape($escape)
	{
		$this->_escape=$escape;
	}
	
	/**
	 * Render the text.
	 * 
	 */
	protected function render()
	{
		if($this->_escape) echo $this->escape($this->_text);
		else echo $this->_text;
	}
}
