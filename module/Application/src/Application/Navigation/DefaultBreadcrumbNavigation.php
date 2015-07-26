<?php
/**
 * Created by PhpStorm.
 * Application: Ya
 * Date: 23.09.14
 * Time: 5:45
 */
namespace Application\Navigation;

use Zend\ServiceManager\ServiceLocatorInterface;
//use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\Navigation\Service\AbstractNavigationFactory;
use Zend\Navigation\AbstractContainer;

class DefaultBreadcrumbNavigation extends AbstractNavigationFactory
{
	/**
	 * @return string
	 */
	protected function getName()
	{
		return 'default-breadcrumb';
	}

	protected function getPages( ServiceLocatorInterface $serviceLocator )
	{
		$mvcEvent = $serviceLocator->get('Application')
		                           ->getMvcEvent();

		$routeMatch = $mvcEvent->getRouteMatch();
//		d('default-breadcrumb');
//		d('d44');
//	d($routeMatch);
			return parent::getPages( $serviceLocator );
		/*$navigation = array();

		if (null === $this->pages) {
			$navigation[] = array (
				'label' => 'Admin',
				'uri'   => 'http://www.test.com'
			);

			$mvcEvent = $serviceLocator->get('Application')
				->getMvcEvent();

			$routeMatch = $mvcEvent->getRouteMatch();
			$router     = $mvcEvent->getRouter();
			$pages      = $this->getPagesFromConfig($navigation);
			$this->pages = $this->injectComponents(
				$pages,
				$routeMatch,
				$router
			);
		}

		return $this->pages;*/
	}
}