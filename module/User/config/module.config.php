<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
	'router'          => array(
		'routes' => array(
			'user' => array(
				'type'    => 'Segment',
				'options' => array(
					'route'       => '/user[/][:controller[/:action][/:id]]',
					'constraints' => array(
						'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'         => '[a-zA-Z0-9_-]*',
					),
					'defaults'    => array(
						'__NAMESPACE__' => 'User\Controller',
						'controller'    => 'Index',
						'action'        => 'index',
					),
				),
			),
			'admin' => array(
				'type'    => 'Segment',
				'options' => array(
					'route'       => '/admin/user[/:action][/:id]',
					'constraints' => array(
						'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'         => '[a-zA-Z0-9_-]*',
					),
					'defaults'    => array(
						'__NAMESPACE__' => 'User\Controller',
						'controller'    => 'Admin',
						'action'        => 'index',
					),
				),
			),
			'login' => [
				'type'          => 'Literal',
				'options'       => array(
					'route'    => '/login',
					'defaults' => array(
						'__NAMESPACE__' => 'User\Controller',
						'controller'    => 'Index',
						'action'        => 'login',
					),
				),
				'may_terminate' => 0,
			],
		),
	),
	'service_manager' => array(
		'abstract_factories' => array(
			'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
			'Zend\Log\LoggerAbstractServiceFactory',
		),
		'aliases'            => array(
			'translator'  => 'MvcTranslator',
			'authService' => 'Zend\Authentication\AuthenticationService',
		),
		'invokables'         => [
//			'Acl'  => 'Zend\Permissions\Acl\Acl',
		],
		'factories'          => [
//			'objectRepository2' => 'User\Other\Acl\ObjectRepository2',
			'navigation'        => 'Zend\Navigation\Service\DefaultNavigationFactory',
			'User\Other\Rbac\RbacListener' => 'User\Other\Rbac\Factory\RbacListenerFactory',
			'Zend\Authentication\AuthenticationService' => function(){
				$storage = new \Zend\Authentication\Storage\Session();
				$authenticationService = new \User\Other\Auth2\AuthenticationService($storage);
				return $authenticationService;
			},
			'Zend\Authentication\Adapter' => function(){
				$adapter = new \User\Other\Auth2\AdapterMongoDB();
				return $adapter;
			}
		]
	),
	'translator'      => array(
		'locale'                    => 'en_US',
		'translation_file_patterns' => array(
			array(
				'type'     => 'gettext',
				'base_dir' => __DIR__ . '/../language',
				'pattern'  => '%s.mo',
			),
		),
	),
	'controllers'     => array(
		'invokables' => array(
			'User\Controller\Index'         => 'User\Controller\IndexController',
			'User\Controller\Admin'         => 'User\Controller\AdminController',
			'User\Controller\UploadManager' => 'User\Controller\UploadManagerController',
		),
	),
	'view_manager'    => array(
		'display_not_found_reason' => TRUE,
		'display_exceptions'       => TRUE,
		'doctype'                  => 'HTML5',
		'not_found_template'       => 'error/404',
		'exception_template'       => 'error/index',
		'template_map'             => array(
			'layout/layout'    => __DIR__ . '/../view/layout/layout.phtml',
			'user/index/index' => __DIR__ . '/../view/user/index/index.phtml',
			'error/404'        => __DIR__ . '/../view/error/404.phtml',
			'error/index'      => __DIR__ . '/../view/error/index.phtml',
		),
		'template_path_stack'      => array(
			__DIR__ . '/../view',
		),
	),
	// Placeholder for console routes
	'console'         => array(
		'router' => array(
			'routes' => array(),
		),
	),
	'form_elements'   => [
		'factories' => [
			'UserForm'     => function ( $sm ) {
				$form = new \User\Form\UserForm;
				$dm   = $sm->getServiceLocator()
				           ->get( 'doctrine.documentmanager.odm_default' );
				$form->setHydrator( new \DoctrineModule\Stdlib\Hydrator\DoctrineObject( $dm ) );
				$form->setServiceLocator( $sm );

				return $form;
			},
			'UserEditForm' => function ( $sm ) {
				$form = new User\Form\UserEditForm;
				$dm   = $sm->getServiceLocator()
				           ->get( 'doctrine.documentmanager.odm_default' );
				$form->setHydrator( new \DoctrineModule\Stdlib\Hydrator\DoctrineObject( $dm ) );
				$form->setServiceLocator( $sm );
				$form->sm( $sm );

				return $form;
			},
			'PasswordForm' => function ( $sm ) {
				$form = new User\Form\PasswordForm();
				$dm   = $sm->getServiceLocator()
				           ->get( 'doctrine.documentmanager.odm_default' );
				$form->setHydrator( new \DoctrineModule\Stdlib\Hydrator\DoctrineObject( $dm ) );
				$form->setServiceLocator( $sm );
				$form->sm( $sm );

				return $form;
			},
		]
	],
	'zfc_rbac'        => [
		'identity_provider'     => 'Zend\Authentication\AuthenticationService',
		'protection_policy' => \ZfcRbac\Guard\GuardInterface::POLICY_ALLOW,
		'guest_role'            => 'guest',
		'guards'        => [
			/*'ZfcRbac\Guard\ControllerGuard' => [
				[
					'controller' => 'User\Controller\Index',
					'actions' => [
					],
					'roles' => ['*'],
				],
				[
					'controller' => 'User\Controller\Index',
					'actions' => [
						'login',
						'registration',
					],
					'roles' => ['guest'],
				],
			],*/
			'ZfcRbac\Guard\ControllerPermissionsGuard' => [
				[
					'controller' => 'Blog\Controller\List',
					'permissions' => ['user.view'],
				],
				[
					'controller' => 'Tree\Controller\Ajax',
					'permissions' => ['user.view'],
				],
				[
					'controller' => 'Application\Controller\Index',
					'actions' => [
						'index',
						'tree',
					],
					'permissions' => ['user.view'],
				],
				[
					'controller' => 'Tree\Controller\Index',
					'actions' => [
						'index',
						'ajax',
						'tree',
					],
					'permissions' => ['user.view', ],
				],
				[
					'controller' => 'User\Controller\UploadManager',
					'actions' => [
						'processUpload',
					],
					'permissions' => ['user.edit'],
				],
				[
					'controller' => 'User\Controller\Index',
				    'actions' => [
					    'index',
				    ],
					'permissions' => [ 'user.view' ],

				],
				[
					'controller' => 'User\Controller\Index',
					'actions' => [
						'login',
						'registration',
					],
					'permissions' => ['user.login', 'user.registration'],
				],
				[
					'controller' => 'User\Controller\Index',
					'actions' => [
						'edit',
						'logout',
					],
					'permissions' => ['user.edit', 'user.logout'],
				],
				[
					'controller' => 'User\Controller\Admin',
					'actions' => [
						'list',
						'archive',
					],
					'permissions' => ['user.list', 'user.archive'],
				],
			],
		    /*
		     * !!!!!
		     * controllerguard or routeguard
		     * https://github.com/ZF-Commons/zfc-rbac/blob/master/docs/04.%20Guards.md
		     */
//		    'ZfcRbac\Guard\RouteGuard' => [
//				'admin*' => [ 'admin' ],
//				'user*' => [ 'member' ],
//		    ],
		],
		'redirect_strategy' => [
			/**
			 * Enable redirection when the user is connected
			 */
			 'redirect_when_connected' => 0,

			/**
			 * Set the route to redirect when user is connected (of course, it must exist!)
			 */
//			'redirect_to_route_connected' => 'login',

			/**
			 * Set the route to redirect when user is disconnected (of course, it must exist!)
			 */
			 'redirect_to_route_disconnected' => 'login',

			/**
			 * If a user is unauthorized and redirected to another route (login, for instance), should we
			 * append the previous URI (the one that was unauthorized) in the query params?
			 */
			'append_previous_uri' => 1,

			/**
			 * If append_previous_uri option is set to true, this option set the query key to use when
			 * the previous uri is appended
			 */
			// 'previous_uri_query_key' => 'redirectTo'
		],
		'role_provider' => [
			'ZfcRbac\Role\InMemoryRoleProvider' => [
				'admin'  => [
					'children'    => [ 'member' ],
					'permissions' => [ 'user.list', 'user.archive' ]
				],
				'member' => [
					'permissions' => [ 'user.view', 'user.edit', 'user.logout']
				],
				'guest'  => [
					'permissions' => [ 'user.view', 'user.login', 'user.registration' ]
				]
			],
//			'ZfcRbac\Role\ObjectRepositoryRoleProvider' => [
//				'object_manager'     => 'doctrine.documentmanager.odm_default',
//				'class_name'         => 'User\Document\User',
//				'role_name_property' => 'roles'
//			]
		],
		'unauthorized_strategy' => [
			'template' => 'error/403'
		],
	],
	'navigation'      => array(
		'default'            => array(
			[
				'label' => 'Profile',
				'route' => 'user',
				'pages' => [
					[
						'label'      => 'Edit',
						'route'      => 'user',
						'controller' => 'index',
						'action'     => 'edit',
						'permission' => 'user.edit'
					],
					[
						'label'      => 'Registration',
						'route'      => 'user',
						'controller' => 'index',
						'action'     => 'registration',
					    'permission' => 'user.registration',
					],
					[
						'label'      => 'Logout',
						'route'      => 'user',
						'controller' => 'index',
						'action'     => 'logout',
					    'permission' => 'user.logout',
					],
					[
						'label'      => 'Login',
						'route'      => 'user',
						'controller' => 'index',
						'action'     => 'login',
					    'permission' => 'user.login',
					],
				]

			],
			[
				'label' => 'Tree',
			    'route' => 'tree',
			    'pages' => [
				    [
					    'label'      => 'Tree',
					    'route'      => 'tree',
					    'controller' => 'index',
					    'action'     => 'tree',
					    'permission' => 'user.view',
				    ],

			    ]
			],
			[
				'label' => 'Blog',
			    'route' => 'blog',
			    'pages' => [
				    [
					    'label' => 'New',
				        'route' => 'blog',
				        'controller' => 'index',
				        'action' => 'new',
				    ]
			    ]
			],
			[
				'label' => 'Admin',
			'route' => 'admin',
				'permission' => 'user.list',
			'pages' => [
				[
					'label'      => 'Users',
					'route'      => 'admin',
					'controller' => 'admin',
					'action'     => 'list',
				]
			]
		        ]
		),
		'default-breadcrumb' => array(
			[
				'label' => 'Profile',
				'route' => 'user',
				'pages' => [
					[
						'label'      => 'Edit',
						'route'      => 'user',
						'controller' => 'index',
						'action'     => 'edit',
					],
					[
						'label'      => 'Registration',
						'route'      => 'user',
						'controller' => 'index',
						'action'     => 'registration',
					],
					[
						'label'      => 'Logout',
						'route'      => 'user',
						'controller' => 'index',
						'action'     => 'logout',
					],
					[
						'label'      => 'login',
						'route'      => 'user',
						'controller' => 'index',
						'action'     => 'login',
					],
				]
			],
			[
				'label' => 'Admin',
				'route' => 'admin',
				'pages' => [
					[
						'label'      => 'Users',
						'route'      => 'admin',
						'controller' => 'admin',
						'action'     => 'list',
					]
				]
			]
		),
	)
);