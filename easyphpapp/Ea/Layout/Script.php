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

require_once 'Ea/Layout/Single.php';

/**
 * Script layout class.
 */
class Ea_Layout_Script extends Ea_Layout_Single
{
	protected $_tag='script';

	/**
	 * Script is called with document.onload.
	 * 
	 * @var boolean
	 */
	protected $_onload=false;
	
	/**
	 * Get  Onload.
	 *
	 * @return type onload
	 */
	public function getOnload() 
	{
		return $this->_onload;
	}
	
	/**
	 * Set Onload.
	 * 
	 * @param boolean onload
	 */
	public function setOnload($onload=true)
	{
		$this->_onload = $onload;
		if($this->getPage() instanceof Ea_Page)
		{
			if($onload)
			{
				$this->getPage()->setOnloadSupport(true);
			}
		}
	}
	
	/**
	 * Script constructor.
	 * 
	 * @param string $src
	 * @param string $script
	 * @param string $type
	 * @param array $config
	 */
	public function __construct($script=null, $onload=false, $type='text/javascript', $config=null)
	{
		parent::__construct(null, $config);
		$this->setScript($script);
		$this->setAttribute('type', $type?$type:'text/javascript');
		$this->setOnload($onload);
	} 
	
	public function setSrc($src)
	{
		$this->setAttribute('src', $src);
	}
	
	protected $_script=null;
	public function setScript($script)
	{
		$this->_script=$script;
	}
	public function getScript()
	{
		return $this->_script;
	}
		
	protected function render()
	{
		echo "\n<";
		echo $this->_tag;
		$this->renderAttributes();
		echo '>';
	
		if($this->getOnload())
		{
			if(($this->getPage() instanceof Ea_Page)&&(!$this->getPage()->getApp()->getJQuery()))
			{
				echo '
ea.addOnload(function(){
	';
			}
			else
			{
				// by default try jQuery ready() :p
				echo '
$(function(){
	';
			}
		}
		
		echo $this->getScript();

		if($this->getOnload())
		{
			echo '
});
';
		
		}		
		
		echo '</';
		echo $this->_tag;
		echo ">";
	}
	
	public function setPage($page)
	{
		parent::setPage($page);
		if($this->getPage() instanceof Ea_Page)
		{
			if($this->getOnload())
			{
				$this->getPage()->setOnloadSupport(true);
			}
		}
	}
}
