<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 05.11.2014
 * Time: 22:48
 */
namespace Std\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\PluginInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\DispatchableInterface as Dispatchable;

class Head implements \Zend\ServiceManager\ServiceLocatorAwareInterface, PluginInterface
{
	public $sl;

	public $controller;

	/**
	 * Set the current controller instance
	 *
	 * @param  Dispatchable $controller
	 * @return void
	 */
	public function setController( Dispatchable $controller )
	{

		if(!$this->controller)
			$this->controller = $controller;
		return $this->controller;
		// TODO: Implement setController() method.
	}

	/**
	 * Get the current controller instance
	 *
	 * @return null|Dispatchable
	 */
	public function getController()
	{
		return $this->controller;
		// TODO: Implement getController() method.
	}
	/**
	 * Set service locator
	 *
	 * @param ServiceLocatorInterface $serviceLocator
	 */
	public function setServiceLocator( ServiceLocatorInterface $serviceLocator )
	{
		if(!$this->sl)
		$this->sl = $serviceLocator;
		return $this->sl;
		// TODO: Implement setServiceLocator() method.
	}

	/**
	 * Get service locator
	 *
	 * @return ServiceLocatorInterface
	 */
	public function getServiceLocator()
	{
		return $this->sl;
		// TODO: Implement getServiceLocator() method.
	}

	function HeadScript()
	{
		return $this->getServiceLocator()
		            ->getServiceLocator()
		            ->get( 'viewhelpermanager' )
		            ->get( 'HeadScript' );
	}
}