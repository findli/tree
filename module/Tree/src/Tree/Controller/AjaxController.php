<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 04.02.2015
 * Time: 6:52
 */

namespace Tree\Controller;

use Std\My\DefaultController;
use Tree\Document\Categories;
use Zend\View\Model\ViewModel;
use ZendTest\XmlRpc\Server\Exception;

class AjaxController extends DefaultController
{
	function indexAction(){
		die(__METHOD__);

	}
	function insertCategoryAction()
	{
		$postJson    = json_decode( $_POST[ 'postJson' ] );
		$dm          = $this->getServiceLocator()
		                    ->get( 'doctrine.documentmanager.odm_default' );
		$parent      = $dm->createQueryBuilder( 'Tree\Document\Categories' )
		                  ->select( [ '_id' ] )
		                  ->field( '_id' )
		                  ->equals( new \MongoId( $postJson->parentId ) )
		                  ->getQuery()
		                  ->getSingleResult();
		$newCategory = new Categories();
		$newCategory->setParentId( new \MongoId( $parent->getId() ) );
		$newCategory->setSlug( $postJson->newDocument->slug );
		$newCategory->setName( $postJson->newDocument->name );
		$newCategory->setDescription( $postJson->newDocument->description );
		$dm->persist( $newCategory );
		$dm->flush();
		$newCategory_Id = $newCategory->getId();

		$this->generateAncestors( $newCategory_Id, $parent->getId() );
		$return                       = new \stdClass();
		$return->err               = false;
		$return->_id                  = $newCategory_Id;
		$return->typeOfnewCategory_Id = gettype( $newCategory_Id );
		$return->newCAtegory_Id       = $newCategory_Id;
		echo json_encode( $return );

		return ( new ViewModel() )->setTerminal( 1 )
		                          ->setTemplate( 'tree/empty' );
	}

	function updateCategoryAction()
	{
		$postAjax = json_decode( $_POST[ 'postAjax' ] );
		$dm       = $this->getServiceLocator()
		                 ->get( 'doctrine.documentmanager.odm_default' );
		try {
			if ( !\MongoId::isValid( $postAjax->id ) ) {
				throw new \Exception();
			}
			$res = $dm->createQueryBuilder( 'Tree\Document\Categories' )
			          ->update()
			          ->field( 'slug' )
			          ->set( $postAjax->slug )
			          ->field( 'name' )
			          ->set( $postAjax->name )
			          ->field( 'description' )
			          ->set( $postAjax->description )
			          ->field( '_id' )
			          ->equals( new \MongoId( $postAjax->id ) )
			          ->getQuery()
			          ->execute();
		} catch ( \Exception $e ) {
			$res[ 'err' ] = TRUE;
		}
		$return         = new \stdClass();
		$return->err = (bool) $res[ 'err' ];
		echo json_encode( $return );

		return ( new ViewModel() )->setTerminal( 1 )
		                          ->setTemplate( 'tree\empty' );
	}

	function removeCategoryAction()
	{
		$postAjax = json_decode( $_POST[ 'postAjax' ] );
		$dm       = $this->getServiceLocator()
		                 ->get( 'doctrine.documentmanager.odm_default' );
//		try {
			$res1 = $dm->createQueryBuilder( 'Tree\Document\Categories' )
			           ->remove()
			           ->field( '_id' )
			           ->equals( new \MongoId( $postAjax->document->_id ) )
			           ->getQuery()
			           ->execute();
			$res2 = $dm->createQueryBuilder( 'Tree\Document\Categories' )
			           ->remove()
			           ->field( 'ancestors._id' )
			           ->equals( new \MongoId( $postAjax->document->_id ) )
			           ->getQuery()
			           ->execute();
//		d($res1['err']);
//		d((bool)$res1['err']);
//		d($res2['err']);
//		d((bool)$res2['err']);
//		} catch ( \Exception $e ) {
//			$res1[ 'err' ] = TRUE;
//		}
		$result      = new \stdClass();
		$result->err = ( (bool) $res1[ 'err' ] || (bool) $res2[ 'err' ] );
		echo json_encode( $result );

		return ( new ViewModel() )->setTerminal( 1 )
		                          ->setTemplate( 'tree\empty' );
	}

	function getTreeAction()
	{
		$dm                   = $this->getServiceLocator()
		                             ->get( 'doctrine.documentmanager.odm_default' );
		$home                 = $dm->createQueryBuilder( 'Tree\Document\Categories' )
		                           ->select( [ '_id', 'name', 'slug', 'description' ] )
		                           ->field( 'parent_id' )
		                           ->equals( NULL )
		                           ->hydrate( 0 )
		                           ->getQuery()
		                           ->getSingleResult();
		$tree[ 0 ]            = $home;
		$tree[ 0 ][ 'child' ] = $this->getChilds( new \MongoId( $home[ '_id' ] ) );
		echo json_encode( $tree );

		return ( new ViewModel() )->setTerminal( 1 )
		                          ->setTemplate( 'tree/empty' );
	}

	function getChilds( $_id )
	{
		$tree = [ ];
		$dm   = $this->getServiceLocator()
		             ->get( 'doctrine.documentmanager.odm_default' );
		$tmp1 = $dm->createQueryBuilder( 'Tree\Document\Categories' )
		           ->select( [ '_id', 'name', 'slug', 'description' ] )
		           ->field( 'parent_id' )
		           ->equals( $_id )
		           ->hydrate( 0 )
		           ->getQuery()
		           ->execute();
		$i    = -1;
		foreach ( $tmp1 as $v ) {
			$tree[ ++$i ]          = $v;
			$childs                = $this->getChilds( $v[ '_id' ] );
			$tree[ $i ][ 'child' ] = ( $childs ) ? $childs : [ ];
		}

		return ( $tree ) ? $tree : NULL;
	}

	public function generateAncestors( $_id, $parent_id )
	{
//		d('new doc id: ');
//		d($_id);
//		d('parent_id: ');
//		dd($parent_id);
		$ancestor_list = [ ];
		$dm            = $this->getServiceLocator()
		                      ->get( 'doctrine.documentmanager.odm_default' );
		while ( $parent = $dm->createQueryBuilder( 'Tree\Document\Categories' )
		                     ->select( [
			                               'parent_id',
			                               'ancestors',
			                               'description'
		                               ] )
		                     ->field( '_id' )
		                     ->equals( new \MongoId( $parent_id ) )
		                     ->hydrate( 0 )
		                     ->getQuery()
		                     ->getSingleResult() ) {
			$parent_id = $parent[ 'parent_id' ];

			unset( $parent[ 'ancestors' ] );
			unset( $parent[ 'parent_id' ] );
			unset( $parent[ 'description' ] );
			array_unshift( $ancestor_list, $parent );
		}
//		d('ancestor_list: ');
//		d($ancestor_list);
		$res = $dm->createQueryBuilder( 'Tree\Document\Categories' )
		          ->update()
		          ->field( 'ancestors' )
		          ->set( $ancestor_list )
		          ->field( '_id' )
		          ->equals( $_id )
		          ->getQuery()
		          ->execute();
//		d($res);
//		d($_id);
	}

}