<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Base
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.4.1-20091113
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Container.php';

/**
 * A session persitent message class.
 * ie. message from form submission.
 * 
 */
class Ea_Layout_Messages extends Ea_Layout_Container
{
	protected $_tag='div';
	
	const succes='succes';
	const notice='notice';
	const warning='warning';
	const error='error';
	
	/**
	 * Link layout constructor.
	 * 
	 * @param string $message
	 * @param string $type succes|notice|warning|error
	 * @param array(string=>mixed) $config associative array of parameters
	 * 
	 * - 'callbacks' : an array of "on add layout" callbacks
	 * @see addCallback()
	 * 
	 */
	public function __construct($id='default', $config=null)
	{
		$this->setId($id); // before parent construct
		parent::__construct(null, $config);
		$this->addAtribute('class', 'messages');
	}
	
	protected $_messages=array();
		
	/**
	 * Reset messages.
	 * 
	 */
	public function resetMessages()
	{
		$this->_messages=array();
	}

	/**
	 * The form id (and name).
	 * 
	 * @var string
	 */
	protected $_id=null;
		
	/**
	 * Set the message id.
	 * 
	 * @param string $id
	 */
	public function setId($id)
	{
		$this->_id=$id;
	}
	
	/**
	 *  Get the message id.
	 * 
	 * @return string
	 */
	public function getId()
	{
		return $this->_id;
	}
	
	public function preRender()
	{
		$render=parent::preRender();
		if(!$this->getAttribute('id'))
		{
			$this->_setAttribute('id', $this->getId());
		}
		if($this->getPage() instanceof Ea_Page)
		{
			$ismsg=false;
			foreach($this->getPage()->getModule()->getMessages($this->getId()) as $message)
			{
				$div=new Ea_Layout_Container('div');
				$div->addAtribute('class', $message->type);
				$div->add($message->content);
				$this->add($div);
				$ismsg=true;
			}
			return $render&&$ismsg;
		}
		return false;
	}
	
	public function postRender()
	{
		parent::postRender();
		if($this->getPage() instanceof Ea_Page)
		{
			$this->getPage()->getModule()->resetMessages($this->getId());
		}
	}

}
?>