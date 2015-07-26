<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AdminController extends AbstractActionController
{
	public function indexAction()
	{
		dd(__METHOD__);
	}

	function listAction()
	{
		$dm = $this->getServiceLocator()
		           ->get( 'doctrine.documentmanager.odm_default' );
		$users = $dm->createQueryBuilder( 'User\Document\User' )
		   ->find()
		   ->getQuery()
		   ->execute();
		$vm   = new ViewModel();
		$vm->setTemplate( 'user/admin/list' );
		$vm->setVariables( [
			                   'users' => $users
		                   ] );

		return $vm;
	}

	function editAction()
	{

		$mainView   = ( new ViewModel(
			[
				'title' => 'tree',
			]
		) )->setTerminal( 0 )
		   ->setTemplate( 'partial/tree' );
		$treeWidget = $this->forward()
		                   ->dispatch( 'Tree\Controller\Index', [ 'action' => 'tree'] );
		$mainView->addChild( $treeWidget, 'tree' );

		return $mainView;
	}
	function delete(){

		$vm   = new ViewModel();
		$vm->setTemplate( 'user/admin/delete' );

		return $vm;
	}
}