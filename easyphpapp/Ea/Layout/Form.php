<?php
require_once 'Ea/Layout/Container.php';
require_once 'Ea/Layout/Input/Abstract.php';
require_once 'Ea/Layout/Input/Array.php';
require_once 'Ea/Layout/Input/Hidden.php';
require_once 'Ea/Layout/Form/Exception.php';

class Ea_Layout_Form extends Ea_Layout_Input_Array
{
	protected $_tag='form';

	public function __construct($id, $config=null)
	{
		$this->setMethod('post');
		parent::__construct($config);
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
		$this->addCallback(array($this, 'onAddLayout'));
	}
	
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
	
	public function addInput(Ea_Layout_Input_Abstract $input)
	{
		$input->setForm($this);
		$this->setArrayInputAt($input->getId(), $input);
	}
	
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
	 * Action
	 *
	 * @var Ea_Route
	 */
	protected $_action=null;
	
	public function setAction(Ea_Route $route)
	{
		$this->_action=$route;
		//parent::setAttribute('action', $this->getActionUrl());
	}

	/**
	 * Return action
	 *
	 * @return Ea_Route
	 */
	public function getAction()
	{
		return $this->_action;
	}
	
	public function getActionUrl()
	{
		if($this->getAction()) return $this->getPage()->getRouter()->url($this->getAction());
		return '';
	}

	protected $_method=null;
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

	public function getMethod()
	{
		return $this->_method;
	}
	
	public function setId($id)
	{
		if(!$id)
		{
			throw new Ea_Layout_Form_Exception('Empty Id');
		}
		$this->_id=$id;
		parent::setAttribute('id', $id);
		parent::setAttribute('name', $id);
	}
	
	public function getId()
	{
		return $this->_id;
	}
	
	//FIXME : simplify
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

	/*
	 * Add some magic stuff
	 */
	protected $_init=false;
	protected function magic()
	{
		if(!$this->_magic)
		{
			//magic input
			$this->add(new Ea_Layout_Input_Hidden($this->getMagicName('id'), $this->getId()));
			$this->_magic=true;
		}
	}

	protected $_magicNamePrefix='eaf';
	protected function getMagicName($name)
	{
		return $this->_magicNamePrefix.'_'.$name;
	}
	
	public function render()
	{
		$this->magic();
		parent::setAttribute('action', $this->getActionUrl());
		parent::render();
	}

	/*
	 * POST stuff
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
	
	public function tryCatch()
	{
		switch($this->getMethod())
		{
			case 'post':
				if(!$this->getPage()->getRouter()->isPost()) return false;
				//testing magic input
				$magicId=$this->getMagicName('id');
				if(!array_key_exists($magicId, $_POST)) return false;
				if($_POST[$magicId]!=$this->getId()) return false;
				$this->recusiveWalk(array($this, 'parseInput'));
				return true;
				break;
			default:
				throw new Ea_Layout_Form_Exception('Not yet done');
		}
	}
	
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
	
	protected function compute()
	{
		throw new Ea_Layout_Form_Exception('To use run() please overload compute()');
	}
	
	public function run()
	{
		if($this->parse())
		{
			$this->compute();
		}
	}
	
}

?>