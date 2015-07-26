<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 03.02.2015
 * Time: 5:56
 */

namespace Tree\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class Categories
{
	/** @ODM\Id */
	private $id;
	/** @ODM\String */
	private $slug;
	/** @ODM\ObjectId */
	private $parent_id;
	/** @ODM\String */
	private $name;
	/** @ODM\String */
	private $description;
	/** @ODM\EmbedMany(targetDocument="Tree\Document\Ancestor") */
	private $ancestors = [];
	public function __construct()
	{
		$this->ancestors = new ArrayCollection();
	}
	function __set($k, $v){
		$this->{$k} = $v;
	}
	function __get($k){
		return $this->{$k};
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

	/**
	 * @return mixed
	 */
	public function getAncestors()
	{
		return $this->ancestors;
	}

	/**
	 * @param mixed $ancestors
	 */
	public function addAncestor( \Tree\Document\Ancestor $ancestor )
	{
		$this->ancestors[ ] = $ancestor;
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId( $id )
	{
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getParentId()
	{
		return $this->parent_id;
	}

	/**
	 * @param mixed $parent_id
	 */
	public function setParentId( \MongoId $parent_id )
	{
		$this->parent_id = $parent_id;
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
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param mixed $description
	 */
	public function setDescription( $description )
	{
		$this->description = $description;
	}
}