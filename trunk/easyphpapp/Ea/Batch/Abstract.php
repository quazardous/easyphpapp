<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Batch
 * @subpackage  Page
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.3.4-20090202
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Batch/Exception.php';

/**
 * Little batch class.
 * 
 */
abstract class Ea_Batch_Abstract
{
	const stop_ok    = 0;
	
	const log_notice =0;
	const log_warning=1;
	const log_error  =2;

	protected $_log=null;
	protected $_batch='*unknown*';
	protected $_params=array(
		/*
		 * $name=>array(
		 * 		'value'=>$value,
		 * 		'shortop'=>'p', // for setParamsFromGetopt()
		 * 		'longop'=>'param', // for setParamsFromGetopt()
		 * 		'boolean'=>false, // just a toggle option with no value
		 * 		'optional'=>false), // sen getopt()
		 * 	$name=>$value,
		 */
		//
	);
	
	protected $_config=array();

	/**
	 * Handle the batch.
	 * 
	 * @return code
	 */
	public function run()
	{		
		$started=microtime(true);
		$this->log("starting...");
		foreach($this->_params as $param => $value)
		{
			if(is_array($value))
			{
				$option='';
				$nop=0;
				if(isset($value['shortop']))
				{
					$option.=" | -{$value['shortop']}";
					$nop++;
				}
				if(isset($value['longop']))
				{
					$option.=" | --{$value['longop']}";
					$nop++;
				}
				$option=trim($option,'| ');
				if($nop>1) $option="( {$option} )";
				if(isset($value['boolean'])&&$value['boolean'])
				{
					$value['value']=$value['value']?'true':'false';
				}
				else
				{
					if($option) $option.=' <value>';
				}
				if($option) $option=" {$option} ";
				if(is_object($value['value']))
				{
					$this->log(" {$param}{$option}: (object: ".get_class($value['value']).")");
				}
				else if(is_array($value['value']))
				{
					$this->log(" {$param}{$option}: (array)");
				}
				else
				{
					$this->log(" {$param}{$option}: {$value['value']}");
				}
			}
			else
			{
				$this->log(" {$param}: {$value}");
			}
		}
		
		try
		{
			$this->start();
		}
		catch(Ea_Batch_Exception $e)
		{
			if($e->getCode()==Ea_Batch_Exception::stop)
			{
				$this->cleanup();
				return $e->getExitCode();
			}
			$this->log("Exception", self::log_error);
			if($this->_log)
			{
				$this->rawlog($e->getMessage());
				$this->rawlog($e->getTraceAsString());
			}
			$this->cleanup();
			throw $e;
		}
		catch(Exception $e)
		{
			$this->log("Unknown exception", self::log_error);
			if($this->_log)
			{
				$this->rawlog($e->getMessage());
				$this->rawlog($e->getTraceAsString());
			}
			$this->cleanup();
			throw $e;
		}
		$this->log(sprintf("done in %.03f seconds.", microtime(true)-$started));
		$this->cleanup();
		return self::stop_ok;
	}

	public function getParamsList()
	{
		return array_keys($this->_params);
	}
	
	public function getParam($name)
	{
		if(array_key_exists($name, $this->_params))
		{
			if(is_array($this->_params[$name])) return $this->_params[$name]['value'];
			return $this->_params[$name];
		}
		throw new Ea_Batch_Exception("{$name} : invalid param", Ea_Batch_Exception::invalid_param);
	}

	public function setParam($name, $value)
	{
		if(array_key_exists($name, $this->_params))
		{
			$this->_params[$name]['value']=$value;
		}
		else throw new Ea_Batch_Exception("{$name} : invalid param", Ea_Batch_Exception::invalid_param);
	}
	
	public function __construct($logfile=null)
	{
		$this->_log=$logfile;
	}

	protected function rawlog($string)
	{
		if($this->_log)
		{
			if($fd=fopen($this->_log, 'a+'))
			{
				fwrite($fd, $string);
				fflush($fd);
				fclose($fd);
			}
			else
			{
				trigger_error("{$this->_log}: cannot open log file.");
				echo $string;
			}
		}
		echo $string;
	}
	
	protected function log($string, $severity = self::log_notice)
	{
		static $severity_libs=array(
		self::log_notice=> 'notice',
		self::log_warning=>'warning',
		self::log_error=>  'error',
		);
		$this->rawlog(sprintf("%s [%s] %s : %s\n" ,strftime("%c"), $this->_batch, $severity_libs[$severity], $string));
	}
	
	/**
	 * Batch body.
	 * 
	 */
	abstract protected function start();

	/**
	 * Stop the batch within run().
	 * 
	 * @param $code
	 * @param $string
	 */
	protected function stop($code, $string="")
	{
		$this->log(sprintf("STOP [code=%u] %s", $code, $string), self::log_error);
		throw new Ea_Batch_Exception('stop', Ea_Batch_Exception::stop, intval($code));
	}
	
	public function cleanup()
	{
		// TODO : cleanup stuff
	}
	
	public function setParamsFromArray($arr)
	{
		foreach($this->_params as $name=>$value)
		{
			if(isset($arr[$name])) $this->setParam($name, $arr[$name]);
		}
	}
	
	public function setParamsFromGetopt()
	{
		$shortops='';
		$longops=array();
		$revopts=array();
		foreach($this->_params as $name=>$value)
		{
			if(isset($value['shortop']))
			{
				$revopts[$value['shortop']]=$name;
			}
			if(isset($value['longop']))
			{
				$revopts[$value['longop']]=$name;
			}
			
			if(isset($value['boolean'])&&$value['boolean'])
			{
				if(isset($value['shortop']))
				{
					$shortops.=$value['shortop'];
				}
				if(isset($value['longop']))
				{
					$longops[]=$value['longop'];
				}
			}
			else if(isset($value['optional'])&&$value['optional'])
			{
				if(isset($value['shortop']))
				{
					$shortops.=$value['shortop'].'::';
				}
				if(isset($value['longop']))
				{
					$longops[]=$value['longop'].'::';
				}
			}
			else
			{
				if(isset($value['shortop']))
				{
					$shortops.=$value['shortop'].':';
				}
				if(isset($value['longop']))
				{
					$longops[]=$value['longop'].':';
				}
			}
		}
		if(count($longops))	$options=getopt($shortops, $longops);
		else $options=getopt($shortops);
		foreach($options as $op=>$val)
		{
			$this->setParam($revopts[$op], $val);
		}
	}
	
}