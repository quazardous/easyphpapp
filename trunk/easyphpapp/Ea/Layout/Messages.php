<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Base
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     $Id:$
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
	
	const success='success';
	const notice='notice';
	const warning='warning';
	const error='error';
	
	/**
	 * Link layout constructor.
	 * 
	 * @param string $message
	 * @param string $type success|notice|warning|error
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
		$this->addAttribute('class', 'messages');
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
	
	/**
	 * Add a message.
	 * 
	 * @param string|Ea_Layout_Abstract $content
	 * @param string $type
	 */
	public function addMessage($content, $type=self::notice)
	{
		$div=new Ea_Layout_Container('div');
		$div->addAttribute('class', $type);
		$div->add($content);
		$this->add($div);
	}
	
	protected function preRender()
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
				$this->addMessage($message->content, $message->type);
				$ismsg=true;
			}
			return $render&&$ismsg;
		}
		return false;
	}
	
	protected function postRender()
	{
		parent::postRender();
		if($this->getPage() instanceof Ea_Page)
		{
			$this->getPage()->getModule()->resetMessages($this->getId());
		}
	}

}