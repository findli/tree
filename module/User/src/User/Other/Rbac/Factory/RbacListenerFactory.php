<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 23.02.2015
 * Time: 22:48
 */

namespace User\Other\Rbac\Factory;

use User\Other\Rbac\RbacListener;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RbacListenerFactory implements FactoryInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$authorizationService = $serviceLocator->get('ZfcRbac\Service\AuthorizationService');

		return new RbacListener($authorizationService);
	}
}