<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 22.04.2015
 * Time: 3:42
 */

namespace User\Other\Auth2\UserFilter;


use ZfcRbac\Identity\IdentityInterface;

class UserStorage implements IdentityInterface
{

	private $id;
	private $email;
	private $name;
	private $lastName;
	private $roles;
	private $avatar;

	/**
	 * @param mixed $id
	 */
	public function setId( $id )
	{
		$this->id = $id;
	}

	/**
	 * @param mixed $email
	 */
	public function setEmail( $email )
	{
		$this->email = $email;
	}

	/**
	 * @param mixed $name
	 */
	public function setName( $name )
	{
		$this->name = $name;
	}

	/**
	 * @param mixed $lastName
	 */
	public function setLastName( $lastName )
	{
		$this->lastName = $lastName;
	}

	/**
	 * @param mixed $roles
	 */
	public function setRoles( $roles )
	{
		$this->roles = $roles;
	}

	/**
	 * @param mixed $avatar
	 */
	public function setAvatar( $avatar )
	{
		$this->avatar = $avatar;
	}

	function __construct( $identity )
	{
		foreach ( $identity as $k => $v ) {
			$this->{$k} = $v;
		}
	}

	function getRoles()
	{
		$roles = ( isset( $this->roles ) && is_array($this->roles)) ? $this->roles : [ 'guest' ];

		return $roles;
	}

	function getId()
	{
		return $this->id;
	}

	function getName()
	{
		return $this->name;
	}

	function getEmail()
	{
		return $this->email;
	}

	function getLastName()
	{
		return $this->lastName;
	}

	function getAvatar()
	{
		return $this->avatar;
	}

	function getAvatarOriginal()
	{
		return $this->avatarOriginal;
	}

	function getArrayCopy()
	{
		return get_object_vars($this);
	}
}