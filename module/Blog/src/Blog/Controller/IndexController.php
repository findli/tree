<?php

namespace Blog\Controller;

function test(array $var = null, Callable &$call = null){
	var_dump( $var );

	echo '<br>';
	echo '<br>';
	echo '<br>';
	echo '<br>';
	var_dump( $call );
//	var_dump( get_class( $call ) );
//	var_dump( get_class_methods( $call ) );
	var_dump( empty( $var ) );
	var_dump( empty( '' ) );
	return isset($var);
}

//$callable = function(){return '123';};
//var_dump( test(['1'], $callable) );
$var = 'wqer';
$Var = 'wQer';
//echo "\{$var\}";
//echo "{$var}";
$haystack = "1abc234";
$needle = 'abc';
$strpos = strspn( $haystack, $needle, 1, 5 );
var_dump( $strpos );
$strpos = strspn( $haystack, $needle, 1, 1 );
var_dump( $strpos );
die;
echo $strpos;
if( $strpos != false){
	echo 'Found!';
}
die;
echo "123" . "\n";
echo <<<"Text"
blabla "$var";
blabla {$Var};
Text;
echo '123' . "\n";
echo '123';

die;

const VARVAR = 123;
var_dump( VARVAR );
use Blog\Document\Post,
	Zend\Mvc\Controller\AbstractActionController,
	Zend\View\Model\ViewModel,
	Zend\Form\Annotation\AnnotationBuilder;

class IndexController extends AbstractActionController
{
	public function listAction()
	{
		echo '<pre>';
		$std = new \STDCLASS;
		$obj = [
			'foo',
			'bar',
			'baz',
		];
		$var =123;
		define( 'cInt', 123);
		define( 'cString', "string");
		$emptyString = '';
		$null = '0';
		$t = (int) '31-1';
		$q = 123 + 'a123';
//		echo `dir`;// shell_exec();
		$a='bar';

		die;
		for($i=0;$i<10;$i++){
//			echo '<br>';
//			echo '<br>';
//			echo 'i ' . $i;
//			echo '<br>';
			for($j=0;$j<3; $j++){
				if(($i + $j) % 5 == 0){
					echo '<br>';
					echo '<br>';
					echo " break $i + $j " . ($i + $j);
					echo '<br>';
					break 2;
				}
			}
		}
		die;
		switch($a){
			case (strpos($a, 'bat')):
				echo ' bat' .PHP_EOL;
			case (strpos($a, 'foo')):
				echo ' foo' .PHP_EOL;
				break;
			case (strpos($a, 'bar')):
				echo ' bar' .PHP_EOL;
//				break;
			default:
				echo ' default' .PHP_EOL;
		}
		die;
		echo "\t" . decbin(111);
		echo '<br>';
		echo "\t" . decbin(11001);
		echo '<br>';
		$x = 111 & 11001;
		echo '& = ';
		echo decbin($x);
		echo '<br>';
		echo '<br>';
		$x = 111 | 11101;
		echo '| = ';
		echo decbin($x);
		echo '<br>';
		echo '<br>';
		$x = 111 ^ 11101;
		echo '^ = ';
		echo decbin($x);
		echo '<br>';
		echo '<br>';
		$a=1;
		$b=2;
		$x = $a * pow(2, 32);
		var_dump( $x );
		die;
		echo '<br>';
		echo '<br>';
		var_dump( print_r( '123' ) );
		echo true;
		print_r( TRUE );
		echo '<br>';
		echo '<br>';
		echo print_r( '12' );
		echo '<br>';
		echo '<br>';
		die('123');
		$var = 1 .'.0'. 3;
		var_dump( is_numeric( $var ) );
		var_dump( is_bool( $var ) );
		var_dump( $var );
		die;
		settype( $var, 'string' );
		$obj1 = (object) $obj;
		var_dump( $obj1 );
		echo '<br>';
		echo '<br>';
		var_dump( get_object_vars( $obj1 ) );
		var_dump($obj1->{'1'});
		var_dump($obj1->{1});
		foreach ( $obj1 as $k => $v ) {
			var_dump( $k );
			echo '<br>';
			echo '<br>';
			var_dump( $v );
			echo '<br>';
		}

		echo '</pre>';
		$viewModel = new ViewModel();
		$viewModel->setTemplate( 'blog/index/list' );
		$viewModel->setVariables( [ 'title' => 'Posts' ] );

		return $viewModel;
	}

	function newAction()
	{
		$builder = new AnnotationBuilder();
		$form = $builder->createForm('Blog\Form\Post');
		$data = [
			'title' => 'title 1',
			'text'    => 'test 1',
			'node'    => [
				'prog',
				'frag',
				'fox',

			],
		];
		$dm = $this->getServiceLocator()
		           ->get( 'doctrine.documentmanager.odm_default' );

		if ( $this->getRequest()
		          ->isPost()
		) {
			$form->setData( $this->params()
			                     ->fromPost() );
			if ( $form->isValid() ) {
				$newPost = new Post();
				$newPost->setTitle( $data[ 'title' ] );
				$newPost->setText( $data[ 'text' ] );
				$newPost->setNode( $data[ 'node' ] );
				$dm->persist( $newPost );
				$dm->flush();
				$this->flashMessenger()
				     ->addSuccessMessage( $this->getServiceLocator()
				                               ->get( 'MVCTranslate' )
				                               ->translate( 'Post has been successfully added!' ) );

			}
		}elseif(is_numeric($this->params()->fromQuery('id'))){
			$this->redirect()
			     ->toRoute( 'post', [ 'controller' => 'index', 'action' => 'list', 'page' => 'last' ] );

		}

		$id     = $this->params()
		               ->fromQuery( 'id' );
		$id = '553fdc3f187959c02200002b';
		$result = $dm->createQueryBuilder( 'Blog\Document\Post' )
		             ->select( [ '_id', '*' ] )
		             ->field( '_id' )
		             ->equals( new \MongoId( $id ) )
		             ->getQuery()
		             ->getSingleResult();
		$form->setData( $result->getArrayCopy() );
		$vm = new ViewModel();
		$vm->setTemplate( 'blog/index/new' );
		$vm->setVariables( [
			                   'title' => 'Add post',
			                   'form'  => $form,
		                   ] );

		return $vm;
	}

	function deleteAction()
	{
		$id = (int) $this->params()
		                 ->fromQuery( 'id' );
		if ( $this->getRequest()
		          ->isPost()
		) {
			$query = '';
			$query->execute();
			$page = $this->params()
			             ->fromRoute( 'page' );
			$this->flashMessenger()
			     ->addSuccessMessage( 'Post successfully deleted!' );
			$this->redirect()
			     ->toRoute( 'posts', [ 'controller' => 'index', 'action' => 'posts', 'page' => $page ] );
		}
		$vm = new ViewModel();
		$vm->setTemplate( 'blog/index/delete' );
		$vm->setVariables( [
			                   'title' => 'Delete post',
			                   'id'    => $id
		                   ] );

		return $vm;
	}

	function editAction()
	{
		$form = '';
		if ( $this->getRequest()
		          ->isPost()
		) {
			$form->setData( $this->params()
			                     ->fromPost() );
			if ( $form->isValid() ) {
				$query = '';
				$query->save();
			}

		} else {
			$query = '';
			$post  = $query->getPost();
			$form->setData( $post );
		}
		$vm = new ViewModel();
		$vm->setVariables( [ 'form' => $form, 'title' => 'Edit post' ] );
		$vm->setTemplate( 'blog/index/edit' );

		return $vm;
	}

}

