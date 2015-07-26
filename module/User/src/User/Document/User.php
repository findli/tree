<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 03.02.2015
 * Time: 5:56
 */

namespace User\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use ZfcRbac\Identity\IdentityInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Collection;
/** @ODM\Document */
class User implements IdentityInterface, \ArrayAccess
{

	/** @ODM\Id */
	private $id;

	/** @ODM\String */
	private $name;
	/** @ODM\ReferenceOne(targetDocument="User\Document\Image") */
	private $avatar;
	/** @ODM\ReferenceOne(targetDocument="User\Document\Image") */
	private $avatarOriginal;

	/** @ODM\String */
	private $lastName;

	/** @ODM\String */
	private $password;

	/** @ODM\String */
	private $confirmPassword;

	/** @ODM\String */
	private $passwordEncripted;

	/** @ODM\Collection */
	private $roles = [];

	/** @ODM\string */
	private $email;

	/**
	 * @return mixed
	 */
	public function getConfirmPassword()
	{
		return $this->confirmPassword;
	}

	/**
	 * @param mixed $confirmPassword
	 */
	public function setConfirmPassword( $confirmPassword )
	{
		$this->confirmPassword = $confirmPassword;
	}
	/**
	 * @return mixed
	 */
	public function getAvatar()
	{
		return $this->avatar;
	}

	/**
	 * @param mixed $avatar
	 */
	public function setAvatar( Image $avatar)
	{
		$this->avatar= $avatar;
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
	public function getLastName()
	{
		return $this->lastName;
	}

	/**
	 * @param mixed $lastName
	 */
	public function setLastName( $lastName )
	{
		$this->lastName = $lastName;
	}

	/**
	 * @return mixed
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param mixed $password
	 */
	public function setPassword( $password )
	{
		$this->password = $password;
	}

	/**
	 * @return mixed
	 */
	public function getPasswordEncripted()
	{
		return $this->passwordEncripted;
	}

	/**
	 * @param mixed $password_encripted
	 */
	public function setPasswordEncripted( $passwordEncripted )
	{
		$this->passwordEncripted = $passwordEncripted;
	}

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
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param mixed $slug
	 */
	public function setEmail( $email )
	{
		$this->email = $email;
	}

	/**
	 * @return mixed
	 */
	public function getRoles()
	{
		return $this->roles;
	}

	/**
	 * @param mixed $aclRole
	 */
	public function setRoles( $roles )
	{
		$this->roles = $roles;
	}

	/**
	 * @return mixed
	 */
	public function getAvatarOriginal()
	{
		return $this->avatarOriginal;
	}

	/**
	 * @param mixed $avatarOriginal
	 */
	public function setAvatarOriginal( Image $avatarOriginal )
	{
		$this->avatarOriginal = $avatarOriginal;
	}

	function exchangeFromArray( $params )
	{
		( isset( $params[ 'name' ] ) ) ? $this->setName( $params[ 'name' ] ) : '';
		( isset( $params[ 'lastName' ] ) ) ? $this->setLastName( $params[ 'lastName' ] ) : '';
		( isset( $params[ 'email' ] ) ) ? $this->setEmail( $params[ 'email' ] ) : '';
		( isset( $params[ 'password' ] ) ) ? $this->setPassword( md5( $params[ 'password' ] ) ) : '';
		( isset( $params[ 'password' ] ) ) ? $this->setPasswordEncripted( $params[ 'password' ] ) : '';
		( isset( $params[ 'aclRole' ] ) ) ? $this->setAclRole( $params[ 'aclRole' ] ) : '';
		( isset( $params[ 'avatar' ] ) ) ? $this->setAvatar( $params[ 'avatarSrc' ] ) : '';
		( isset( $params[ 'avatarOriginal' ] ) ) ? $this->setAvatarOriginal( $params[ 'avatarOriginal' ] ) : '';
	}
	/*
	 * Ya_manual
	 * need for bind()
	 */
	function getArrayCopy(){
		return get_object_vars($this);
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Whether a offset exists
	 * @link http://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param mixed $offset <p>
	 * An offset to check for.
	 * </p>
	 * @return boolean true on success or false on failure.
	 * </p>
	 * <p>
	 * The return value will be casted to boolean if non-boolean was returned.
	 */
	public function offsetExists( $offset )
	{
		return (isset($this->{$offset}))?true:false;
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Offset to retrieve
	 * @link http://php.net/manual/en/arrayaccess.offsetget.php
	 * @param mixed $offset <p>
	 * The offset to retrieve.
	 * </p>
	 * @return mixed Can return all value types.
	 */
	public function offsetGet( $offset )
	{
		return $this->{$offset};
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Offset to set
	 * @link http://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset <p>
	 * The offset to assign the value to.
	 * </p>
	 * @param mixed $value <p>
	 * The value to set.
	 * </p>
	 * @return void
	 */
	public function offsetSet( $offset, $value )
	{
		if(method_exists($this, 'set' . ucfirst($offset)))
		$this->{'set' . ucfirst($offset)}($value);
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Offset to unset
	 * @link http://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset <p>
	 * The offset to unset.
	 * </p>
	 * @return void
	 */
	public function offsetUnset( $offset )
	{
		if(method_exists($this, 'set' . ucfirst($offset)))
		$this->{'set' . ucfirst($offset)}(NULL);
	}

}