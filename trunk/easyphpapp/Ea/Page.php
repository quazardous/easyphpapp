<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Page
 * @subpackage  Page
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.1
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Container.php';
require_once 'Ea/Module/Abstract.php';

/**
 * Page class.
 * The page is used by the module to render all page elements. It contains layouts.
 * @see Ea_Module_Abstract
 * @see Ea_Layout_Abstract
 * 
 */
class Ea_Page
{
	/**
	 * Top layout.
	 * The top layout is where the page starts to render content.
	 * @see render()
	 * @see Ea_Layout_Abstract
	 *
	 * @var Ea_Layout_Container
	 */
	protected $_top=null;

	/**
	 * Main layout.
	 * The main layout is where you can add more content.
	 * @see add()
	 * @see Ea_Layout_Abstract
	 *
	 * @var Ea_Layout_Container
	 */
	protected $_main=null;
	
	/**
	 * The title of the page.
	 * Used to render the <title> tag.
	 * 
	 * @var string
	 */
	protected $_title=null;

	/**
	 * Contains CSS file to use ase style sheets.
	 * 
	 * @var array(string)
	 */
	protected $_styles=array();
	
	/**
	 * If true, the page is render only once, even if render() is called many times.
	 * @see render()
	 * 
	 * @var boolean
	 */
	protected $_smartRender=true;

	/**
	 * Set smart render.
	 * @see $_smartRender
	 * 
	 * @param boolean $smart
	 */
	public function setSmartRender($smart)
	{
		$this->_smartRender=$smart;
	}
	
	
	/**
	 * Used by render().
	 * 
	 * @var boolean
	 */
	protected $_rendered=false;

	/**
	 * The module that called the page.
	 *
	 * @var Ea_Module_Abstract
	 */
	protected $_module=null;
	
	/**
	 * Page constructor.
	 * 
	 * @param Ea_Module_Abstract|array(string=>mixed) $config
	 * 
	 * - if Ea_Module_Abstract : the module that instantiate the page.
	 * 
	 * - if associative array : 'module', 'title'
	 */
	public function __construct($config=null)
	{
		if($config instanceof Ea_Module_Abstract)
		{
			$config=array('module'=>$config);
		}
		
		if(is_array($config))
		{
			if(array_key_exists('module', $config)) $this->setModule($config['module']);
			if(array_key_exists('title', $config)) $this->setTitle($config['title']);
		}
		
		$this->_top=new Ea_Layout_Container('body', $this);

		/*
		 * Can be usefull to make difference between top layout and main layout (where to add new content)
		 */
		$this->_main=$this->_top;
	} 
	
	/**
	 * Set the module.
	 * @see $_module
	 * 
	 * @param Ea_Module_Abstract $module
	 */
	public function setModule(Ea_Module_Abstract $module)
	{
		$this->_module=$module;
	}

	/**
	 * Return the module.
	 * @see $_module
	 *
	 * @return Ea_Module_Abstract
	 */
	public function getModule()
	{
		return $this->_module;
	}

	/**
	 * Return the router.
	 *
	 * @return Ea_Router
	 */
	public function getRouter()
	{
		return $this->_module->getRouter();
	}
	
	/**
	 * Set the title.
	 * @see $_title
	 * 
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->_title=$title;
	}
	
	/**
	 * Append to the title.
	 * @see $_title
	 * 
	 * @param string $title
	 */
	public function appendTitle($title)
	{
		$this->_title.=$title;
	}
	
	/**
	 * Get the title.
	 * @see $_title
	 * 
	 * @return string
	 */
	public function getTitle()
	{
		return $this->_title;
	}
	
	/**
	 * Add a style sheet.
	 * 
	 * @param string $href
	 * @param string $media default is 'all'
	 */
	public function addStyle($href, $media='all')
	{
		array_push($this->_styles, array('href'=>$href, 'media'=>$media));
	}
			
	/**
	 * Render the page.
	 * 
	 */
	public function render()
	{
		if($this->_smartRender&&$this->_rendered) return;
		?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
		?><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<?php
		foreach($this->_styles as $style)
		{
			?><link type="text/css" href="<?php echo $this->escape($style['media']); ?>" rel="stylesheet" media="<?php echo $this->escape($style['media']); ?>" />
<?php
		}
		if($this->_title)
		{
			?><title><?php echo $this->escape($this->_title); ?></title>
<?php
		}
		?></head><?php
		$this->_top->preRender();
		$this->_top->render();
		?>
</html><?php
		$this->_rendered=true;
	}
	
	/**
	 * Add content to the page. In fact add content to the main layout.
	 * @uses $_main
	 * @see Ea_Layout_Abstract
	 * 
	 * @param mixed $content
	 */
	public function add($content, $append=true)
	{
		$this->_main->add($content, $append);
	}
	
	/**
	 * Main escaping method. Used by all children layouts.
	 * @see Ea_Layout_Abstract::escape()
	 * 
	 * @param string $string
	 * @return string
	 */
	public function escape($string)
	{
		return htmlentities($string);
	}
	
	/**
	 * Get an url.
	 * @see Ea_Router::getRoute()
	 * @see Ea_Router::url()
	 * 
	 * @param array(string=>string) $params
	 * @param string $action
	 * @param string $module
	 * @param string $script
	 * @param string $fragment
	 * @return string
	 */
	public function url($params=null, $action=null, $module=null, $script=null, $fragment=null)
	{
		return $this->getRouter()->url($this->getRouter()->getRoute($params, $action, $module, $script, $fragment));
	}

	/**
	 * Rediert to an url.
	 * @see Ea_Router::getRoute()
	 * @see Ea_Router::redirect()
	 * 
	 * @param array(string=>string) $params
	 * @param string $action
	 * @param string $module
	 * @param string $script
	 * @param string $fragment
	 * @param boolean $exit
	 */
	public function redirect($params=null, $action=null, $module=null, $script=null, $fragment=null, $exit=true)
	{
		return $this->getRouter()->redirect($this->getRouter()->getRoute($params, $action, $module, $script, $fragment), $exit);
	}
}

?>