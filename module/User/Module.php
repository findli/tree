<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User;

use User\Other\Rbac\AuthService;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ServiceProviderInterface
{
	public function onBootstrap( MvcEvent $e )
	{
		$eventManager        = $e->getApplication()
		                         ->getEventManager();
		$moduleRouteListener = new ModuleRouteListener();
		$moduleRouteListener->attach( $eventManager );

/*		$eventManager->attach( MvcEvent::EVENT_ROUTE, [
			$e->getApplication()
			  ->getServiceManager()
			  ->get( 'Acl2' ), 'initDefault'], 1
		);*/
//$this->showRouter($e);
		$t = $e->getTarget();
		$t->getEventManager()->attach(
			$t->getServiceManager()->get('ZfcRbac\View\Strategy\RedirectStrategy')
		);

		$application        = $e->getApplication();
		$eventManager       = $application->getEventManager();
		$sharedEventManager = $eventManager->getSharedManager();
		$serviceManager     = $application->getServiceManager();
		$rbacListener       = $serviceManager->get('User\Other\Rbac\RbacListener');

		$sharedEventManager->attach(
			'Zend\View\Helper\Navigation\AbstractHelper',
			'isAllowed',
			array($rbacListener, 'accept')
		);

	}

function showRouter( MvcEvent $e){
	$eventManager        = $e->getApplication()
	                         ->getEventManager();
	$moduleRouteListener = new ModuleRouteListener();
	$moduleRouteListener->attach( $eventManager );

	$sharedEventManager = $eventManager->getSharedManager(); // The shared event manager
	$sharedEventManager->attach( __NAMESPACE__, MvcEvent::EVENT_DISPATCH, function ( $e ) {
		$controller     = $e->getTarget(); // The controller which is dispatched
		$controllerName = $controller->getEvent()
		                             ->getRouteMatch()
		                             ->getParam( 'controller' );
		$actionName     = $controller->getEvent()
		                             ->getRouteMatch()
		                             ->getParam( 'action' );
		$route = $controller->getEvent()->getRouteMatch()->getMatchedRouteName();
//		$params= $controller->getEvent()->getRouteMatch()->getParams();
//		d( $params);;
		d( 'route: ' . $route);
		d( 'controllerName: ' . $controllerName );
		d( 'actionName: ' . $actionName );

			echo $controller->layout()->getTemplate();
	} );
}
	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}

	public function getAutoloaderConfig()
	{
		return array(
			'Zend\Loader\ClassMapAutoloader' => [
				__DIR__ . '/autoload_classmap.php',
			],
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
				),
			),
		);
	}

	function getServiceConfig()
	{
		return [
			'factories' => [
//				'Zend\Authentication\AuthenticationService' => function ( $serviceManager ) {
//					return new AuthService();
//				}
			]
		];
	}

}
