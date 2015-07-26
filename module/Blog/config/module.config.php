<?php
return array(
	'router' => [
		'routes' => [
			'blog' => [
				'type'          => 'Zend\Mvc\Router\Http\Literal',
				'options'       => [
					'route'    => '/blog',
					'defaults' => [
						'__NAMESPACE__' => 'Blog\Controller',
						'controller'    => 'Index',
						'action'        => 'list',
					]
				],
				'may_terminate' => 1,
				'child_routes'  => [
					'default' => [
						'type'    => 'Segment',
						'options' => [
							'route'       => '/[:controller[/:action[/:id]]]',
							'constraints' => [
								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'         => '[0-9]*',
							]
						]
					]
				]
			]
		]
	],
	'controllers' => [
		'invokables' => [
			'Blog\Controller\Index' => 'Blog\Controller\IndexController',

		]
	],
    'view_manager' => [
	    'template_path_stack' => [
		    __DIR__ . '/../view'
	    ]
    ]
);