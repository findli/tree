<?php
// filename : module/User/src/User/Form/LoginForm.php
namespace User\Form;

use Zend\Form\Form;
use Zend\Form\Annotation;

/**
 * @Annotation\Name("login")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 */
class LoginForm extends Form
{
	/**
	 * @Annotation\Type("Zend\Form\Element\Email")
	 * @Annotation\Options({"label":"Email"})
	 * @Annotation\Attributes({"required":"true"})
	 * @Annotation\Validator({"name":"EmailAddress","options":{"domain":"True"}})
	 */
	public $email;

	/**
	 * @Annotation\Type("Zend\Form\Element\Password")
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":25}})
	 * @Annotation\Attributes({"required":"true"})
	 * @Annotation\Options({"label":"Password"})
	 */
	public $password;
	/**
	 * @Annotation\Type("Zend\Form\Element\Submit")
	 * @Annotation\Attributes({"value":"Login"})
	 */
	public $submit;
/*	public function __construct( $name = 'login' )
	{
		parent::__construct( $name );
		$this->setInputFilter(new LoginFilter());
		$this->setAttribute( 'method', 'post' );
		$this->setAttribute( 'enctype', 'multipart/form-data' );


		$this->add( array(
			            'name'       => 'email',
			            'attributes' => array(
				            'type'     => 'email',
				            'required' => 'required'
			            ),
			            'options'    => array(
				            'label' => 'Email',
			            ),
		            ) );

		$this->add( array(
			            'name'       => 'password',
			            'attributes' => array(
				            'type'     => 'password',
				            'required' => 'required'
			            ),
			            'options'    => array(
				            'label' => 'Password',
			            ),
		            ) );


		$this->add( array(
			            'name'       => 'submit',
			            'attributes' => array(
				            'type'  => 'submit',
				            'value' => 'Login'
			            ),
		            ) );
	}*/
}
