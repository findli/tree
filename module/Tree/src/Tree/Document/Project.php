<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 03.02.2015
 * Time: 9:14
 */

namespace Tree\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class Project
{
	/** @ODM\Id */
	private $id;

	/** @ODM\String */
	private $name;

	public function __construct($name) { $this->name = $name; }

	public function getId() { return $this->id; }

	public function getName() { return $this->name; }
	public function setName($name) { $this->name = $name; }
}