<?php
namespace User\Form;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

class LoginFilter extends InputFilter
{
	public function __construct()
	{
		$inputFactory = new InputFactory();
		$this->add( $inputFactory->createInput( array(
			                                        'name'       => 'email',
			                                        'required'   => TRUE,
			                                        'validators' => array(
				                                        array(
					                                        'name'    => 'EmailAddress',
					                                        'options' => array(
						                                        'domain' => TRUE,
					                                        ),
				                                        ),
			                                        ),
		                                        ) ) );
		$this->add( $inputFactory->createInput( array(
			                                        'name'     => 'password',
			                                        'required' => TRUE,
		                                        ) ) );
	}
}
