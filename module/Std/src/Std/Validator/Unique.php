<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 19.09.14
 * Time: 7:21
 */
namespace Std\Validator;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\AbstractValidator;
use Zend\Db\Sql\Sql;

class Unique extends AbstractValidator implements ServiceLocatorAwareInterface
{
	public $sm;

	/**
	 * Set service locator
	 *
	 * @param ServiceLocatorInterface $serviceLocator
	 */
	public function setServiceLocator( ServiceLocatorInterface $serviceLocator )
	{
		return $this->sm = $serviceLocator;
	}

	/**
	 * Get service locator
	 *
	 * @return ServiceLocatorInterface
	 */
	public function getServiceLocator()
	{
		return $this->sm;
	}

	protected $messageTemplates = array(
		// 'User with email: '%value%' already registered.'
		'error' => "Check form.",
	);

	public $serviceLocator;

	public $modelInstance;
	public $field;
	public $table;
	public $elements;

	public function __construct( $options = NULL )
	{
		parent::__construct( $options );
		if ( $options && is_array( $options ) && array_key_exists( 'serviceLocator', $options ) ) {
			$this->serviceLocator = $options[ 'serviceLocator' ];
			$this->document= $options[ 'document' ];
			$this->field          = $options[ 'field' ];
			$this->elements = $options['elements'];
		}
	}

	public function isValid( $value )
	{
		return 1;
		$options = $this->elements[ $this->field ]->getOptions();
		if(isset($options['edit']) && $options['edit'] == 1){

		}
		$this->setValue( $value );

		$dbAdapter = $this->serviceLocator->get( 'Zend\Db\Adapter\Adapter' );

		$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
		$resultSetPrototype->setArrayObjectPrototype( $this->modelInstance );
		$tableGateway = new \Zend\Db\TableGateway\TableGateway( $this->table /* table name  */, $dbAdapter, NULL, $resultSetPrototype );

		$sql = new Sql( $tableGateway->getAdapter() );

		$editId = $this->getServiceLocator()
		               ->getServiceLocator()
		               ->get( 'zendmvccontrollerpluginmanager' )
		               ->getController()
			->params()
			->fromPost('id');
//		               ->getEvent()
//		               ->getRouteMatch()
//		               ->getParam( 'id' );
		if(!empty($editId)) {
			$select = $sql->select( 'user' );
			$select->where( [ 'id' => $editId ] );
			$selectString = $sql->getSqlStringForSqlObject( $select );
			$adapter      = $tableGateway->getAdapter();
			$results      = $tableGateway->getAdapter()
			                             ->query( $selectString, $adapter::QUERY_MODE_EXECUTE )
			                             ->current();
			if ( $results !== FALSE && count( $results ) ) {
				$editEmail = $results[ 'email' ];
			} else {
				throw new \Exception( 'user not found!' );
			}
			if ( $editEmail == $this->getValue() ) {
				return 1;
			}
		}
		$select = $sql->select();
		$select->from( $tableGateway->getTable() );
		$select->where( array( $this->field => $this->getValue() ) );

		$selectString = $sql->getSqlStringForSqlObject( $select );
		$adapter      = $tableGateway->getAdapter();
		$results      = $tableGateway->getAdapter()
		                             ->query( $selectString, $adapter::QUERY_MODE_EXECUTE );
		if ( $results->count() ) {
			$this->error( 'error' );

			return FALSE;
		}

		return TRUE;
	}
}