<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

// use Zend\Permissions\Acl\Acl;
// use Zend\Permissions\Acl\Role\GenericRole as Role;
// use Zend\Permissions\Acl\Resource\GenericResource as Resource;

use Zend\Permissions\Acl\Acl as BaseAcl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

// use Zend\Session\SessionManager;
// use Zend\Session\Config\SessionConfig;
// use Zend\Session\Container;

class Module
{   
     public function initSession($config)
    {
        $sessionConfig = new SessionConfig();
        $sessionConfig->setOptions($config);
        $sessionManager = new SessionManager($sessionConfig);
        $sessionManager->start();
        Container::setDefaultManager($sessionManager);
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        // $this -> initAcl($e);
        // $e -> getApplication() -> getEventManager() -> attach('route', array($this, 'checkAcl'));
         // $this -> initAcl($e);
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $this->initSession(array(
            'remember_me_seconds' => 180,
            'use_cookies' => true,
            'cookie_httponly' => true,
        ));
     }

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



    // public function onBootstrap(MvcEvent $e) {
    //     $this -> initAcl($e);
    //     $e -> getApplication() -> getEventManager() -> attach('route', array($this, 'checkAcl'));
    // }

    // public function initAcl(MvcEvent $e) {
 
    //     $acl = new \Zend\Permissions\Acl\Acl();
    //     // $ roles = include __DIR__ . '/config/module.acl.roles.php';
    //     $allResources = array();
    //     foreach ($roles as $role => $resources) {
     
    //         $role = new \Zend\Permissions\Acl\Role\GenericRole($role);
    //         $acl -> addRole($role);
     
    //         $allResources = array_merge($resources, $allResources);     
    //         //adding resources
    //         foreach ($resources as $resource) {
    //              // Edit 4
    //              if(!$acl ->hasResource($resource))
    //                 $acl -> addResource(new \Zend\Permissions\Acl\Resource\GenericResource($resource));
    //         }
    //         //adding restrictions
    //         foreach ($resources as $resource) {
    //             $acl -> allow($role, $resource);            }
    //     }
    //     //testing
    //     //var_dump($acl->isAllowed('admin','home'));
    //     //true
     
    //     //setting to view
    //     $e -> getViewModel() ->acl = $acl; 
    // }
 
    // public function checkAcl(MvcEvent $e) {
    //     $route = $e -> getRouteMatch() -> getMatchedRouteName();
    //     //you set your role
    //     $userRole = 'guest';
     
    //     if ($e -> getViewModel() ->acl ->hasResource($route) && !$e -> getViewModel() ->acl -> isAllowed($userRole, $route)) {
    //         $response = $e -> getResponse();
    //         //location to page or what ever
    //         $response -> getHeaders() -> addHeaderLine('Location', $e -> getRequest() -> getBaseUrl() . '/404');
    //         $response -> setStatusCode(404);
 
    //     }
    // }
}
