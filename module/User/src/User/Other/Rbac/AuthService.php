<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 08.02.2015
 * Time: 12:59
 */
namespace User\Other\Rbac;

use User\Document\Identity;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Authentication\Result;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;
use Zend\Stdlib\ArrayUtils;
use ZfcRbac\Identity\IdentityProviderInterface;

class AuthService extends AuthenticationService implements AuthenticationServiceInterface, ServiceLocatorAwareInterface, IdentityProviderInterface
{
	public $identityPropertyName = 'email';
	public $credentialPropertyName = 'password';
	public $identityProperty;
	public $credentialProperty;
	public $storeKey = [
		'id',
		'email',
		'name',
		'lastName',
		'aclRole',
		'avatar',
		'avatarOriginal',
	];
	protected $serviceManager;
	private $defaultPropertyName = 'user';
	private $isValid = 0;

	function editUser( $values )
	{
		$currentUser = ( new Container() )->offsetGet( $this->defaultPropertyName );
		foreach ( $values as $k => $v ) {
			$currentUser[ $k ] = $v;
		}
		( new Container() )->offsetSet( $this->defaultPropertyName, $currentUser );
	}

	/**
	 * Set service locator
	 *
	 * @param ServiceLocatorInterface $serviceLocator
	 */
	public function setServiceLocator( ServiceLocatorInterface $serviceLocator )
	{
		$this->serviceManager = $serviceLocator;
	}

	/**
	 * Get service locator
	 *
	 * @return ServiceLocatorInterface
	 */
	public function getServiceLocator()
	{
		return $this->serviceManager;
	}


	function setIdentityProperty( $identityProperty )
	{
		$this->identityProperty = $identityProperty;
	}

	function setCredentialProperty( $credentialProperty )
	{
		$this->credentialProperty = $credentialProperty;
	}

	/**
	 * Authenticates and provides an authentication result
	 *
	 * @return Result
	 */
	public function authenticate()
	{
		$dm  = $this->getServiceLocator()
		            ->get( 'doctrine.documentmanager.odm_default' );
		$res = $dm->createQueryBuilder( 'User\Document\User' )
		          ->field( $this->identityPropertyName )
		          ->equals( $this->identityProperty )
		          ->field( $this->credentialPropertyName )
		          ->equals( md5( $this->credentialProperty ) )
		          ->hydrate( 1 )
		          ->getQuery()
		          ->getSingleResult();
		$i   = $this->isValid = ( count( $res ) ? 1 : 0 );
		if ( $i ) {
			/*$value = array_intersect_key( $res, array_flip( $this->storeKey ) );
			( in_array( 'id', $this->storeKey ) ) ? $value[ 'id' ] = $res[ '_id' ]->{'$id'} : '';
			if ( in_array( 'avatar', $this->storeKey ) ) {
				$avatar            = $dm->createQueryBuilder( 'User\Document\Image' )
				                        ->field( 'id' )
				                        ->equals( $res[ 'avatar' ][ '$id' ] )
				                        ->getQuery()
				                        ->getSingleResult();
				$value[ 'avatar' ] = base64_encode( $avatar->getFile()
				                                           ->getBytes() );
			}*/
//			( new Container() )->offsetSet( $this->defaultPropertyName, $value );
			( new Container() )->offsetSet( $this->defaultPropertyName, $res );
		}

		return $i;
	}

	/**
	 * Returns true if and only if an identity is available
	 *
	 * @return bool
	 */
	public function hasIdentity()
	{
		return ( ( new Container() )->offsetExists( $this->defaultPropertyName ) );
	}

	/**
	 * Returns the authenticated identity or null if no identity is available
	 *
	 * @return mixed|null
	 */
	public function getIdentity()
	{
		$identity = ( new Container() )->offsetGet( $this->defaultPropertyName );
//		d('$identity:');
//		d($identity);
		return $identity;
	}

	/**
	 * Clears the identity
	 *
	 * @return void
	 */
	public function clearIdentity()
	{
		d(__FUNCTION__);
		( new Container() )->offsetUnset( $this->defaultPropertyName );
	}

	function isValid()
	{
		return $this->isValid;
	}
function getRoles(){
	dd(__FILE__);
	return $this->getIdentity()->getRoles();
}

}