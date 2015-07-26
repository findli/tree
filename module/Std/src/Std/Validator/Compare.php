<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 19.09.14
 * Time: 7:21
 */
namespace Std\Validator;

use Zend\Validator\AbstractValidator;

class Compare extends AbstractValidator
{
	protected $messageTemplates = array(
		// 'User with email: '%value%' already registered.'
		'error' => "Check form.",
	);
	private $params = [ ];

	public function __construct( $options = NULL )
	{
		parent::__construct( $options );
		if ( $options && is_array( $options ) ) {
			$this->params = $options[ 'params' ];
		}
	}

	public function isValid( $value )
	{
		$this->setValue( $value );
		if ( $this->params[ 'password' ]->getValue() == $this->getValue() ) {
			return TRUE;
		} else {
			$this->error( 'error' );

			return FALSE;
		};
	}
}