<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.1
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
		parent::__construct($config);
		$this->setId($id);
		$this->setValue($value);
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
	
	/**
	 * Set the input Id.
	 *
	 * @param string|array(string) $id
	 * 
	 * @see $_id
	 */
	public function setId($id)
	{
		if(!is_array($id)) $id=array($id);
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
		return $this->getValue();
	}
	
	/**
	 * Used to easily access the input name and value in string expression.
	 * 
	 * Example :
	 * <code>
	 * <?php
	 * 	echo "value: {$input->value}";
	 * ?>
	 * </code>
	 *
	 * @return string the name or the value
	 * 
	 * @property-read int $name the input name
	 * @property-read int $value the input value
	 */
	public function __get($name)
	{
		switch($name)
		{
			case 'value': return $this->getValue();
			case 'name': return $this->getName();
			default :
				throw new Ea_Form_Input_Exception("'$name' incorrect field for input");
		}
	}
	
	public function render()
	{
		/*
		 * Display the reserved attributes...
		 */
		parent::setAttribute('name', $this->getName());
		parent::setAttribute('value', $this->getValue());
		parent::setAttribute('type', $this->getType());
		parent::render();
	}
	
}
?>