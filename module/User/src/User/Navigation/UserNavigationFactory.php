<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 23.09.14
 * Time: 5:44
 */
namespace User\Navigation;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserNavigationFactory implements FactoryInterface
{
	public function createService( ServiceLocatorInterface $serviceLocator )
	{
		$navigation = new UserNavigation();

		return $navigation->createService( $serviceLocator );
	}
}