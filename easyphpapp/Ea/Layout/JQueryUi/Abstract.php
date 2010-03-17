<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  jQueryUi
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.4-20100315
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Container.php';

/**
 * jQuery UI Tabs element layout class.
 * 
 */
class Ea_Layout_JQueryUi_Abstract extends Ea_Layout_Container
{
	/**
	 * jQueryUi abstract constructor.
	 * 
	 * @param string $id
	 * @param string $tag
	 * @param array $config associative array of parameters
	 * 
	 */
	public function __construct($id=null, $tag='div', $config=null)
	{
		if($id) $this->setId($id);
		parent::__construct($tag, $config);
		$this->protectedAttribute('id');
	}
	
	static protected $_autoId=0;
	
	/**
	 * Get the next auto id.
	 * 
	 * @return string
	 */
	protected function nextAutoId($baseId)
	{
		$res=$this->currAutoId($baseId);
		self::$_autoId++;
		return $res;
	}
	
	/**
	 * Get the current auto id.
	 * 
	 * @return string
	 */
	protected function currAutoId($baseId)
	{
		return $baseId.'-'.self::$_autoId;
	}	
	
	protected $_id=null;
	public function setId($id)
	{
		$this->_id=$id;
	}
	public function getId()
	{
		if(!$this->_id)
		{
			$this->_id=$this->nextAutoId('jq-container');
		}
		return $this->_id;
	}
	
}
