<?php
return array(
	'controllers' => array(
        'invokables' => array(
        	'MyAcl\Controller\Index' => 'MyAcl\Controller\IndexController',
            // 'MyAcl\Controller\MyAcl' => 'MyAcl\Controller\CommentsController',            	
        ),
	),
    'router' => array(
        'routes' => array(
			'myacl' => array(			
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/myacl',					
					'defaults' => array(
						'__NAMESPACE__' => 'MyAcl\Controller',						
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
           'layout/myacl'           => __DIR__ . '/../view/layout/index.phtml',
       ),
        'template_path_stack' => array(
            'myacl' => __DIR__ . '/../view'          
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

