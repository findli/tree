<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 23.09.14
 * Time: 5:45
 */
namespace User\Navigation;

use Zend\ServiceManager\ServiceLocatorInterface;
//use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\Navigation\Service\AbstractNavigationFactory;
use Zend\Navigation\AbstractContainer;

class UserBreadcrumbNavigation extends AbstractNavigationFactory
{
	/**
	 * @return string
	 */
	protected function getName()
	{
		return 'user-breadcrumb';
	}

	protected function getPages( ServiceLocatorInterface $serviceLocator )
	{
		$mvcEvent = $serviceLocator->get('Application')
		                           ->getMvcEvent();

		$routeMatch = $mvcEvent->getRouteMatch();
//		d('user-breadcrumb');
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