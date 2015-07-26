<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 17.09.2014
 * Time: 2:31
 */
namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
class PasswordForm extends Form /*implements InputFilterProviderInterface, ServiceLocatorAwareInterface*/
{
	protected $services;

	public function setServiceLocator( ServiceLocatorInterface $serviceLocator )
	{
		$this->services = $serviceLocator;
	}

	function sm( $sm )
	{
		$this->services = $sm->getServiceLocator();
	}

	public function getServiceLocator()
	{
		return $this->services;
	}

	/**
	 * Should return an array specification compatible with
	 * {@link Zend\InputFilter\Factory::createInputFilter()}.
	 *
	 * @return array
	 */
/*	public function getInputFilterSpecification()
	{

		return [
			array(
				'name'       => 'password[password]',
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
				'name'       => 'password[confirm_password]',
				'required'   => TRUE,
				'filters'    => array(
					array( 'name' => 'StripTags' ),
					array( 'name' => 'StringTrim' ),
				),
				'validators' => array(
					[
						'name'    => 'Application\Validator\Compare',
						'options' => [
							'params'           => $this->getElements(),
							'messageTemplates' => [
								'error' => "Password and password confirmation must be the same.",
							]
						]
					],
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
	}*/

	public function __construct( $name = 'user' )
	{
		// we want to ignore the name passed
		parent::__construct( $name );
		$this->setHydrator(new ClassMethodsHydrator(false))
		     ->setInputFilter(new InputFilter());

		$this->add(array(
			           'type' => 'User\Form\PasswordFieldSet',
			           'options' => array(
				           'use_as_base_fieldset' => true
			           )
		           ));
		$this->add( array(
			            'name' => 'id',
			            'type' => 'Hidden',
		            ) );

		$this->add(array(
			           'type' => 'Zend\Form\Element\Csrf',
			           'name' => 'csrf'
		           ));
		$this->add( array(
			            'name'       => 'submit',
			            'type'       => 'Submit',
			            'attributes' => array(
				            'value' => 'Enter',
				            'id'    => 'submitbutton',
			            ),
		            ) );
	}
}