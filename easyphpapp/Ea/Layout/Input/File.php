<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.0.2.6.20081022
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @copyright   David Berlioz <berlioz@nicematin.fr>
 */

require_once 'Ea/Layout/Input/Abstract.php';
require_once 'Ea/Layout/Form.php';

/**
 * File input layout class.
 */
class Ea_Layout_Input_File extends Ea_Layout_Input_Abstract
{
	protected $_type='file';
	
	/**
	 * Return the 
	 * 
	 * @param $field
	 * @return unknown_type
	 */
	protected function getFileId($field)
	{
		$id=$this->getId();
		if(count($id)==1)
		{
			array_push($id, $field);
			return $id;
		}
		$ret=array($id[0], $field);
		$ret=array_merge($ret, array_slice($id, 1));
		return $ret;
	}
	
	/**
	 * Get the name of the uploaded file.
	 * 
	 * @return string
	 */
	public function getFileName()
	{
		if(!isset($_FILES)) return null;
		return Ea_Layout_Form::array_get_from_id($_FILES, $this->getFileId('name'));
	}

	/**
	 * Get the mime type of the uploaded file.
	 * 
	 * @return string
	 */
	public function getFileType()
	{
		if(!isset($_FILES)) return null;
		return Ea_Layout_Form::array_get_from_id($_FILES, $this->getFileId('type'));
	}

	/**
	 * Get the tmp namee of the uploaded file.
	 * 
	 * @return string
	 */
	public function getFileTmpName()
	{
		if(!isset($_FILES)) return null;
		return Ea_Layout_Form::array_get_from_id($_FILES, $this->getFileId('tmp_name'));
	}

	/**
	 * Get the size of the uploaded file.
	 * 
	 * @return numeric
	 */
	public function getFileSize()
	{
		if(!isset($_FILES)) return null;
		return Ea_Layout_Form::array_get_from_id($_FILES, $this->getFileId('size'));
	}

	/**
	 * Get the error code for the uploaded file.
	 * 
	 * @return numeric
	 */
	public function getFileError()
	{
		if(!isset($_FILES)) return null;
		return Ea_Layout_Form::array_get_from_id($_FILES, $this->getFileId('error'));
	}
	
	/**
	 * File was upload ?.
	 * 
	 * @return boolean
	 */
	public function isUploadedFile()
	{
		if(!isset($_FILES)) return false;
		return is_uploaded_file($this->getFileTmpName());
	}

	/**
	 * Move uploaded file.
	 * 
	 * @return boolean
	 */
	public function moveUploadedFile($destination)
	{
		if(!isset($_FILES)) return false;
		return move_uploaded_file($this->getFileTmpName(), $destination);
	}

	/**
	 * Get file contents.
	 * @see file_get_contents
	 * 
	 * @return boolean
	 */
	public function getFileContents($maxlen=null, $offset=null, $flags=null)
	{
		if(!isset($_FILES)) return null;
		return file_get_contents($this->getFileTmpName(), $flags, null, $offset, $maxlen);
	}
	
	public function getValue()
	{
		//TODO : is it ok ?
		return $this->getFileName();
	}
}
?>