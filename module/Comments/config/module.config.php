<?php
return array(
	'controllers' => array(
        'invokables' => array(
        	'Comments\Controller\Index' => 'Comments\Controller\IndexController',
            'Comments\Controller\Comments' => 'Comments\Controller\CommentsController',            	
        ),
	),
    'router' => array(
        'routes' => array(
			'comments' => array(			
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/comments',					
					'defaults' => array(
						'__NAMESPACE__' => 'Comments\Controller',						
						'controller'    => 'Index',
						'action'        => 'index',						
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

