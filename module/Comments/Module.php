<?php
namespace Comments; 

use Comments\Model\CommentsModel;
use Comments\Model\CommentsTable;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module 
{

    // public function onBootstrap(MvcEvent $e)
    // {
    //     $eventManager        = $e->getApplication()->getEventManager();
    //     $moduleRouteListener = new ModuleRouteListener();
    //     $moduleRouteListener->attach($eventManager);
    // }
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
	
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                // table data Gateway
                'Comments\Model\CommentsTable' =>  function($sm) {
                    $tableGateway = $sm->get('CommentsTableGateway');
                    $table = new CommentsTable($tableGateway);
                    return $table; 
                },
                'CommentsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new \Comments\Model\CommentsModel()); // Notice what is set here
                    return new TableGateway('comments', $dbAdapter, null, $resultSetPrototype);
                }
            ),
        );
    } 





    // public function getAutoloaderConfig()
    // {
    //     return array(
    //         'Zend\Loader\ClassMapAutoloader' => array(
    //             __DIR__ . '/autoload_classmap.php',
    //         ),
    //         'Zend\Loader\StandardAutoloader' => array(
    //             'namespaces' => array(
    //                 __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
    //             ),
    //         ),
    //     );
    // }
    
    // public function getServiceConfig()
    // {
    //     return array(
    //         'factories' => array(
    //             'Album\Model\AlbumTable' =>  function($sm) {
    //                 $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    //                 $table = new AlbumTable($dbAdapter);
    //                 return $table;
    //             },
    //         ),
    //     );
    // }    
    
}