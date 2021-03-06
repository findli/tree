<?php
/**
 * Created by PhpStorm.
 * Application: Ya
 * Date: 23.09.14
 * Time: 5:44
 */
namespace Application\Navigation;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DefaultBreadcrumbNavigationFactory implements FactoryInterface
{
	public function createService( ServiceLocatorInterface $serviceLocator )
	{
		$navigation = new DefaultBreadcrumbNavigation();

		return $navigation->createService( $serviceLocator );
	}
}