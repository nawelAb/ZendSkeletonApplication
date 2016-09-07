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

use Forms\Model\TagsModel;
use Forms\Model\TagsTable;

use Forms\Model\FormTagModel;
use Forms\Model\FormTagTable;

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
                    $resultSetPrototype->setArrayObjectPrototype(new \Forms\Model\FormsModel());  
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
                    $resultSetPrototype->setArrayObjectPrototype(new \Forms\Model\CategoryModel()); 
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
                    $resultSetPrototype->setArrayObjectPrototype(new \Forms\Model\FormCommentModel()); 
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
                    $resultSetPrototype->setArrayObjectPrototype(new \Forms\Model\CommentsModel()); 
                    return new TableGateway('comments', $dbAdapter, null, $resultSetPrototype);
                },

                // tags
                'Forms\Model\TagsTable' =>  function($sm) {
                    $tableGateway = $sm->get('TagsTableGateway');
                    $table = new TagsTable($tableGateway);
                    return $table;
                },
                'TagsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new \Tags\Model\TagsModel()); 
                    return new TableGateway('tags', $dbAdapter, null, $resultSetPrototype);
                }, 

                // formTag  
                'Forms\Model\FormTagTable' =>  function($sm) {
                    $tableGateway = $sm->get('FormTagTableGateway');
                    $table = new FormTagTable($tableGateway);
                    return $table;
                },
                'FormTagTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();                 
                    $resultSetPrototype->setArrayObjectPrototype(new \Forms\Model\FormTagModel()); 
                    return new TableGateway('form_tag', $dbAdapter, null, $resultSetPrototype);
                },
 
                           
            ),
        );
    }   
}