<?php
namespace Forms; 

use Forms\Model\FormsModel;
use Forms\Model\FormsTable;

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
                'Forms\Model\FormsTable' =>  function($sm) {
                    $tableGateway = $sm->get('FormsTableGateway');
                    $table = new FormsTable($tableGateway);
                    return $table;
                },
                'FormsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    // var_dump("expression");die;
                    $resultSetPrototype->setArrayObjectPrototype(new \Forms\Model\FormsModel()); // Notice what is set here
                    return new TableGateway('forms', $dbAdapter, null, $resultSetPrototype);
                },                
            ),
        );
    }   
}