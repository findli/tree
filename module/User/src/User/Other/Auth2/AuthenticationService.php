<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 21.04.2015
 * Time: 13:43
 */

namespace User\Other\Auth2;


use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Authentication\Result;
use ZfcRbac\Identity\IdentityProviderInterface;
use ZfcRbac\Identity\IdentityInterface;

/*
 * authenticationService deal with application
 * and storage of authenticated user
 */

class AuthenticationService implements AuthenticationServiceInterface, IdentityProviderInterface
{
	protected $adapter;
	protected $storage;

	function __construct( $storage )
	{
		$this->storage = $storage;
	}

	function setAdapter( AdapterInterface $adapter )
	{
		$this->adapter = $adapter;
	}

	/**
	 * Authenticates and provides an authentication result
	 *
	 * @return Result
	 */
	public function authenticate()
	{
		$result = $this->adapter->authenticate();
		$this->storage->write( $result->getIdentity() );

		return $result;
	}

	function getAdapter()
	{
		return $this->adapter;
	}

	function getStorage()
	{
		return $this->storage;
	}

	/**
	 * Returns true if and only if an identity is available
	 *
	 * @return bool
	 */
	public function hasIdentity()
	{
		$hasIdentity = !$this->storage->isEmpty();

		return $hasIdentity;
	}

	/**
	 * Returns the authenticated identity or null if no identity is available
	 *
	 * @return mixed|null
	 */
	public function getIdentity()
	{
		$identity = $this->storage->read();

		return $identity;
	}

	/**
	 * Clears the identity
	 *
	 * @return void
	 */
	public function clearIdentity()
	{
		$this->storage->clear();
	}

}