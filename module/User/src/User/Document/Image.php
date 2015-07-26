<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 10.02.2015
 * Time: 7:39
 */

namespace User\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class Image
{
	/** @ODM\Id */
	private $id;

	/** @ODM\Field */
	private $name;

	/** @ODM\File */
	private $file;

	/** @ODM\Field */
	private $uploadDate;

	/** @ODM\Field */
	private $length;

	/** @ODM\Field */
	private $chunkSize;

	/** @ODM\Field */
	private $md5;

	public function getId()
	{
		return $this->id;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getFile()
	{
		return $this->file;
	}

	public function setFile($file)
	{
		$this->file = $file;
	}
}