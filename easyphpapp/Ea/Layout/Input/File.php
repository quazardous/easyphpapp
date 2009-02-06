<?php
/**
 * EasyPhpApp Framework
 * A simple form application framework
 * 
 * @category    EasyPhpApp
 * @package     Layout
 * @subpackage  Form
 * @author      David Berlioz <berlioz@nicematin.fr>
 * @version     0.3.5-20090206
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
	 * Return the reordered file id for $_FILES array.
	 * 
	 * @param $field
	 * @return array
	 */
/*	protected function getFileId($field)
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
*/	
	public static function get_reordered_field_id($id, $field)
	{
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
		return $this->getForm()->getUploadedFileName($this->getId());
	}

	/**
	 * Get the mime type of the uploaded file.
	 * 
	 * @return string
	 */
	public function getFileType()
	{
		return $this->getForm()->getUploadedFileType($this->getId());
	}

	/**
	 * Get the tmp namee of the uploaded file.
	 * 
	 * @return string
	 */
	public function getFileTmpName()
	{
		return $this->getForm()->getUploadedFileTmpName($this->getId());
	}

	/**
	 * Get the size of the uploaded file.
	 * 
	 * @return numeric
	 */
	public function getFileSize()
	{
		return $this->getForm()->getUploadedFileSize($this->getId());
	}

	/**
	 * Get the error code for the uploaded file.
	 * 
	 * @return numeric
	 */
	public function getFileError()
	{
		return $this->getForm()->getUploadedFileError($this->getId());
	}
	
	/**
	 * File was upload ?.
	 * 
	 * @return boolean
	 */
	public function isUploadedFile()
	{
		return $this->getForm()->isUploadedFile($this->getId());
	}

	/**
	 * Move uploaded file.
	 * 
	 * @return boolean
	 */
	public function moveUploadedFile($destination)
	{
		return $this->getForm()->moveUploadedFile($this->getId(), $destination);
	}

	/**
	 * Get file contents.
	 * @see file_get_contents
	 * 
	 * @param $maxlen
	 * @param $offset
	 * @param $flags
	 * @return string
	 */
	public function getFileContents($maxlen=null, $offset=null, $flags=null)
	{
		return $this->getForm()->getUploadedFileContents($this->getId(), $maxlen, $offset, $flags);
	}
	
	public function getValue()
	{
		//TODO : is it ok ?
		return $this->getFileName();
	}
}
?>