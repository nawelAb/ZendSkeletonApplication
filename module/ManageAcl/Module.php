<?php 
namespace ManageAcl;
// continuer ici 
 
use Zend\Mvc\MvcEvent;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\Permissions\Acl\Resource\GenericResource;
 
class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        // initialize ACL
        $this->initAcl($e);
        // Check ACL
        $e->getApplication()->getEventManager()->attach('route', array($this, 'checkAcl'));
    }
 
    public function initAcl(MvcEvent $e) {
 
        $acl = new Acl();
        $roles = include __DIR__ . '/config/module.roles.php';
        $allResources = array();
        foreach ($roles as $role => $resources) {
            // Add groups to the Role registry using Zend\Permissions\Acl\Role\GenericRole
            $role = new GenericRole($role);
            $acl->addRole($role);
            $allResources = array_merge($resources, $allResources);
            foreach ($resources as $resource) {
                if(!$acl->hasResource($resource))
                    $acl->addResource(new GenericResource($resource));
            }
            //adding restrictions
            foreach ($allResources as $resource) {
                $acl->allow($role, $resource);
            }
        }
        $e->getViewModel()->acl = $acl;
    }
 
    public function checkAcl(MvcEvent $e) {
        $route = $e->getRouteMatch()->getMatchedRouteName();
         
        // set user role
        $userRole = 'admin'; // $userRole = 'super_admin';
 
        if($userRole != 'super_admin') {
            if (!$e->getViewModel()->acl->isAllowed($userRole, $route)) {
                $response = $e->getResponse();
                $response->getHeaders()->addHeaderLine('Location', $e->getRequest()->getBaseUrl() . '/404');
                $response->setStatusCode(404);
 
            }
        }
 
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
}
