<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.2.5.20081020
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Container.php';
require_once 'Ea/Layout/Input/Abstract.php';
require_once 'Ea/Layout/Input/Array.php';
require_once 'Ea/Layout/Input/Hidden.php';
require_once 'Ea/Layout/Input/Radio.php';
require_once 'Ea/Layout/Form/Exception.php';

/**
 * Form layout class.
 * In HTML : a form.
 * Form class handle the input layouts you put in it or in subcontainers.
 * <code>
 * <?php
 *  $form=new Ea_Layout_Form('form');
 *  $form->add(new Ea_Layout_Input_Text('login'));
 * 
 *  $form['login']='john';
 *  
 * 	echo "value: {$form['login']}";
 * ?>
 * </code>
 *  
 * Using the catchInput() method, you can determine if this form was submitted.
 * 
 * <code>
 * <?php
 *  $form=new Ea_Layout_Form('form');
 *  $form->add(new Ea_Layout_Input_Text('login'));
 *  
 *  if($form->catchInput())
 *  {
 *      // This form was submitted
 * 		// Do stuff with $form['login']
 *  }
 * ?>
 * </code>
 * 
 * @see Ea_Layout_Form::catchInput()
 */
class Ea_Layout_Form extends Ea_Layout_Input_Array
{
	protected $_tag='form';

	/**
	 * Form constructor.
	 * 
	 * @param string $id a unique id used to identify the form
	 * @param array() $config
	 * 
	 * - 'method' : 'get' or 'post'
	 * @todo get is not implemented
	 * 
	 * - 'action' : route or url
	 * 
	 * @uses 
	 */
	public function __construct($id, $config=null)
	{
		$this->setMethod('post');
		parent::__construct(null, $config);
		$this->setId($id);
		if(is_array($config))
		{
			if(array_key_exists('method', $config))
			{
				$this->setMethod($config['method']);
			}
			if(array_key_exists('action', $config))
			{
				$this->setAction($config['action']);
			}
		}
		/*
		 * We want to know when input layouts are added.
		 */
		$this->addCallback(array($this, 'onAddLayout'));
	}
	
	/**
	 * Callback used to trap input layout.
	 * @see Ea_Layout_Container::addCallback()
	 * 
	 * @param Ea_Layout_Abstract $layout
	 */
	public function onAddLayout(Ea_Layout_Abstract $layout)
	{
		if($layout instanceof Ea_Layout_Form)
		{
			throw new Ea_Layout_Form_Exception('Form cannot contain form');
		}
		else if($layout instanceof Ea_Layout_Input_Abstract)
		{
			$this->addInput($layout);
		}
	}
	
	/**
	 * Add input layout to handle.
	 * Input must have a valid id.
	 * @see Ea_Layout_Input_Abstract::$_id
	 * 
	 * @param Ea_Layout_Input_Abstract $input
	 */
	public function addInput(Ea_Layout_Input_Abstract $input)
	{
		$input->setForm($this);
		$this->setArrayInputAt($input->getId(), $input);
	}
	
	/**
	 * Put an input at the given id.
	 * ie. the id array('foo', 'bar') will reference $this['foo']['bar'].
	 * 
	 * @param array(string) $id
	 * @param Ea_Layout_Input_Abstract$input
	 */
	protected function setArrayInputAt(array $id, Ea_Layout_Input_Abstract $input)
	{
		$arr=$this;
		// add inputs in an array pattern
		// $form[id1][id2][...][idn]=input
		$nid=array();
		$isNid=false;
		$i=0;
		foreach($id as $pid)
		{
			
			//if last part of id
			if(($i+1)==count($input->getId()))
			{
				// if valid pid and existing item
				if($pid!==null&&$arr->offsetExists($pid))
				{
					//must be an input
					if(!($arr[$pid] instanceof Ea_Layout_Input_Abstract))
					{
						throw new Ea_Layout_Form_Exception('Not an input');
					}
					// special case four radio buttons
					if( ($arr[$pid] instanceof Ea_Layout_Input_Radio) && ($input instanceof Ea_Layout_Input_Radio) )
					{
						// last radio is selected ?
						// do it before setting parent !
						$selected=$input->isSelected();
						$input->setGroupParent($arr[$pid]);
						if($selected) $arr[$pid]->setValue($input->getRadioValue());
					}
					// if same id => same input just break
					break;
				}
				$npid=$arr->protectedOffsetSet($pid, $input);
				if($pid!==$npid)
				{
					$isNid=true;
				}
				array_push($nid, $npid);
				break;
			}
			else
			{
				// if valid pid and existing item
				if($pid!==null&&$arr->offsetExists($pid))
				{
					//must be an array
					if(!($arr[$pid] instanceof Ea_Layout_Form_Array))
					{
						throw new Ea_Layout_Form_Exception('Not an array');
					}
					$arr=$arr[$pid];
					array_push($nid, $pid);
				}
				else
				{
					$narr=new Ea_Layout_Input_Array;
					$npid=$arr->protectedOffsetSet($pid, $narr);
					if($pid!==$npid)
					{
						$isNid=true;
					}
					array_push($nid, $npid);
					$arr=$narr;
				}
			}
			$i++;
		}
		if($isNid)
		{
			$input->setId($nid);
		}
	}

	/**
	 * Action can be a route or an url.
	 *
	 * @var Ea_Route|string
	 */
	protected $_action=null;
	
	/**
	 * Set action.
	 * @see $_action
	 * 
	 * @param Ea_Route|string $route
	 */
	public function setAction($route)
	{
		$this->_action=$route;
	}

	/**
	 * Return action.
	 * @see $_action
	 *
	 * @return Ea_Route|string
	 */
	public function getAction()
	{
		return $this->_action;
	}
	
	/**
	 * Return the URL string of the action.
	 * 
	 * @return string
	 */
	public function getActionUrl()
	{
		if($this->getAction() instanceof Ea_Route) return $this->getPage()->getRouter()->url($this->getAction());
		return $this->getAction();
	}

	/**
	 * The method 'post' or 'get'
	 * 
	 * @var string
	 */
	protected $_method=null;

	/**
	 * Set the method.
	 * 
	 * @param string $method
	 */
	public function setMethod($method)
	{
		$method=strtolower($method);
		switch($method)
		{
			case 'get': case 'post':
				$this->_method=$method;
				break;
			default:
				throw new Ea_Layout_Form_Exception("$method: unknown method");
		}
		parent::setAttribute('method', $method);
	}

	/**
	 * Get the method.
	 * 
	 * @return string
	 */
	public function getMethod()
	{
		return $this->_method;
	}
	
	/**
	 * Set the form id.
	 * 
	 * @param string $id
	 */
	public function setId($id)
	{
		if(!$id)
		{
			throw new Ea_Layout_Form_Exception('Empty Id');
		}
		$this->_id=$id;
		//TODO simplify this
		parent::setAttribute('id', $id);
		parent::setAttribute('name', $id);
	}
	
	/**
	 *  Get the form id.
	 * 
	 * @return string
	 */
	public function getId()
	{
		return $this->_id;
	}
	
	/**
	 * Set layout attribute.
	 * 
	 * @param string $name
	 * @param string $value
	 * TODO : simplify
	 */
	public function setAttribute($name, $value)
	{
		$name=strtolower($name);
		switch($name)
		{
			case 'name': case 'action':
				throw new Ea_Layout_Form_Exception("$name: forbidden attribute for form");
				break;
			case 'method':
				$this->setMethod($value);
				break;
			case 'id':
				$this->setId($value);
				break;
			default:
				parent::setAttribute($name, $value);
		}
	} 

	/**
	 * Use(render) magic hidden input to determine if the form was submitted.
	 * 
	 * @var boolean
	 */
	protected $_useMagic=true;

	/**
	 * Set use magic.
	 * @see $_useMagic
	 * 
	 * @param boolean $use
	 */
	public function useMagic($use)
	{
		$this->_useMagic=$use;
	}	
	
	/**
	 * To know if magic() was called.
	 * 
	 * @var boolean
	 */
	protected $_magic=false;

	/**
	 * Add a magic hidden input.
	 * @see useMagic()
	 * 
	 */
	protected function magic()
	{
		if(!$this->_useMagic) return;
		if(!$this->_magic)
		{
			//magic input
			$this->add(new Ea_Layout_Input_Hidden($this->getMagicName('id'), $this->getId()));
			$this->_magic=true;
		}
	}

	/**
	 * Magic inputs name prefix.
	 * 
	 * @var string
	 */
	protected $_magicNamePrefix='eaf';

	/**
	 * Get the magic name for the given string.
	 * Prepend the magic prefix.
	 * 
	 * @param string $name
	 * @return string
	 */
	protected function getMagicName($name)
	{
		return $this->_magicNamePrefix.'_'.$name;
	}
	
	public function preRender()
	{
		parent::preRender();
		parent::setAttribute('action', $this->getActionUrl());
		$this->magic();
	}

	/**
	 * Get the $_POST value for the given id.
	 * ie. the id array('foo','bar') reference $_POST['foo']['bar']
	 * if array contains null try to increment a counter.
	 * 
	 * @param array $id
	 * @return string
	 */
	public function getFromPost(array $id)
	{
		$arr=&$_POST;
		$i=0;
		// add inputs in an array pattern
		// $form[id1][id2][...][idn]=input
		foreach($id as $pid)
		{
			// can't be null part of id : if part of is null => autoincrement
			if($pid===null) return null;
			if(!isset($arr[$pid])) return null;
			if(($i+1)==count($id)) return $arr[$pid];
			$arr=&$arr[$pid];
			$i++;
		}
	}
	
	protected $_post=null;

	/**
	 * Test if request is POST.
	 * 
	 * @return boolean
	 */
	public function isPost()
	{
		return strtoupper($_SERVER['REQUEST_METHOD'])=='POST';
	}
	
	/**
	 * This method allow you to test if some data where submitted in this form.
	 * @see Ea_Layout_Form
	 * @todo method='get' form
	 * 
	 */
	public function catchInput()
	{
		switch($this->getMethod())
		{
			case 'post':
				if(!$this->isPost()) return false;
				$magicId=$this->getMagicName('id');
				if(!array_key_exists($magicId, $_POST)) return false;
				if($_POST[$magicId]!=$this->getId()) return false;
				if(count($this->_items)==0)
				{
					// special not initialized mode
					$this->_post=$_POST;
					unset($this->_post[$magicId]);
					return true;
				}
				//testing magic input
				$this->recusiveWalk(array($this, 'parseInput'));
				return true;
				break;
			default:
				//TODO : implements get form
				throw new Ea_Layout_Form_Exception('Not yet done');
		}
	}
	
	/**
	 * Retrieve value from submitted data for given input.
	 * 
	 * @param Ea_Layout_Input_Abstract $input
	 */
	public function parseInput(Ea_Layout_Input_Abstract $input)
	{
		switch($this->getMethod())
		{
			case 'post':
				$input->setValue($this->getFromPost($input->getId()));
				break;
			default:
				throw new Ea_Layout_Form_Exception('Not yet done');
		}
	}
	
	protected function usePostData()
	{
		return $this->_post!==null;
	}
	
	public function offsetExists($offset)
	{
		if($this->usePostData())
		{
			return array_key_exists($offset, $this->_post);
		}
		return parent::offsetExists($offset);
	}
	
 	public function offsetGet($offset)
 	{
 		if($this->usePostData())
 		{
 			return $this->_post[$offset];
 		}
 		return parent::offsetGet($offset);
 	}
 	 	
 	public function offsetSet($offset, $value)
 	{
 		if($this->usePostData())
 		{
 			throw new Ea_Layout_Form_Exception('Data update not allowed here !');
 		}
 		parent::offsetSet($offset, $value);
 	}
 	
 	public function offsetUnset($offset)
 	{
 		if($this->usePostData())
 		{
 			throw new Ea_Layout_Form_Exception('Data update not allowed here !');
 		}
 		parent::offsetUnset($offset);
  	}
 	
 	public function current()
 	{
 		if($this->usePostData())
 		{
 			return current($this->_post);
 		}
 		return parent::current();
  	}
 	
 	public function key()
 	{
 		if($this->usePostData())
 		{
 			return key($this->_post);
 		}
 		return key($this->_items);
 	}
 	
 	public function next()
 	{
 		if($this->usePostData())
 		{
 			return next($this->_post);
 		}
 		return next($this->_items);
 	}
 	
 	public function rewind()
 	{
 		if($this->usePostData())
 		{
 			return reset($this->_post);
 		}
 		return reset($this->_items);
 	}
 	
 	public function valid()
 	{
 		if($this->usePostData())
 		{
 			return current($this->_post)!==false;
 		}
 		return $this->current()!==false;
 	}
 	
 	public function count()
 	{
 		if($this->usePostData())
 		{
 			return count($this->_post);
 		}
 		return count($this->_items);
 	}
 }

?>