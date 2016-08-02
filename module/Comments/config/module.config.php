<?php
return array(
	'controllers' => array(
        'invokables' => array(
        	'Comments\Controller\Index' => 'Comments\Controller\IndexController',
            'Comments\Controller\Comments' => 'Comments\Controller\CommentsController',
             // 'Forms\Controller\CommentsController' => 'Forms\Controller\CommentsControllerController',	
        ),
	),
    'router' => array(
        'routes' => array(
			'comments' => array(
			// 'forms' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/comments',
					// 'route'    => '/forms',
					'defaults' => array(
						'__NAMESPACE__' => 'Comments\Controller',
						// '__NAMESPACE__' => 'Forms\Controller',
						'controller'    => 'Index',
						'action'        => 'index',
						// 'action'        => 'uploadForm',
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:controller[/:action[/:id]]]',
							'constraints' => array(
								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     	 => '[a-zA-Z0-9_-]*',
							),
							'defaults' => array(
							),
						),
					),
				),
			),			
		),
	),
    'view_manager' => array(
       'template_map' => array(
           'layout/Comments'           => __DIR__ . '/../view/layout/index.phtml',
       ),
        'template_path_stack' => array(
            'comments' => __DIR__ . '/../view'
            // 'forms' => __DIR__ . '/../view'
        ),
		
		'display_exceptions' => true,
    ),
	'service_manager' => array(
		 // 'factories' => array(
		     // 'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory', 
		// added for Authentication and Authorization. Without this each time we have to create a new instance.
		// This code should be moved to a module to allow Doctrine to overwrite it
		// ),
		
	),
);

