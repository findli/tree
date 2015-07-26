<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonTree for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Tree\Controller;

use Std\My\DefaultController;
use Tree\Document\Ancestor;
use Tree\Document\Categories;
use Zend\View\Model\ViewModel;

use Tree\Document\Category;

class IndexController extends DefaultController
{
	public function indexAction()
	{
		$dm = $this->getServiceLocator()
		           ->get( 'doctrine.documentmanager.odm_default' );


		$ancestor1 = new Ancestor();
		$ancestor1->setName( 'ancestor 1' );
		$ancestor1->setSlug( 'ancestor 1 slug' );

		$ancestor1->setId2( 4 );
		$ancestor2 = new Ancestor();
		$ancestor2->setName( 'ancestor 2' );
		$ancestor2->setSlug( 'ancestor 2 slug' );

		$ancestor2->setId2( 4 );
		$category = new Categories();
		$category->setSlug( 'slug1' );
		$category->setParentId( 1 );
		$category->setName( 'cat 1' );
		$category->setDescription( 'desc 1' );
		$category->addAncestor( $ancestor1 );
		$category->addAncestor( $ancestor2 );

		$dm->persist( $ancestor1 );
		$dm->persist( $ancestor2 );
		$dm->persist( $category );
		$dm->flush();

		echo '<pre>';
		var_dump( get_class( $dm ) );
		var_dump( get_class_methods( $dm ) );
		echo '</pre>';

		return new ViewModel();
	}

	function removeAction()
	{
		$dm = $this->getServiceLocator()
		           ->get( 'doctrine.documentmanager.odm_default' );
		$dm->createQueryBuilder( 'Tree\Document\Category' )
		   ->remove()
		   ->field( 'ancestors.id2' )
		   ->equals( 3 )
		   ->getQuery()
		   ->execute();

		return $this->getResponse();
	}

	function treeAction()
	{

		return ( new ViewModel(
			[
				'title' => 'tree',
			]
		) )->setTerminal( 0 )
		   ->setTemplate( 'partial/tree' );
	}
}