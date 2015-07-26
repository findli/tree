<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 17.09.2014
 * Time: 2:31
 */
namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserEditForm extends Form implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
	protected $services;

	public function setServiceLocator( ServiceLocatorInterface $serviceLocator )
	{
//	    var_dump(get_class($serviceLocator));
//	    die(__METHOD__);
		$this->services = $serviceLocator;
	}

	function sm( $sm )
	{
//	echo '<br>';
//	echo '<br>';
//	echo '<br>:';
//	var_dump(get_class($sm->getServiceLocator()));
		$this->services = $sm->getServiceLocator();
	}

	public function getServiceLocator()
	{
//	    echo ':';
//	    var_dump($this->services);
//	    die(__CLASS__);
		return $this->services;
	}

	/**
	 * Should return an array specification compatible with
	 * {@link Zend\InputFilter\Factory::createInputFilter()}.
	 *
	 * @return array
	 */
	public function getInputFilterSpecification()
	{

		return [
			array(
				'name'     => 'id',
				'required' => TRUE,
				'filters'  => array(
					array( 'name' => 'Int' ),
				),
			),
			array(
				'name'       => 'email',
				'required'   => TRUE,
				'filters'    => array(
					array( 'name' => 'StripTags' ),
					array( 'name' => 'StringTrim' ),
				),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 1,
							'max'      => 150,
						),
					),
					array(
						'name' => 'EmailAddress',
					),
					[
						'name'    => 'Std\Validator\Unique',
						'options' => [
							'serviceLocator'   => $this->getServiceLocator()
							                           ->getServiceLocator(),
							'messageTemplates' => [
								'error' => 'User with this email already registered.'
							],
							'field'            => 'email',
							'document'    => '\User\Document\User',
							'elements' => $this->getElements(),
						]
					],
				),
			),
			array(
				'name'       => 'name',
				'required'   => TRUE,
				'filters'    => array(
					array( 'name' => 'StripTags' ),
					array( 'name' => 'StringTrim' ),
				),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 1,
							'max'      => 150,
						),
					),
				),
			),
			array(
				'name'       => 'lastName',
				'required'   => TRUE,
				'filters'    => array(
					array( 'name' => 'StripTags' ),
					array( 'name' => 'StringTrim' ),
				),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 1,
							'max'      => 150,
						),
					),
				),
			),
		];
	}

	public function __construct( $name = 'user' )
	{
		// we want to ignore the name passed
		parent::__construct( $name );

		$this->add( array(
			            'name' => 'id',
			            'type' => 'Hidden',
		            ) );
		$this->add( array(
			            'name'    => 'email',
			            'type'    => 'Text',
			            'options' => array(
				            'label' => 'Email',
			            ),
		            ) );
		$this->add( array(
			            'name'    => 'name',
			            'type'    => 'Text',
			            'options' => array(
				            'label' => 'First name',
			            ),
		            ) );
		$this->add( array(
			            'name'    => 'lastName',
			            'type'    => 'Text',
			            'options' => array(
				            'label' => 'Last name',
			            ),
		            ) );
		$this->add( array(
			            'name'       => 'submit',
			            'type'       => 'Submit',
			            'attributes' => array(
				            'value' => 'Go',
				            'id'    => 'submitbutton',
			            ),
		            ) );
	}
}