<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 21.04.2015
 * Time: 13:43
 */

namespace User\Other\Auth2;


use User\Other\Auth2\UserFilter\Filter;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/*
 * adapter deal with DB for check credentials
 * and
 */

class AdapterMongoDB implements AdapterInterface, ServiceLocatorAwareInterface
{
	protected $identityProperty;
	protected $credentialProperty;
	public $identityPropertyName = 'email';
	public $credentialPropertyName = 'password';


	function setCredentials( $identityProperty, $credentialProperty )
	{
		$this->identityProperty   = $identityProperty;
		$this->credentialProperty = $credentialProperty;
	}

	/**
	 * Performs an authentication attempt
	 *
	 * @return \Zend\Authentication\Result
	 * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface If authentication cannot be performed
	 */
	public function authenticate()
	{
		$messages                    = [ ];
		$messages[ Result::FAILURE ] = "This combination of email and password aren't valid.";

		$dm     = $this->getServiceLocator()
		               ->get( 'doctrine.documentmanager.odm_default' );
		$result = $dm->createQueryBuilder( 'User\Document\User' )
		             ->field( $this->identityPropertyName )
		             ->equals( $this->identityProperty )
		             ->field( $this->credentialPropertyName )
		             ->equals( md5( $this->credentialProperty ) )
		             ->hydrate( 1 )
		             ->getQuery()
		             ->getSingleResult();
		$count  = count( $result );
		$i      = ( $count ? 1 : 0 );
		if ( $i ) {
//			$data              = $values[ 'avatarOriginal' ]->getFile()
//			                                        ->getBytes();
//			d($data->getFileName());
//			d($data->isDirty());
//			d($data->getSize());
//			dd(get_class_methods($data));
//			$value[ 'avatar' ] = base64_encode( $data );
//			echo '<br>';
//			echo '<br>';
//			echo '<br>';
//			echo '<img src="' . 'data:image/jpg;base64,' . $value['avatar'] . '" >';
//			echo '<br>';
//			echo '<br>';
//			echo '<br>';
//			dd(__FILE__);
			/*			$value = array_intersect_key( $res, array_flip( $this->storeKey ) );
						( in_array( 'id', $this->storeKey ) ) ? $value[ 'id' ] = $res[ '_id' ]->{'$id'} : '';
						if ( in_array( 'avatar', $this->storeKey ) ) {
							$avatar            = $dm->createQueryBuilder( 'User\Document\Image' )
													->field( 'id' )
													->equals( $res[ 'avatar' ][ '$id' ] )
													->getQuery()
													->getSingleResult();
							$value[ 'avatar' ] = base64_encode( $avatar->getFile()
																	   ->getBytes() );
							echo '<br>';
							echo '<br>';
							echo '<br>';
							echo '<img src="' . 'data:image/jpg;base64,' . $value['avatar'] . '" >';
							echo '<br>';
							echo '<br>';
							echo '<br>';
						}*/

			$code = Result::SUCCESS;
		} else {
			$code = Result::FAILURE;
		}
		$result   = ( $code > 0 ) ? $result : new stdClass;
		$identity = ( new Filter( $result ) )->getObject();
		$result   = new Result( $code, $identity, $messages );

		return $result;
	}

	function updateUser( $params = [ ], $id )
	{
		if ( !( $id instanceof \MongoId ) ) {
			$id = new \MongoId( $id );
		}
		if ( !count( $params ) ) {
			return;
		}
		$dm     = $this->getServiceLocator()
		               ->get( 'doctrine.documentmanager.odm_default' );
		$update = $dm->createQueryBuilder( 'User\Document\User' )
		             ->update();
		foreach ( $params as $k => $v ) {

			$update->field( $k )
			       ->set( $v );

		}
		$update->field( '_id' )
		       ->equals( $id )
		       ->getQuery();

		return $update->execute();

	}

	protected $serviceManager;

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
}