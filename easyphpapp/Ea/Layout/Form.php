<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.3-20081219
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Container.php';
require_once 'Ea/Layout/Input/Abstract.php';
require_once 'Ea/Layout/Input/Array.php';
require_once 'Ea/Layout/Input/Hidden.php';
require_once 'Ea/Layout/Input/Radio.php';
require_once 'Ea/Layout/Input/File.php';
require_once 'Ea/Layout/Form/Exception.php';
require_once 'Zend/Session/Namespace.php';

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
			if(array_key_exists('remember_all_inputs_values', $config))
			{
				$this->rememberAllInputsValues($config['remember_all_inputs_values']);
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
		if($this->_rememberAllInputsValues)
		{
			$input->rememberValue();
		}
		if($input instanceof Ea_Layout_Input_File)
		{
			$this->uploadFile();
		}
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
		$arr=&$this->_items;
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
				if($pid!==null&&array_key_exists($pid, $arr))
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
				$npid=self::array_set_or_push($arr, $pid, $input);
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
				if($pid!==null&&array_key_exists($pid, $arr))
				{
					//must be an array
					if(!($arr[$pid] instanceof Ea_Layout_Input_Array))
					{
						throw new Ea_Layout_Form_Exception('Not an array');
					}
					$arr=&$arr[$pid]->_items;
					array_push($nid, $pid);
				}
				else
				{
					$narr=new Ea_Layout_Input_Array;
					$npid=self::array_set_or_push($arr, $pid, $narr);
					if($pid!==$npid)
					{
						$isNid=true;
					}
					array_push($nid, $npid);
					$arr=&$narr->_items;
				}
			}
			$i++;
		}
		if($isNid)
		{
			$input->setId($nid);
		}
	}

 	static protected function array_set_or_push(&$array, $offset, $value)
 	{
 		if($offset===null)
 		{
 			$n=count($array);
 			array_push($array, $value);
 			end($array);
 			return key($array);
 		}
 		$array[$offset]=$value;
 		return $offset;		
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
	 * @see setMethod() if GET form and action is a route, params will be generate as hidden inputs.
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
		if($this->getAction() instanceof Ea_Route)
		{
			$page=$this->getPage();
			if(!$page instanceof Ea_Page)
			{
				throw new Ea_Layout_Form_Exception('Cannot use route outside EasyPhpApp router engine !');
			}
			return $page->getRouter()->url($this->getAction());
		}
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
	 * In EasyPhpApp POST is form data manipulation and GET for navigation (list criteria, etc).
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
	 * The form id (and name).
	 * 
	 * @var string
	 */
	protected $_id=null;
		
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
			case 'enctype':
				$this->uploadFile(strtolower($value)=='multipart/form-data');
				break;
			default:
				parent::setAttribute($name, $value);
		}
	} 

	public function getAttribute($name)
	{
		$name=strtolower($name);
		switch($name)
		{
			case 'name': case 'id':	return $this->getId();	
			case 'action': return $this->getActionUrl();
			case 'method': return $this->getMethod();
			case 'enctype': return $this->canUploadFile()?'multipart/form-data':null;
			default: return parent::getAttribute($name);
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
			if($this->getMethod()=='post')
			{
				//magic input for post
				$this->add(new Ea_Layout_Input_Hidden($this->getMagicName('id'), $this->getId()));
			}
			else
			{
				//magic input for get => module and route.
				$action=$this->_action;
				if(($this->getPage() instanceof Ea_Page)&&(!$action))
				{
					// default route
					$action=$this->getPage()->getRouter()->getRoute();
				}
				if($action instanceof Ea_Route)
				{
					// if a route was set, the form tries to keep all params.
					$action=$this->getPage()->getRouter()->url($action);
					$infos=parse_url($action);
					
					if(isset($infos['query']))
					{
						//add input for each params
						foreach(explode('&',urldecode($infos['query'])) as $string)
						{
							$tmp=explode('=',$string);
							if(!self::array_get_from_id($this, $tmp[0]))
							{
								$this->add(new Ea_Layout_Input_Hidden($tmp[0], $tmp[1]), false);
							}
						}
					}
				}
			}
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
		$this->_setAttribute('action', $this->getActionUrl());
		$this->_setAttribute('id', $this->getId());
		$this->_setAttribute('name', $this->getId());
		$this->_setAttribute('method', $this->getMethod());
		if($this->canUploadFile()) $this->_setAttribute('enctype', 'multipart/form-data');
		$this->magic();
	}

	/**
	 * Get an array element from an array of keys.
	 * ie. the id array('foo','bar') wil return $array['foo']['bar']
	 * 
	 * @param array $array
	 * @param array|string $id
	 * @return mixed
	 */
	static public function array_get_from_id(&$array, $id)
	{
		$id=Ea_Layout_Input_Abstract::get_id_from_name($id);
		$arr=&$array;
		$i=0;
		foreach($id as $pid)
		{
			// can't be null part of id
			if($pid===null) return null;
			if(!isset($arr[$pid])) return null;
			if(($i+1)==count($id)) return $arr[$pid];
			$arr=&$arr[$pid];
			$i++;
		}
	}

	/**
	 * Reset an array element from an array of keys.
	 * ie. the id array('foo','bar') wil set $array['foo']['bar']
	 * 
	 * @param array $array
	 * @param array|string $id
	 * @param string $value
	 * @return mixed
	 */
	static public function array_reset_from_id(&$array, $id, $value)
	{
		$id=Ea_Layout_Input_Abstract::get_id_from_name($id);
		$arr=&$array;
		$i=0;
		foreach($id as $pid)
		{
			// can't be null part of id
			if($pid===null) throw new Ea_Layout_Form_Exception('incorrect input id');
			if(!isset($arr[$pid])) throw new Ea_Layout_Form_Exception('incorrect input id');
			if(($i+1)==count($id))
			{
				$arr[$pid]=$value;
				return;
			}
			$arr=&$arr[$pid];
			$i++;
		}
	}
	
	/**
	 * Test an array element from an array of keys.
	 * ie. the id array('foo','bar') wil return $array['foo']['bar']
	 * 
	 * @param array $array
	 * @param array|string $id
	 * @return mixed
	 */
	static public function array_exist_from_id(&$array, $id)
	{
		$id=Ea_Layout_Input_Abstract::get_id_from_name($id);
		$arr=&$array;
		$i=0;
		foreach($id as $pid)
		{
			// can't be null part of id
			if($pid===null) return false;
			if(!isset($arr[$pid])) return false;
			if(($i+1)==count($id)) return true;
			$arr=&$arr[$pid];
			$i++;
		}
	}
	
	/**
	 * Get the $_POST value for the given id.
	 * ie. the id array('foo','bar') reference $_POST['foo']['bar']
	 * 
	 * @param array $id
	 * @return string
	 */
	public function getValueFromPost($id)
	{
		return self::array_get_from_id($_POST, $id);
	}
	
	/**
	 * Get the stored value for the given id.
	 * 
	 * @param array $id
	 * @return string
	 */
	public function getValueFromSession($id)
	{
		if(!isset($this->getSession()->forms[$this->getId()])) return null;
		$input=self::array_get_from_id($this->getSession()->forms[$this->getId()], $id);
		//TODO think about it
		if($input instanceof Ea_Layout_Input_Abstract) return $input->getValue();
		return null;
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
				//testing magic input
				if(!array_key_exists($magicId, $_POST)) return false;
				if($_POST[$magicId]!=$this->getId()) return false;
				$stored=$this->tryRestore();
				if(count($this->_items)==0)
				{
					// special not initialized mode
					$this->_post=$_POST;
					unset($this->_post[$magicId]);
					return true;
				}
				$this->recursiveWalk(array($this, 'parseInput'));
				// store new values
				if($stored) $this->store();
				return true;
				break;
			default:
				throw new Ea_Layout_Form_Exception('Use Ea_Router::getParam()');
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
				$input->setValue($this->getValueFromPost($input->getId()));
				break;
			default:
				throw new Ea_Layout_Form_Exception('Cannot do that !');
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
			return self::array_exist_from_id($this->_post, $offset);
			//return array_key_exists($offset, $this->_post);
		}
		return self::array_exist_from_id($this->_items, $offset);
		//return parent::offsetExists($offset);
	}
	
 	public function offsetGet($offset)
 	{
 		if($this->usePostData())
 		{
 			return self::array_get_from_id($this->_post, $offset);
 			//return $this->_post[$offset];
 		}
 		return self::array_get_from_id($this->_items, $offset);
 		//return parent::offsetGet($offset);
 	}
 	 	
 	public function offsetSet($offset, $value)
 	{
 		if($this->usePostData())
 		{
 			throw new Ea_Layout_Form_Exception('Data update not allowed here !');
 		}
 		$input=self::array_get_from_id($this->_items, $offset);
 		if(!($input instanceof Ea_Layout_Input_Abstract))
 		{
 			throw new Ea_Layout_Form_Exception('Data update not allowed here !');
 		}
 		$input->setValue($value);
 		//parent::offsetSet($offset, $value);
 	}
 	
 	public function offsetUnset($offset)
 	{
 		if($this->usePostData())
 		{
 			throw new Ea_Layout_Form_Exception('Data update not allowed here !');
 		}
 		$this->offsetSet($offset, null);
 		//self::array_reset_from_id($this->_items, $offset, null);
 		//parent::offsetUnset($offset);
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

 	protected $_sessionNamespace=__CLASS__;
	/**
	 * Set session namespace.
	 * @uses $_sessionNamespace
	 * 
	 * @param string $name
	 */
	public function setSessionNamespace($namespace)
	{
		$this->_sessionNamespace=$namespace;
	}

	/**
	 * Get session namespace.
	 * 
	 * @return string
	 */
	public function getSessionNamespace()
	{
		return $this->_sessionNamespace;
	}
  	
	/**
	 * Session used to store structure.
	 * 
	 * @var Zend_Session_Namespace
	 */
	protected $_session=null;
	
	/**
	 * Get session.
	 * 
	 * @return Zend_Session_Namespace
	 */
	protected function getSession()
	{
		if(!$this->_session)
		{
			$this->_session=new Zend_Session_Namespace($this->_sessionNamespace);
		}
		return $this->_session;
	}
	
 	/**
 	 * Store the structure using session.
 	 * This can be usefull to remember inputs values, etc...
 	 * 
 	 */
 	public function store()
 	{
 		if(!isset($this->getSession()->forms)) $this->getSession()->forms=array();
 		$this->getSession()->forms[$this->getId()]=$this->_items;
 	}
 	
 	/**
 	 * Try to restore structure.
 	 * 
 	 * @return boolean
 	 */
 	public function tryRestore()
 	{
 		if(!isset($this->getSession()->forms[$this->getId()])) return false;
		$this->_items=$this->getSession()->forms[$this->getId()];
		$this->recursiveWalk(array($this, 'setInputForm'));
		return true;
 	}
 	
 	/**
 	 * Delete any stored structure.
 	 * 
 	 */
 	public function unstore()
 	{
 		unset($this->getSession()->forms[$this->getId()]);
 	}

	public function setInputForm(Ea_Layout_Input_Abstract $input)
	{
		$input->setForm($this);
	}

	/**
	 * Remember all inputs values.
	 * When the input is added to the form, automatically call $input->rememebrValue();
	 * @see Ea_Layout_Input_Abstract::rememberValue()
	 * 
	 * @var boolean
	 */
	protected $_rememberAllInputsValues=false;
	
    /**
     * Set remember all inputs values.
     * 
     * @param boolean $remember
     */
    public function rememberAllInputsValues($remember=true)
    {
    	$this->_rememberAllInputsValues=$remember;
    }
    
    /**
     * File upload support.
     * 
     * @var boolean
     */
    protected $_uploadFile=false;
    
    /**
     * Enable/disable file upload support.
     * 
     * @param boolean $upload
     */
    public function uploadFile($upload=true)
    {
    	$this->_uploadFile=$upload;
    }
    
    /**
     * Get file upload support.
     * 
     * @return boolean
     */
    public function canUploadFile()
    {
    	return $this->_uploadFile;
    }
    
	
    
}