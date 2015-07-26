<?php
/**
 * Created by PhpStorm.
 * User: ya
 * Date: 12/23/13
 * Time: 5:24 AM
 */
/*
 * in_array for multidimensional arrays
 */
function in_array_r($needle, $haystack, $strict = false) {
	foreach ($haystack as $item) {
		if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
			return true;
		}
	}

	return false;
}
/*
 * desc: die call in class;
 */
function dc($stdOut = ''){
	echo '<pre>';
	echo 'std out: ';
	var_export($stdOut);
	echo '</pre>';
	die();
}
/**
 * Obtains an object class name without namespaces
 */
function get_real_class($obj)
{
	$classname = get_class($obj);

	if (preg_match('@\\\\([\w]+)$@', $classname, $matches)) {
		$classname = $matches[1];
	}

	return $classname;
}

function d($var, $preText = "")
{
	echo '+++++++++++++++++ start - global.php -> d($var)' . PHP_EOL;
	echo '<pre>';
	echo ($preText)?$preText . '<br>':'';
	var_dump($var);
	echo '</pre>';
	echo '----------------- end - global.php -> d($var)' . PHP_EOL;
}


function p($var)
{
	echo '+++++++++++++++++ start - global.php -> d($var)' . PHP_EOL;
	echo '<pre>';
	print_r($var);
	echo '</pre>';
	echo '----------------- end - global.php -> d($var)' . PHP_EOL;
}

function dd($var, $preText = "")
{
	echo '+++++++++++++++++ start - global.php -> dd($var) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ' . PHP_EOL;
	echo ($preText)?$preText . '<br>':'';
	d($var);
	die('----------------- &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; end - global.php -> dd($var)');
}

function pp($var)
{
	echo '+++++++++++++++++ start - global.php -> dd($var) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ' . PHP_EOL;
	p($var);
	die('----------------- &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; end - global.php -> dd($var)');
}

function br(){
    echo "<br>";
}