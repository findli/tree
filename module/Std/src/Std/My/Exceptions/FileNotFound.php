<?php
/**
 * Created by PhpStorm.
 * User: ya
 * Date: 1/29/14
 * Time: 3:53 PM
 */

namespace Exceptions;


class FileNotFound extends \Exception
{

	function __construct( $message = NULL )
	{
		$message = ( $message ) ? $message : '404: page not found!';
		parent::__construct( $message );
	}


} 