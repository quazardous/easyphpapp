<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.3.1-20081114
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Single.php';
require_once 'Ea/Layout/Input/Exception.php';

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
	 * Ea_Layout_Input_Abstract constructor.
	 *
	 * @param string|array(string) $id the id of the input
	 * @param string|numeric $value
	 * @param array $config
	 * 
	 * @see $_id
	 */
	public function __construct($id, $value=null, $config=null)
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
		}
		
	}

	public function setAttribute($name, $value)
	{
		$name=strtolower($name);
		switch($name)
		{
			/*
			 * Some reserved attributes...
			 */
			case 'type': $this->setType($value); break;
			case 'value': $this->setValue($value); break;
			case 'name': case 'id':
				throw new Ea_Layout_Input_Exception("$name: forbidden attribute for input");
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
			/*
			 * Some reserved attributes...
			 */
			case 'type': return $this->getType();
			case 'value': return $this->getValue();
			case 'name': return $this->getName();
			case 'id': return $this->getId();
			default: return parent::getAttribute($name);
		}
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
	 * Set the input Id.
	 *
	 * @param string|array(string) $id
	 * 
	 * @see $_id
	 */
	public function setId($id)
	{
		if(!is_array($id)) $id=self::get_id_from_name($id);
		if(count($id)<1)
		{
			throw new Ea_Layout_Input_Exception("Empty id");
		}
		$first=true;
		foreach($id as $pid)
		{
			if($first)
			{
				if(!preg_match('|^[a-z][a-z0-9_]*$|i', $pid))
				{
					throw new Ea_Layout_Input_Exception("'$pid' incorrect first value for input id");
				}
			}
			else
			{
				if(!($pid===null||is_numeric($pid)||preg_match('/^[a-z][a-z0-9_]*$/i', $pid)))
				{
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
		$name=false;
		foreach($this->_id as $pid)
		{
			if($name) $name.="[".$pid."]";
			else $name=$pid;
		}
		return $name;
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
	 */
	public function setFromPost()
	{
		$this->setValue($this->getForm()->getValueFromPost($this->getId()));
	}
	
	public function preRender()
	{
		parent::preRender();
		/*
		 * Display the reserved attributes...
		 */
		$this->_setAttribute('name', $this->getName());
		$this->_setAttribute('value', $this->getValue());
		$this->_setAttribute('type', $this->getType());
	}
	
	public function __sleep()
    {
        return array('_id', '_value');
    }
	    
    /**
     * Set remember Value.
     * 
     * @param boolean $remember
     */
    public function rememberValue()
    {
    	if($this->_value===null)
    	{
    		$this->setValue($this->getForm()->getValueFromSession($this->getId()));
    	}
    }
}
?>