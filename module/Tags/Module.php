<?php
namespace Tags; 

// Table Date Gateway
use Tags\Model\TagsModel;
use Tags\Model\TagsTable;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;


class Module
{
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
                'Tags\Model\TagsTable' =>  function($sm) {
                    $tableGateway = $sm->get('TagsTableGateway');
                    $table = new TagsTable($tableGateway);
                    return $table;
                },
                'TagsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    // var_dump("expression");die;
                    $resultSetPrototype->setArrayObjectPrototype(new \Tags\Model\TagsModel()); 
                    return new TableGateway('tags', $dbAdapter, null, $resultSetPrototype);
                },                
            ),
        );
    }   
}