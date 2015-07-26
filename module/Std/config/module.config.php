<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
	'controller_plugins' => [
		'invokables' => [
			'Head' => 'Std\Controller\Plugin\Head'
		]
	],
	'module_config'      => [
		'upload_location' => getcwd() . '\public\upload',
	],
	'router'             => array(
		'routes' => array(
			'std' => array(
				'type'    => 'Zend\Mvc\Router\Http\Literal',
				//				'type'    => 'Zend\Mvc\Router\Http\Segment',
				'options' => array(
					'route'    => '/',
					'defaults' => array(
						'controller' => 'Std\Controller\Index',
						'action'     => 'index',
					),
				),
			),
			// The following is a route to simplify getting started creating
			// new controllers and actions without needing to create a new
			// module. Simply drop new controllers in, and you can access them
			// using the path /std/:controller/:action
			'std' => array(
				'type'          => 'Literal',
				'options'       => array(
					'route'    => '/std',
					'defaults' => array(
						'__NAMESPACE__' => 'Std\Controller',
						'controller'    => 'Index',
						'action'        => 'index',
					),
				),
				'may_terminate' => TRUE,
				'child_routes'  => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'       => '/[:controller[/:action]]',
							'constraints' => array(
								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults'    => array(),
						),
					),
				),
			),
		),
	),
	'service_manager'    => array(
		'abstract_factories' => array(
			'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
			'Zend\Log\LoggerAbstractServiceFactory',
		),
		'aliases'            => array(
			'translator' => 'MvcTranslator',
		),
		'factories'          => array(
			'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
		),
		'invokables'         => [
			'\Std\My\CheckRequest' => '\Std\My\CheckRequest',
		]
	),
	'translator'         => array(
//		'locale'                    => 'nl_NL',
'locale'                    => 'en_US',
'translation_file_patterns' => array(
	array(
		'type'     => 'gettext',
		'base_dir' => __DIR__ . '/../language',
		'pattern'  => '%s.mo',
	),
),
	),
	'controllers'        => array(
		'invokables' => array(
			'Std\Controller\Index' => 'Std\Controller\IndexController',
		),
	),
	'view_manager'       => array(
		'display_not_found_reason' => TRUE,
		'display_exceptions'       => TRUE,
		'doctype'                  => 'HTML5',
		'not_found_template'       => 'error/404',
		'exception_template'       => 'error/index',
		'template_map'             => array(
			'std/index/index' => __DIR__ . '/../view/std/index/index.phtml',
			'error/404'       => __DIR__ . '/../view/error/404.phtml',
			'error/index'     => __DIR__ . '/../view/error/index.phtml',
		),
		'template_path_stack'      => array(
			__DIR__ . '/../view',
		),
		'strategies' => [
			'ViewJsonStrategy',
		]
	),
	// Placeholder for console routes
	'console'            => array(
		'router' => array(
			'routes' => array(),
		),
	),
);
