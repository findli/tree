<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Std\Controller;

use Std\My\DefaultController;
use Zend\View\Model\ViewModel;

class IndexController extends DefaultController
{
	public function indexAction()
	{
	    die(__METHOD__);
		return new ViewModel();
	}

	function phpinfoAction()
	{
		echo phpinfo();

		return ( new ViewModel() )->setTerminal( 1 );
	}

	function getAction()
	{
		d( $this->getServiceLocator()
		        ->get( 'Application' )
		        ->getServiceManager()
		        ->getRegisteredServices() );

		return $this->getResponse();
	}
}
