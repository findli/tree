<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 19.09.14
 * Time: 7:21
 */
namespace Std\Validator;

use Zend\Validator\AbstractValidator;
use Zend\Db\Sql\Sql;

class True extends AbstractValidator
{
	public function isValid( $value )
	{
		return TRUE;
	}
}