<?php

return array(
	'controllers' => array(
        'invokables' => array(
            'ManageAcl\Controller\Index' => 'ManageAcl\Controller\IndexController',	
        ),
	),
    'router' => array(
        'routes' => array(
			'manageAcl' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/manageAcl',
					'defaults' => array(
						'__NAMESPACE__' => 'ManageAcl\Controller',
						'controller'    => 'ManageAcl',
						// 'action'        => 'init',
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
//        'template_map' => array(
//            'layout/Auth'           => __DIR__ . '/../view/layout/Auth.phtml',
//        ),
        'template_path_stack' => array(
            'manageAcl' => __DIR__ . '/../view',
            // __DIR__ . '/../view',
        ),
		
		'display_exceptions' => true,
    ),
	'service_manager' => array(
		// added for Authentication and Authorization. Without this each time we have to create a new instance.
		// This code should be moved to a module to allow Doctrine to overwrite it
		
	),
);

