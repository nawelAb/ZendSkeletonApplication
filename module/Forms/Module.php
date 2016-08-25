<?php
namespace Forms; 

use Forms\Model\FormsModel;
use Forms\Model\FormsTable;

use Forms\Model\CategoryModel;
use Forms\Model\CategoryTable;

use Forms\Model\FormCommentModel;
use Forms\Model\FormCommentTable;

use Forms\Model\CommentsModel;
use Forms\Model\CommentsTable;

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
                // Forms
                'Forms\Model\FormsTable' =>  function($sm) {
                    $tableGateway = $sm->get('FormsTableGateway');
                    $table = new FormsTable($tableGateway);
                    return $table;
                },
                'FormsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new \Forms\Model\FormsModel()); // Notice what is set here
                    return new TableGateway('forms', $dbAdapter, null, $resultSetPrototype);
                },    

                // Category
                'Forms\Model\CategoryTable' =>  function($sm) {
                    $tableGateway = $sm->get('CategoryTableGateway');
                    $table = new CategoryTable($tableGateway);
                    return $table;
                },
                'CategoryTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    // var_dump("expression");die;
                    $resultSetPrototype->setArrayObjectPrototype(new \Forms\Model\CategoryModel()); // Notice what is set here
                    return new TableGateway('category', $dbAdapter, null, $resultSetPrototype);
                }, 

                //FormComment
                'Forms\Model\FormCommentTable' =>  function($sm) {
                    $tableGateway = $sm->get('FormCommentTableGateway');
                    $table = new FormCommentTable($tableGateway);
                    return $table;
                },
                'FormCommentTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    // var_dump("expression");die;
                    $resultSetPrototype->setArrayObjectPrototype(new \Forms\Model\FormCommentModel()); // Notice what is set here
                    return new TableGateway('form_comment', $dbAdapter, null, $resultSetPrototype);
                },

                // Comments
                'Forms\Model\CommentsTable' =>  function($sm) {
                    $tableGateway = $sm->get('CommentsTableGateway');
                    $table = new CommentsTable($tableGateway);
                    return $table; 
                },
                'CommentsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new \Forms\Model\CommentsModel()); // Notice what is set here
                    return new TableGateway('comments', $dbAdapter, null, $resultSetPrototype);
                }
            ),
        );
    }   
}