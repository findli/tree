<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 28.04.2015
 * Time: 6:43
 */
namespace Blog\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document
 */
class Post
{
	/**
	 * @ODM\Id
	 */
	private $id;
	/**
	 * @ODM\String
	 */
	private $title;

	/**
	 * @ODM\String
	 */
	private $text;

	/**
	 * @ODM\Collection
	 */
	private $node;

	function __get( $k )
	{
		return $this->{$k};
	}

	function __set( $k, $v )
	{
		$this->{$k} = $v;
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
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param mixed $title
	 */
	public function setTitle( $title )
	{
		$this->title = $title;
	}

	/**
	 * @return mixed
	 */
	public function getText()
	{
		return $this->text;
	}

	/**
	 * @param mixed $text
	 */
	public function setText( $text )
	{
		$this->text = $text;
	}

	/**
	 * @return mixed
	 */
	public function getNode()
	{
		return $this->node;
	}

	/**
	 * @param mixed $node
	 */
	public function setNode( $node )
	{
		$this->node = $node;
	}

	function getArrayCopy()
	{
		return get_object_vars( $this );
	}
}