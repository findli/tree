<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 22.04.2015
 * Time: 3:29
 */

namespace User\Other\Auth2\UserFilter;


class Filter
{

	public $storeKey = [
		'id',
		'email',
		'name',
		'lastName',
		'roles',
		'avatar',
		//		'avatarOriginal',
	];
	protected $storeKeyImages = [
		'avatar',
	];
	protected $identity;

	function __construct( $user )
	{
		$this->identity = $this->exchangeFromObject( $user );
	}

	function getObject()
	{

		$before = memory_get_usage();
		$user   = ( new UserStorage( $this->identity ) );
		$after  = memory_get_usage();
		d( ( $after - $before ) / 1000000 );

		return $user;
	}

	function exchangeFromObject( $object )
	{
		$methods = get_class_methods( $object );
		$values  = [ ];
		foreach ( $this->storeKey as $v ) {
			if ( in_array( 'get' . ucfirst( $v ), $methods ) ) {
				if ( in_array( $v, $this->storeKeyImages ) ) {
					$str = 'get' . ucfirst( $v );
					try {
						$values[ $v ] = base64_encode( $object->{$str}()
						                                      ->getFile()
						                                      ->getBytes() );
					} catch ( \Exception $e ) {
						$values[ $v ] = NULL;
					}
				} else {
					$str          = 'get' . ucfirst( $v );
					$values[ $v ] = $object->{$str}();
				}
			} else {
				$values[ $v ] = NULL;
			}
		}

		return $values;
	}
}