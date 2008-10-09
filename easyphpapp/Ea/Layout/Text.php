<?php
require_once 'Ea/Layout/Abstract.php';
class Ea_Layout_Text extends Ea_Layout_Abstract
{
	public function __construct($config=null)
	{
		parent::__construct($config);
		if(is_array($config))
		{
			if(array_key_exists('text', $config))
			{
				$this->_text=$config['text'];
			}
			if(array_key_exists('escape', $config))
			{
				$this->_escape=$config['escape'];
			}
		}
		else if(is_string($config)||is_numeric($config))
		{
			$this->_text=$config;
		}
	}
	
	protected $_text=null;
	protected $_escape=true;
	
	public function render()
	{
		if($this->_escape) echo $this->escape($this->_text);
		echo $this->_text;
	}
}
?>