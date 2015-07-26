<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User\Controller;

use Std\My\DefaultController;
use User\Document\Image;
use User\Form\LoginForm;
use User\Other\Acl\Storage;
use User\Other\Auth2\Adapter;
use User\Other\Auth2\StorageSession;
use Zend\Authentication\AuthenticationService;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use User\Document\User;
use Zend\Form\Annotation\AnnotationBuilder;

class test
{
	public $name;
	public $email;
}

class IndexController extends DefaultController
{
	public function indexAction()
	{
		$this->redirect()->toRoute('user', [ 'controller' => 'index', 'action' => 'edit']);
	}

	function viewAction()
	{
		$dm   = $this->getServiceLocator()
		             ->get( 'doctrine.documentmanager.odm_default' );
		$id   = $this->params()
		             ->fromRoute( 'id' );
		$user = $dm->createQueryBuilder( '\User\Document\User' )
		           ->field( '_id' )
		           ->equals( new \MongoId( $id ) )
		           ->getQuery()
		           ->getSingleResult();
		$vm   = new ViewModel();
		$vm->setTemplate( 'user/index/view' );
		$vm->setVariables( [
			                   'user' => $user,
		                   ] );

		return $vm;
	}

	function registrationAction()
	{
		$form = $this->getServiceLocator()
		             ->get( 'FormElementManager' )
		             ->get( 'UserForm' );
		if ( $this->getRequest()
		          ->isPost()
		) {
			$form->setData( $this->getRequest()
			                     ->getPost() );
			if ( $form->isValid() ) {
				$user = new User();
				$user->exchangeFromArray( $this->getRequest()
				                               ->getPost() );
				$user->setRoles( [ 'admin' ] );
				$dm     = $this->getServiceLocator()
				               ->get( 'doctrine.documentmanager.odm_default' );
				$image  = new Image;
				$image2 = new Image;
				$user->setAvatar( $image );
				$user->setAvatarOriginal( $image2 );
				$dm->persist( $image );
				$dm->persist( $image2 );
				$dm->persist( $user );
				$dm->flush();
				$this->redirect()
				     ->toRoute( 'user', [
					     'action' => 'view',
					     'id'     => $user->getId(),
				     ] );
			}
		}

		$vm   = new ViewModel();
		$vm->setTemplate( 'user/index/registration' );
		$vm->setVariables( [
			                   'title' => 'Registration',
			                   'form'  => $form,
		                   ] );

		return $vm;
	}

	public function editAction()
	{
		$request = $this->getRequest();
		$post    = $request->getPost();
		if ( !$this->getRequest()
		           ->isPost()
		) {
			$post[ 'id' ] = ( $this->params()
			                       ->fromRoute( 'id' ) ) ? $this->params()
			                                                    ->fromRoute( 'id' ) : $this->identity()
			                                                                               ->getId();
		}
		/*if ( !( ( $this->getServiceLocator()
		               ->get( 'Acl' )
		               ->isAllowed( $this->identity()[ 'aclRole' ], 'privateProfile' )
				&& $this->identity()[ 'id' ] == $post[ 'id' ] )
			|| $this->identity()[ 'aclRole' ] == 'adminMain' )
		) {
			$this->redirect()
			     ->toRoute( 'user', [ 'controller' => 'index', 'action' => 'logout' ] );
		}*/

		$sm                 = $this->getServiceLocator();
		$formElementManager = $sm->get( 'FormElementManager' );
		$UserEditForm       = $formElementManager
			->get( 'UserEditForm' );
		$UserEditForm->get( 'submit' )
		             ->setAttribute( 'value', 'Edit' );
		$UserEditForm->remove( 'password' );
		$UserEditForm->remove( 'confirm_password' );

		if ( !$this->getRequest()
		           ->isPost()
		) {
			$UserEditForm->setData( $this->identity()
			                             ->getArrayCopy() );
		}

		$formPassword = $formElementManager->get( 'PasswordForm' );
		$formPassword->setData( $post );
		if ( $request->isPost() && isset( $post[ 'password' ] ) ) {
			$formPassword = $this->proceedPasswordForm( $formPassword );
			if ( $formPassword->hasValidated() ) {
				$msg = 'Password has been successfully updated!';
			}
			$UserEditForm->setData( $this->identity()
			                             ->getArrayCopy() );
		} elseif ( $request->isPost() ) {
			$UserEditForm = $this->proceedUserEditForm( $UserEditForm );
			if ( $UserEditForm->hasValidated() ) {

				if ( $this->getRoute() == 'admin' ) {
					$toRoute         = [ 'controller' => 'user', 'action' => 'edit' ];
					$toRoute[ 'id' ] = $post[ 'id' ];
					$this->redirect()
					     ->toRoute( $this->getRoute(), $toRoute );
				}
				$msg = 'User updated!';

				$authService = $this->getServiceLocator()
				                    ->get( 'authService' );
				$authService->editUser( $post );
			}
		}
		$this->Head()
		     ->HeadScript()
		     ->appendFile(
			     '/js/plupload/js/plupload.full.min.js',
			     'text/javascript'
		     );
		$vm   = new ViewModel();
		$vm->setTemplate( 'user/index/edit' );
		$vm->setVariables( [
			                   'title'        => 'Edit user profile',
			                   'id'           => $this->params()
			                                          ->fromRoute( 'id' ),
			                   'formUserEdit' => $UserEditForm,
			                   'formPassword' => $formPassword,
			                   'auth'         => [ 'user' => $this->identity() ],
			                   'msg'          => ( isset( $msg ) ? $msg : '' ),
		                   ] );

		return $vm;
	}

	function proceedPasswordForm( $formPassword )
	{
		$post = $this->params()
		             ->fromPost();
		$formPassword->setData( $post );
		if ( $formPassword->isValid() ) {
			$dm = $this->getServiceLocator()
			           ->get( 'doctrine.documentmanager.odm_default' );
			$dm->createQueryBuilder( 'User\Document\User' )
			   ->update()
			   ->field( 'password' )
			   ->set( md5( $post[ 'password' ][ 'password' ] ) )
			   ->field( 'passwordEncripted' )
			   ->set( $post[ 'password' ][ 'password' ] )
			   ->field( '_id' )
			   ->equals( new \MongoId( $post[ 'id' ] ) )
			   ->getQuery()
			   ->execute();

		}

		return $formPassword;

	}

	function proceedUserEditForm( $formUserEdit )
	{
		$post = $this->params()
		             ->fromPost();

		$formUserEdit->setData( $post );

		if ( $formUserEdit->isValid() ) {
			$dm = $this->getServiceLocator()
			           ->get( 'doctrine.documentmanager.odm_default' );
			$dm->createQueryBuilder( 'User\Document\User' )
			   ->update()
			   ->field( 'email' )
			   ->set( $post[ 'email' ] )
			   ->field( 'name' )
			   ->set( $post[ 'name' ] )
			   ->field( 'lastName' )
			   ->set( $post[ 'lastName' ] )
			   ->field( '_id' )
			   ->equals( new \MongoId( $post[ 'id' ] ) )
			   ->getQuery()
			   ->execute();

		}

		return $formUserEdit;

	}

	public function loginAction()
	{
		if ( $this->identity() ) {
//			dd($this->identity());
//			$this->redirect()->toRoute('user',['controller' => 'user', 'action' => 'profile']);
		}
		$builder   = new AnnotationBuilder();
		$loginForm = $builder->createForm( 'User\Form\LoginForm' );
		if ( $this->getRequest()
		          ->isPost()
		) {
			$data = $this->getRequest()
			             ->getPost();
			$loginForm->setData( $data );
			if ( $loginForm->isValid() ) {

				$authService = $this->getServiceLocator()
				                    ->get( 'Zend\Authentication\AuthenticationService' );
				$adapter     = $this->getServiceLocator()
				                    ->get( 'Zend\Authentication\Adapter' );
				$adapter->setCredentials( $data[ 'email' ], $data[ 'password' ] );
				$authService->setAdapter( $adapter );
				$result = $authService->authenticate();
				if ( $result->isValid() ) {
					$this->flashMessenger()
					     ->addSuccessMessage( 'You are now logged in.' );
//					d( get_class( $this->identity() ) );
//					d( $this->identity());
//					d( $this->identity()
//					        ->getRoles() );
//					dd( __FUNCTION__ );

					return $this->redirect()
					            ->toRoute( 'user', [
						            'controller' => 'index',
						            'action'     => 'edit',
					            ] );
				} else {
					$messages = $result->getMessages();
					$error    = ( count( $messages ) ) ? $messages[ $result->getCode() ] : "";
				}
			}
		}
		$vm   = new ViewModel();
		$vm->setTemplate( 'user/index/login' );
		$vm->setVariables( array(
			                   'loginForm' => $loginForm,
			                   'error'     => ( isset( $error ) ? $error : '' ),
		                   ) );

		return $vm;
	}

	public function logoutAction()
	{
		$this->getServiceLocator()
		     ->get( 'authService' )
		     ->clearIdentity();
//d('$this->identity():');
//dd($this->identity());
		$this->flashMessenger()
		     ->addInfoMessage( 'You are now logged out.' );

		return $this->redirect()
		            ->toRoute( 'user', array(
			            'controller' => 'index',
			            'action'     => 'login'
		            ) );
	}

}