<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.3.8-20091013
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Container.php';
require_once 'Ea/Layout/Input/Abstract.php';
require_once 'Ea/Layout/Input/Array.php';
require_once 'Ea/Layout/Input/Hidden.php';
require_once 'Ea/Layout/Input/Radio.php';
require_once 'Ea/Layout/Input/File.php';
require_once 'Ea/Layout/Input/Submit.php';
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
	public function __construct($id, $method='post', $action=null, $config=null)
	{
		$this->setMethod($method);
		$this->setAction($action);
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
		$id=$input->getId();
		if($id)
		{
			$this->setArrayInputAt($id, $input);
			// cannot remeber if no id
			if($this->_rememberAllInputsValues)
			{
				$input->rememberValue();
			}
		}
		if($input instanceof Ea_Layout_Input_File)
		{
			$this->uploadFile();
		}
		if($input instanceof Ea_Layout_Input_Submit)
		{
			// get the submit callbacks added before input was attached to form
			foreach($input->getSubmitCallbacks() as $callback)
			{
				$this->addSubmitCallback($callback['callback'], $input->getId(), $callback['module']);
			}
			$input->resetSubmitCallbacks();
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
	
	protected $_submitCallbacks=array();
	
	/**
	 * Add submit callback for given button.
	 * The callbacks are triggered when the form is Submited (POST).
	 * @see triggerSubmitCallbacks()
	 * 
	 * @param callback $callback
	 *  the callback will recieve 2 arguments : the form object and the name of the submit button.
	 * @param string|array $id the id of the button, if null general submit callback
	 * @param boolean $module
	 *  by default $callback is one of the module method, else it's a classic php callback
	 */
	public function addSubmitCallback($callback, $id=null, $module=true)
	{
		if($this->getMethod()!='post') throw new Ea_Layout_Form_Exception('Only for post form');
		if(!$id)
		{
			$id = '*'; //general callback
		}
		else
		{
			$id = Ea_Layout_Input_Abstract::get_name_from_id($id);
		}
		if(!array_key_exists($id, $this->_submitCallbacks)) $this->_submitCallbacks[$id]=array();
		$this->_submitCallbacks[$id][]=array('callback'=>$callback, 'module'=>$module);
	}
	
	/**
	 * Flag for trigger submit callback.
	 * 
	 * @var boolean
	 */
	protected $_submitCallbacksTriggered=false;
	
	/**
	 * Trigger the submit callback for the given button.
	 * If you don't specify the button, it try determine the submitting button.
	 * 
	 * @param id|array $id
	 * @return int|true|false number of triggered callbacks, true il already triggered or false if can't catchInput()
	 * @see catchInput()
	 */
	public function triggerSubmitCallbacks($triggeringId=null)
	{
		if($this->getMethod()!='post') throw new Ea_Layout_Form_Exception('Only for post form');
		if($this->_submitCallbacksTriggered) return true;
		$this->_submitCallbacksTriggered=true;
		if(!$this->catchInput()) return false;
		if($this->isStore()&&(!$triggeringId))
		{
			$triggeringId=$this->_submittingInputId;
		}
		$triggeringId = Ea_Layout_Input_Abstract::get_name_from_id($triggeringId);
		$n=0;
		foreach($this->_submitCallbacks as $id => $callbacks)
		{
			$do=false;
			if($id=='*') //general callback
			{
				$do=true;
				$id=null;
			}
			else if($this->isStore())  //useStore way
			{
				if($triggeringId&&$triggeringId==$id) $do=true;
			}
			else //simple POST way
			{
				if($triggeringId)
				{
					if($triggeringId==$id) $do=true; 
				}
				else if($this[$id]) $do=true; 
			}
			if(!$do) continue;
			foreach($callbacks as $callback)
			{
				if($callback['module'])
				{
					$callback['callback']=array($this->getPage()->getModule(), $callback['callback']);
				}
				//echo "call_user_func({$callback['callback'][1]}, \$this, $id)";
				call_user_func($callback['callback'], $this, $id);
				$n++;
			}
		}
		return $n;
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
				throw new Ea_Layout_Form_Exception('Cannot use route outside EasyPhpApp application engine !');
			}
			return $page->getApp()->url($this->getAction());
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
	 * @return string|array
	 */
	public function getId($string=true)
	{
		if($string) Ea_Layout_Input_Abstract::get_name_from_id($this->_id);
		return $this->_id;
	}
	
	// FIXME add getName();
	
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
	 * Are we using magic..
	 * 
	 * @return boolean
	 */
	public function isMagic()
	{
		return $this->_useMagic;
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
		if(!$this->isMagic()) return;
		
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
					$action=$this->getPage()->getApp()->getRoute();
				}
				if($action instanceof Ea_Route)
				{
					// if a route was set, the form tries to keep all params.
					$action=$this->getPage()->getApp()->url($action);
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
		if(!$this->isStoredData()) return null;
		$input=self::array_get_from_id($this->getStoredData()->items, $id);
		//TODO think about it
		if($input instanceof Ea_Layout_Input_Abstract) return $input->getValue();
		return null;
	}
	
	/**
	 * Test if there are some session data for the form.
	 * 
	 * @return boolean
	 */
	protected function isStoredData()
	{
		return isset($this->getSession()->forms[$this->getId()]);
	}

	/**
	 * Get the session stored data.
	 * 
	 * @return mixed
	 */
	protected function &getStoredData()
	{
		return $this->getSession()->forms[$this->getId()];
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
				if($this->isStore()) $this->restore();
				if(count($this->_items)==0)
				{
					// special not initialized mode
					$this->_post=$_POST;
					unset($this->_post[$magicId]);
					return true;
				}
				$this->_submittingInputId=null;
				// dealing with stored structure
				$this->recursiveWalk(array($this, 'parseInput'));
				// store new values
				if($this->isStore()) $this->store();
				return true;
				break;
			default:
				throw new Ea_Layout_Form_Exception('Use Ea_App::getParam()');
		}
	}
	
	/**
	 * Id of input clicked for submission.
	 * 
	 * @var array|string
	 */
	protected $_submittingInputId;
	
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
				if($input instanceof Ea_Layout_Input_Submit)
				{
					if($input->getValue())
					{
						$this->_submittingInputId=$input->getId();
					}
				}
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
	public function setNamespace($namespace)
	{
		$this->_namespace=$namespace;
	}

	/**
	 * Get session namespace.
	 * 
	 * @return string
	 */
	public function getNamespace()
	{
		return $this->_namespace;
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
			$page=$this->getPage();
			if($page instanceof Ea_Page)
			{
				$this->_session=new Zend_Session_Namespace($this->getPage()->getApp()->getNamespace().'.forms');
			}
			else
			{
				$this->_session=new Zend_Session_Namespace($this->_namespace);
			}
		}
		return $this->_session;
	}
	
	protected $_useStore=false;
	
	/**
	 * Tells if form must store its structure at postRender().
	 * 
	 * @param boolean $use
	 */
	protected function useStore($use=true)
	{
		Zend_Session::start();
		$this->_useStore=$use;
	}
	
	/**
	 * Test if form must store its structure at postRender().
	 * 
	 * @return boolean
	 */
	public function isStore()
	{
		return $this->_useStore;
	}
	
	public function assertStore()
	{
		if(!$this->isStore())
		{
			throw new Ea_Layout_Form_Exception('No storage define : use Ea_Module_Abstract::registerForm()');
		}
	}
	
 	/**
 	 * Store the structure using session.
 	 * This can be usefull to remember inputs values, etc...
 	 * 
 	 */
 	protected function store()
 	{
 		if(!isset($this->getSession()->forms)) $this->getSession()->forms=array();
 		$this->getSession()->forms[$this->getId()]=(object)array('items'=>$this->_items, 'submitCallbacks'=>$this->_submitCallbacks);
 	}
 	
 	/**
 	 * Try to restore structure.
 	 * 
 	 * @return boolean
 	 */
 	protected function restore()
 	{
 		if(!$this->isStoredData()) return false;
		$this->_submitCallbacks=$this->getStoredData()->submitCallbacks;
		$this->_items=$this->getStoredData()->items;
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
    	if($remember) $this->assertStore();
    	$this->_rememberAllInputsValues=$remember;
    }

	/**
	 * Link the form to the module (and the application).
	 * Mandatory to use session mechanisms in the app namespace.
	 * 
	 * @param Ea_Module_Abstract $module
	 * @param boolean $useStore
	 */
	public function storage(Ea_Module_Abstract $module, $useStore=true)
	{
		$this->setPage($module->getPage());
		if($useStore) $this->useStore();
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
    
	
	/**
	 * Get the name of the uploaded file.
	 * 
	 * @param $inputId
	 * @return string
	 */
	public function getUploadedFileName($inputId)
	{
		if(!isset($_FILES)) return null;
		$inputId=Ea_Layout_Input_Abstract::get_id_from_name($inputId);
		return self::array_get_from_id($_FILES, Ea_Layout_Input_File::get_reordered_field_id($inputId,'name'));
	}

	/**
	 * Get the mime type of the uploaded file.
	 * 
	 * @param $inputId
	 * @return string
	 */
	public function getUploadedFileType($inputId)
	{
		if(!isset($_FILES)) return null;
		$inputId=Ea_Layout_Input_Abstract::get_id_from_name($inputId);
		return Ea_Layout_Form::array_get_from_id($_FILES, Ea_Layout_Input_File::get_reordered_field_id($inputId,'type'));
	}

	/**
	 * Get the tmp namee of the uploaded file.
	 * 
	 * @param $inputId
	 * @return string
	 */
	public function getUploadedFileTmpName($inputId)
	{
		if(!isset($_FILES)) return null;
		$inputId=Ea_Layout_Input_Abstract::get_id_from_name($inputId);
		return Ea_Layout_Form::array_get_from_id($_FILES, Ea_Layout_Input_File::get_reordered_field_id($inputId,'tmp_name'));
	}

	/**
	 * Get the size of the uploaded file.
	 * 
	 * @param $inputId
	 * @return numeric
	 */
	public function getUploadedFileSize($inputId)
	{
		if(!isset($_FILES)) return null;
		$inputId=Ea_Layout_Input_Abstract::get_id_from_name($inputId);
		return Ea_Layout_Form::array_get_from_id($_FILES, Ea_Layout_Input_File::get_reordered_field_id($inputId,'size'));
	}

	/**
	 * Get the error code for the uploaded file.
	 * 
	 * @param $inputId
	 * @return numeric
	 */
	public function getUploadedFileError($inputId)
	{
		if(!isset($_FILES)) return null;
		$inputId=Ea_Layout_Input_Abstract::get_id_from_name($inputId);
		return Ea_Layout_Form::array_get_from_id($_FILES, Ea_Layout_Input_File::get_reordered_field_id($inputId,'error'));
	}
	
	/**
	 * File was upload ?.
	 * 
	 * @param $inputId
	 * @return boolean
	 */
	public function isUploadedFile($inputId)
	{
		if(!isset($_FILES)) return false;
		$inputId=Ea_Layout_Input_Abstract::get_id_from_name($inputId);
		return is_uploaded_file($this->getUploadedFileTmpName($inputId));
	}

	/**
	 * Move uploaded file.
	 * 
	 * @param $inputId
	 * @param $destination
	 * @return boolean
	 */
	public function moveUploadedFile($inputId, $destination)
	{
		if(!isset($_FILES)) return false;
		$inputId=Ea_Layout_Input_Abstract::get_id_from_name($inputId);
		return move_uploaded_file($this->getUploadedFileTmpName($inputId), $destination);
	}

	/**
	 * Get file contents.
	 * @see file_get_contents
	 *  
	 * @param $inputId
	 * @param $maxlen
	 * @param $offset
	 * @param $flags
	 * @return string
	 */
	public function getUploadedFileContents($inputId, $maxlen=null, $offset=null, $flags=null)
	{
		if(!isset($_FILES)) return null;
		$inputId=Ea_Layout_Input_Abstract::get_id_from_name($inputId);
		return file_get_contents($this->getUploadedFileTmpName($inputId), $flags, null, $offset, $maxlen);
	}
    
    public function postRender()
    {
    	parent::postRender();
    	if($this->isStore())
    	{
    		//TODO : not optimum because we must start the session manualy
    		$this->store();
    	}
    }
    
}