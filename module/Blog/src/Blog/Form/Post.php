<?php
/**
 * Created by PhpStorm.
 * User: Ya
 * Date: 28.04.2015
 * Time: 14:09
 */
namespace Blog\Form;

use Zend\Form\Form;
use Zend\Form\ Annotation;

/**
 * @Annotation\Name("post")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 */
class Post extends Form
{
	/**
	 * @Annotation\Type("Zend\Form\Element\Text")
	 * @Annotation\Options({"label":"Title"})
	 * @Annotation\Attributes({"required":"true"})
	 * @Annotation\Validator({"name":"StringLength", "options":{"min":2, "max":250, "encoding":"UTF-8"}})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Filter({"name":"StripTags"})
	 */
	public $title;

	/**
	 * @Annotation\Type("Zend\Form\Element\TextArea")
	 * @Annotation\Options({"label":"Text"})
	 * @Annotation\Attributes({"required":"true"})
	 * @Annotation\Validator({"name":"StringLength", "options":{"min":10, "max":250, "encoding":"UTF-8"}})
	 * @Annotation\Filter({"name":"StringTrim"})
	 * @Annotation\Filter({"name":"StripTags"})
	 */
	public $text;

	/**
	 * @Annotation\Type("Zend\Form\Element\Submit")
	 * @Annotation\Attributes({"value":"Enter"})
	 */
	public $submit;

}