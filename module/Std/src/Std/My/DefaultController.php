<?php
namespace Std\My;

use Exceptions\Http404;
use Exceptions\FileNotFound;
use Exceptions\DbError;

/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 15.11.2014
 * Time: 19:10
 */
class DefaultController extends \Zend\Mvc\Controller\AbstractActionController
{
	/**
	 * @param $expr
	 * @param $exception
	 * @param $message
	 * @throws \exceptions\Http404
	 * @throws FileNotFound
	 */
	function ensure( $expr = NULL, $exception = NULL, $message = NULL )
	{
		if ( !$expr ) {
			switch ( $exception ) {
				case 'Http404':
					throw new Http404( $message );
					break;
				case 'FileNotFound':
					throw new FileNotFound( $message );
					break;
				case 'FileNotFound':
					throw new DbError( $message );
					break;
				default:
					throw new Http404( $message );
			}
		}
	}

	function __get( $param )
	{
		$sm = $this->getServiceLocator();
		if ( $sm->has( 'User\Model\\' . $param ) ) {
			$return = $sm->get( 'User\Model\\' . $param );
		} elseif ( $sm->has( $param ) ) {
			$return = $sm->get( $param );
		} elseif ( $sm->get( 'FormElementManager' )
		              ->has( $param )
		) {
			$return = $sm->get( 'FormElementManager' )
			             ->get( $param );
		} else {
			throw new \Exception( 'requested: "' . $param . '" not found' );
		}

		return $return;

	}

	function getRoute()
	{
		return $this->getServiceLocator()
		            ->get( 'Application' )
		            ->getMvcEvent()
		            ->getRouteMatch()
		            ->getMatchedRouteName();
	}

	function getAuthorizationService()
	{
			return $this->getServiceLocator()
			                                   ->get( 'ZfcRbac\Service\AuthorizationService' );
	}
}