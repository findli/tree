<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 03.02.2015
 * Time: 5:56
 */

namespace Tree\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\EmbeddedDocument */
class Ancestor
{
	/** @ODM\Id */
	private $id;
	/** @ODM\Int */
	private $id2;

	/**
	 * @return mixed
	 */
	public function getId2()
	{
		return $this->id2;
	}

	/**
	 * @param mixed $id2
	 */
	public function setId2( $id2 )
	{
		$this->id2 = $id2;
	}
	/** @ODM\String */
	private $name;

	/** @ODM\string */
	private $slug;

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}


	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param mixed $name
	 */
	public function setName( $name )
	{
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * @param mixed $slug
	 */
	public function setSlug( $slug )
	{
		$this->slug = $slug;
	}

}