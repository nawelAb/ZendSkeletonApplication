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

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
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
    // //     $e -> getApplication() -> getEventManager() -> attach('route', array($this, 'checkAcl'));
    // }


    public function initAcl(MvcEvent $e)
    {
    // 
        // $acl = new Acl();
        //   $acl->deny();
        // // $acl->addRole(new Role('guest'));
        // $acl->addRole(new Role('user'),  'guest');
        // $acl->addRole(new Role('admin'), 'user');
        //         ////////////////# end ROLES ########################################
       
        //         ////////////////# RESOURCES ########################################
        // $acl->addResource('application'); // Application module
        // $acl->addResource('album'); // Album module
        // $acl->addResource('auth'); // Album module
        //        //////////////// # end RESOURCES ########################################
                
        //        //////////////// ################ PERMISSIONS #######################
        //         // $acl->allow('role', 'resource', 'controller:action');
                
        // // Application -------------------------->
        // $acl->allow('guest', 'auth', 'index:login');
        // $acl->allow('guest', 'auth', 'index:index');
        // // $acl->allow('guest', 'application', 'index:index');
        // // $acl->allow('guest', 'application', 'profile:index');
       
        // // Album -------------------------->
        //         $acl->allow('guest', 'auth', 'auth:index'); 
        //         $acl->allow('guest', 'auth', 'auth:login'); 
        //         // $acl->allow('guest', 'album', 'album:index'); 
        //         // $acl->allow('guest', 'album', 'album:add'); 
        //         // $acl->deny('guest', 'album', 'album:hello'); 
        //         // $acl->allow('guest', 'album', 'album:view');
        //         // $acl->allow('guest', 'album', 'album:edit'); // also allows route: zf2-tutorial.com/album/edit/1
        //         //$acl->deny('guest', 'Album', 'Album:song');
        //       ///////  ################ end PERMISSIONS #####################
                 
        // var_dump($acl->isAllowed('auth', null, 'publish'));
        //  die; 
        // $acl = new Acl();

        // $acl->addRole(new Role('guest'))
        //     ->addRole(new Role('member'))
        //     ->addRole(new Role('admin'));

        // $parents = array('guest', 'member', 'admin');
        // $acl->addRole(new Role('someUser'), $parents);

        // $acl->addResource(new Resource('someResource'));

        // $acl->deny('guest', 'someResource');
        // $acl->allow('member', 'someResource');
        //     // var_dump($acl);       die;

        // echo $acl->isAllowed('someUser', 'someResource') ? 'allowed' : 'denied';

        $acl = new Acl();

        $roleGuest = new Role('guest');
        $acl->addRole($roleGuest);
        $acl->addRole(new Role('user'), $roleGuest);
        $acl->addRole(new Role('editor'), 'user');
        // $acl->addRole(new Role('administrator'));

        // Guest may only view content
        $acl->allow($roleGuest, null, 'view');
        $acl->allow('guest', null, 'Auth:index'); 
        $acl->allow('guest', null, 'Auth:login'); 

        /*
        Alternatively, the above could be written:
        $acl->allow('guest', null, 'view');
        //*/

        // Staff inherits view privilege from guest, but also needs additional
        // privileges
        $acl->allow('auth', null, array('index', 'submit', 'login'));

        // Editor inherits view, edit, submit, and revise privileges from
        // auth, but also needs additional privileges
        // $acl->allow('editor', null, array('publish', 'archive', 'delete'));

        // Administrator inherits nothing, but is allowed all privileges
        // $acl->allow('administrator');
        

        echo $acl->isAllowed('guest', null, 'view') ?
             "allowed" : "denied guest </br>";
        // allowed

        echo $acl->isAllowed('auth', null, 'publish') ?
             "allowed" : "denied auth</br> </br>";
        // denied </br>

        echo $acl->isAllowed('auth', null, 'revise') ?
             "allowed" : "denied auth  </br>";
        // allowed

        // echo $acl->isAllowed('editor', null, 'view') ?
        //      "allowed" : "denied editor</br>";
        // // allowed because of inheritance from guest

        // echo $acl->isAllowed('editor', null, 'update') ?
        //      "allowed" : "denied editor</br>";
        // // denied because no allow rule for 'update'

        // echo $acl->isAllowed('administrator', null, 'view') ?
        //      "allowed" : "denied </br>";
        // // allowed because administrator is allowed all privileges

        // echo $acl->isAllowed('administrator') ?
        //      "allowed" : "denied </br>";
        // // allowed because administrator is allowed all privileges

        // echo $acl->isAllowed('administrator', null, 'update') ?
        //      "allowed" : "denied </br>";
    }
    //////////////////////////////:: acl //////////////////////////////////
    
    // public function onBootstrap(MvcEvent $e) {
    //     $this -> initAcl($e);
    //     $e -> getApplication() -> getEventManager() -> attach('route', array($this, 'checkAcl'));
    // }
 
    // // public function initAcl(MvcEvent $e) {
 
    // //     $acl = new \Zend\Permissions\Acl\Acl();
    // //     // $roles = $this->getDbRoles($e);
    // //     $roles = include __DIR__ . '/config/module.acl.roles.php';

    // //     $allResources = array();
    // //     foreach ($roles as $role => $resources) {
     
    // //         $role = new \Zend\Permissions\Acl\Role\GenericRole($role);
    // //         $acl -> addRole($role);
     
    // //         $allResources = array_merge($resources, $allResources);
     
    // //         //adding resources
    // //         foreach ($resources as $resource) {
    // //              // Edit 4
    // //              if(!$acl ->hasResource($resource))
    // //                 $acl -> addResource(new \Zend\Permissions\Acl\Resource\GenericResource($resource));
    // //         }
    // //         //adding restrictions
    // //         foreach ($resources as $resource) {
    // //         // foreach ($allResources as $resource) {
    // //             $acl -> allow($role, $resource);
    // //         }
    // //     }
    // //     //testing
    // //        // \Zend\Debug\Debug::dump($acl -> addResource(new \Zend\Permissions\Acl\Resource\GenericResource($resource)));        die;
    // //     // var_dump($acl->isAllowed('guest','home'));
    // //     //true
     
    // //     //setting to view
    // //     $e -> getViewModel() ->acl = $acl;
    // //     // if($this->layout()->acl->isAllowed($dato["guest"],$this->url('guest', array('action'=>'index'))))
    // //     // echo 'true';
 
    // // }
    // //////////////////////////////////////////////
    // public function initAcl(MvcEvent $e) {
 
    //     $acl = new \Zend\Permissions\Acl\Acl();
    //     $roles = include __DIR__ .'/config/module.acl.roles.php';
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
    //         // foreach ($allResources as $resource) {
    //             $acl -> allow($role, $resource);
    //         }
    //     }
    //     //testing
    //     //var_dump($acl->isAllowed('admin','home'));
    //     //true
     
    //     //setting to view
    //     $e -> getViewModel() ->acl = $acl;
     
    // }


    // ///////////////////////////////////////////////////////
 
    // public function checkAcl(MvcEvent $e) {
    //     $route = $e -> getRouteMatch() -> getMatchedRouteName();
    //     //you set your role
    //     $userRole = 'guest';
     
    //     if ($e -> getViewModel() ->acl ->hasResource($route) && !$e -> getViewModel() ->acl -> isAllowed($userRole, $route)) {
    //     // if (!$e -> getViewModel() -> acl -> isAllowed($userRole, $route)) {
    //         $response = $e -> getResponse();
    //         //location to page or what ever
    //         $response -> getHeaders() -> addHeaderLine('Location', $e -> getRequest() -> getBaseUrl() . '/404');
    //         $response -> setStatusCode(404);
     
    //     }
    // }


    // public function getDbRoles(MvcEvent $e){
    //     // I take it that your adapter is already configured
    //     $dbAdapter = $e->getApplication()->getServiceManager()->get('Zend\Db\Adapter\Adapter');
    //     $results = $dbAdapter->query('SELECT * FROM users');
    //     // making the roles array
    //     $roles = array();
    //     foreach($results as $result){
    //         $roles[$result['usrl_id']][] = $result['resource'];
    //     }
    //     return $roles;
    // }
}
