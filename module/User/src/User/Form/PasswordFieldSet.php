<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 15.11.2014
 * Time: 23:23
 */
namespace User\Form;

use User\Document\User;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class PasswordFieldSet extends Fieldset implements InputFilterProviderInterface
{
	function __construct()
	{
		parent::__construct( 'password' );
		$this->setHydrator( new ClassMethodsHydrator() )
		     ->setObject( new User() );
		$this->add(
			array(
				'name'     => 'id',
				'required' => TRUE,
				'filters'  => array(
					array( 'name' => 'Int' ),
				),
			) );
		$this->add( array(
			            'name'    => 'password',
			            'type'    => 'password',
			            'options' => array(
				            'label' => 'Password',
			            ),
		            ) );

		$this->add( array(
			            'name'    => 'confirm_password',
			            'type'    => 'password',
			            'options' => array(
				            'label' => 'Confirm password',
			            ),

		            ) );
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
			'password'         => [
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
			],
			'confirm_password' => [
				'required'   => TRUE,
				'filters'    => array(
					array( 'name' => 'StripTags' ),
					array( 'name' => 'StringTrim' ),
				),
				'validators' => array(
					[
						'name'    => 'Std\Validator\Compare',
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
			]
		];
	}

}