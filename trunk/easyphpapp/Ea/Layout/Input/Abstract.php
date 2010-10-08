<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      berlioz [$Author$]
 * @version     0.4.6-20101007 [$Id$]
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Single.php';

/**
 * Abstract input layout class.
 * 
 * Base class for all input element used with form.
 * @see Ea_Layout_Form
 * 
 * @abstract
 */
abstract class Ea_Layout_Input_Abstract extends Ea_Layout_Single
{
	protected $_tag='input';
	
	/**
	 * The form to which the input belongs.
	 *
	 * @var Ea_Layout_Form
	 */
	protected $_form=null;
	
	/**
	 * Return the form handler.
	 *
	 * @return Ea_Layout_Form
	 */
	public function getForm()
	{
		return $this->_form;
	}
	
	public function setForm(Ea_Layout_Form $form)
	{
		$this->_form=$form;
	}
	
	/**
	 * The input type (text, hidden, ...).
	 *
	 * @var string
	 */
	protected $_type=null;
	
	/**
	 * The input Id.
	 * 
	 * ie : array( 'a', 'b', 'c' ) will produce <input name="a[b][c]" ... /> and could be acceded by $form['a']['b']['c'].
	 * 
	 * @var array(string)
	 * 
	 * @see Ea_Layout_Form
	 */
	protected $_id=null;
	
	/**
	 * The input value.
	 *
	 * @var string|numeric
	 */
	protected $_value=null;

	/**
	 * Is the input disabled.
	 * 
	 * @var boolean
	 */
	protected $_disabled=false; 
	
	/**
	 * Disable the input.
	 * 
	 * @param boolean $disable
	 */
	public function setDisabled($disabled)
	{
		if(is_bool($disabled)) $this->_disabled=$disabled;
		else $this->_disabled=strtolower($disabled)=='disabled'?true:false;
	}
	
	public function isDisabled()
	{
		return $this->_disabled;
	}
	
	/**
	 * Ea_Layout_Input_Abstract constructor.
	 *
	 * @param string|array(string) $id the id of the input
	 * @param string|numeric $value
	 * @param array $config
	 * 
	 * @see $_id
	 */
	public function __construct($id=null, $value=null, $config=null)
	{
		parent::__construct(null, $config);
		$this->setId($id);
		$this->setValue($value);
		if(is_array($config))
		{
			if(array_key_exists('remember_value', $config))
			{
				$this->rememberValue();
			}
			if(array_key_exists('disabled', $config))
			{
				$this->setDisabled($config['disabled']);
			}
		}
		$this->protectAttribute('type');
		$this->protectAttribute('value');
		$this->protectAttribute('name');
		$this->protectAttribute('id', 'setAttributeId', 'getAttributeId');
		$this->protectAttribute('disabled', 'setDisabled', 'isDisabled');
	}

	public function setAttributeId($value)
	{
		//it's ok id attribute can be different from internal id
		$this->_setAttribute('id', $value);
	}
	
	public function getAttributeId()
	{
		$id=$this->_getAttribute('id');
		if($id) return $id;
		return $this->getName();
	}
	
	public function setName($value)
	{
	    require_once 'Ea/Layout/Input/Exception.php';
		throw new Ea_Layout_Input_Exception("name : forbidden attribute for input");
	}
	
	/**
	 * Get array id from name.
	 * ie 'foo[bar]' => array('foo', 'bar')
	 * 
	 * @param string $name
	 * @return string|array
	 */
	public static function get_id_from_name($name, $alwaysArray=true)
	{
		if(is_array($name)) return $name;
		if(preg_match('|\[|', $name))
		{
			$id=array();
			foreach(explode('[', $name) as $pid)
			{
				$id[]=trim($pid, '[]');
			}
			return $id;
		}
		if($alwaysArray) return array($name);
		return $name;
	}
	
	/**
	 * Get name from id.
	 * 
	 * @param array $id
	 * @return string
	 */
	public static function get_name_from_id($id)
	{
		if(!is_array($id)) return $id;
		$name=false;
		foreach($id as $pid)
		{
			if($name) $name.="[".$pid."]";
			else $name=$pid;
		}
		return $name;
	}
	
	/**
	 * Set the input Id.
	 *
	 * @param string|array(string) $id
	 * 
	 * @see $_id
	 */
	public function setId($id)
	{
		if(!$id)
		{
			$this->_id=null;
			return;
		}
		// TODO : set id only once
		if(!is_array($id)) $id=self::get_id_from_name($id);
		if(count($id)<1)
		{
		    require_once 'Ea/Layout/Input/Exception.php';
			throw new Ea_Layout_Input_Exception("Empty id");
		}
		$first=true;
		foreach($id as $pid)
		{
			if($first)
			{
				if(!preg_match('|^[a-z][a-z0-9_-]*$|i', $pid))
				{
					require_once 'Ea/Layout/Input/Exception.php';
					throw new Ea_Layout_Input_Exception("'$pid' incorrect first value for input id");
				}
			}
			else
			{
				if(!($pid===null||is_numeric($pid)||preg_match('/^[a-z][a-z0-9_-]*$/i', $pid)))
				{
				    require_once 'Ea/Layout/Input/Exception.php';
					throw new Ea_Layout_Input_Exception("'$pid' incorrect value for input id");
				}
			}
			$first=false;
		}
		$this->_id=$id;
	}
	
	/**
	 * Return the input Id.
	 *
	 * @return array(string)
	 * 
	 * @see $_id
	 */
	public function getId()
	{
		return $this->_id;
	}

	/**
	 * Return the input name.
	 *
	 * @return string
	 * 
	 * @see $_id
	 */
	public function getName()
	{
		if(count($this->_id)==0) return null;
		if(count($this->_id)==1) return $this->_id[0];
		return self::get_name_from_id($this->_id);
	}
	
	/**
	 * Return the input value.
	 *
	 * @return string|numeric
	 * 
	 * @see $_value
	 */
	public function getValue()
	{
		return $this->_value;
	}

	/**
	 * Set the input value.
	 *
	 * @param string|numeric $value
	 * 
	 * @see $_value
	 */
	public function setValue($value)
	{
		$this->_value=$value;
	}

	/**
	 * Return the input type.
	 *
	 * @return string
	 * 
	 * @see $_type
	 */
	public function getType()
	{
		return $this->_type;
	}

	/**
	 * Set the input type.
	 *
	 * @param string $type
	 * 
	 * @see $_type
	 */
	public function setType($type)
	{
		$this->_type=$type;
	}
	
	/**
	 * Used to easily access input value directly from the form array.
	 * 
	 * Example :
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
	 * @see Ea_Layout_Form
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string)$this->getValue();
	}
	
	/**
	 * Set the value from $_POST.
	 * 
	 * @param string|numeric $value from POST
	 * 
	 */
	public function setValueFromForm($value=null)
	{
		if($value===null) $value=$this->getForm()->getValueFromPost($this->getId());
		$this->setValue($value);
	}
	
	/**
	 * Return the value for form rendering.
	 * 
	 * @return string
	 */
	public function getValueForForm()
	{
		return $this->getValue();
	}
	
	protected function preRender()
	{
		$render=parent::preRender();
		/*
		 * Display the reserved attributes...
		 */
		$this->_setAttribute('name', $this->getName());
		if(!$this->_getAttribute('id'))
		{
			$this->_setAttribute('id', $this->getName());
		}
		$this->_setAttribute('value', $this->getValueForForm());
		$this->_setAttribute('type', $this->getType());
		$this->_setAttribute('disabled', $this->isDisabled()?'disabled':null);
		return $render;
	}
	
	public function __sleep()
    {
        return array('_id', '_value');
    }
	    
    /**
     * Set remember Value.
     * 
     * @param string $defaultValue
     * 
     * @param boolean $remember
     */
    public function rememberValue($defaultValue=false)
    {
    	if($this->getForm())
    	{
    		$this->getForm()->assertStore();
    	}
    	if($this->_value===null)
    	{
    		$this->setValue($this->getForm()->getValueFromSession($this->getId()));
    	}
    	if($defaultValue!==false)
    	{
    		$this->setDefaultValue($defaultValue);
    	}
    }
    
    /**
     * For now, set value if no current value.
     * 
     * @param string $value
     * 
     * @return unknown_type
     */
    public function setDefaultValue($value)
    {
    	if($this->_value===null)
    	{
    		$this->setValue($value);
    	}
    }
}
