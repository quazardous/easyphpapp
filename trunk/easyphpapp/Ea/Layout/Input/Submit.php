<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.5.2-20110629
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Input/Abstract.php';

/**
 * Submit input layout class.
 */
class Ea_Layout_Input_Submit extends Ea_Layout_Input_Abstract
{
	protected $_type='submit';
	
	/**
	 * Temporary submit callback, copied in Ea_Layout_Form.
	 * 
	 * @var array
	 */
	protected $_submitCallbacks=array();
	
	public function getSubmitCallbacks()
	{
		return $this->_submitCallbacks;	
	}
	
	public function resetSubmitCallbacks()
	{
		return $this->_submitCallbacks=array();	
	}
	
	/**
	 * Add submit callback to the button.
	 * 
	 * @param callback $callback
	 * @param boolean $module : by default if $callback is a string $callback is taken as one of the module method, else it's a classic php callback
	 * 
	 * @see Ea_Layout_Form::addSubmitCallback()
	 */
	public function addSubmitCallback($callback, $module=true)
	{
		if($this->getForm())
		{
			// if already attached to a form add callback to the form.
			$this->getForm()->addSubmitCallback($this->getId(), $callback, $module);
		}
		else
		{
			// put it in a temporary array
			$this->_submitCallbacks[]=array('callback'=>$callback, 'module'=>$module);
		}
	}
	
	/**
	 * Constructor.
	 * 
	 * @param string $id
	 * @param string $value
	 * @param callback $callback
	 *  if it's a string it is taken as one of the module method, if it's an array it's taken as a php callback.
	 *  NB : here you can use an array with a null first element to add a basic static function as callback :
	 *   ie : array(null, 'foo') will call the non class member function foo().
	 * @param array $config
	 */
	public function __construct($id=null, $value=null, $callback=null, $config=null)
	{
		parent::__construct($id, $value, $config);
		if($callback)
		{
			$module=true;
			if(is_array($callback)&&$callback[0]===null)
			{
				$callback=$callback[1];
				$module=false;
			}
			$this->addSubmitCallback($callback, $module);
			$this->protectAttribute('confirm');
		}
	}
	
	protected $_confirm = null;
	/**
	 * Add a confirm popup.
	 * @param string $confirm
	 */
	public function setConfirm($confirm) {
	  $this->_confirm = $confirm;
	}
	public function getConfirm() {
	  return $this->_confirm;
	}
	
	protected function preRender()
	{
		$render=parent::preRender();
		$this->addAttribute('onclick', "return confirm('".addcslashes($this->_confirm, "'")."')");
		return $render;
	}	
}
