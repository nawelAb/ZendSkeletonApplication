<?php
namespace ManageAcl\Controller;



use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class IndexController extends AbstractActionController

{
    public function indexAction()
    {
        var_dump("var");
        die;
    }
 // public function __construct()
 //    {
 //        echo 'aaaaa';die;
 //        $this->addRole(new Role('Guest'));
 //        $this->addRole(new Role('User'),  'Guest');
 //        $this->addRole(new Role('Admin'), 'User');
    	
 //    	// ressources 
 //    	$this->addResource(new Resource('HomeController'));
 //        $this->addResource(new Resource('IndexController'));
 //        $this->addResource(new Resource('AdminController'));
 //        $this->addResource(new Resource('RegistrationController'));

 //        //
 //        $this->allow('Guest', 'HomeController', 'ViewHome');
 //        $this->allow('Guest', 'IndexController', ['ViewUser', 'RegisterUser']);

 //        $this->allow('User', 'HomeController', 'ViewHome');
 //        $this->allow('User', 'IndexController', ['index', 'update']);
 //        // $this->deny('User', 'IndexController', 'RegisterUser');

 //        $this->allow('Admin', 'AdminController', ['delete', 'create']);


 //        $acl = new YourAclModel();
	// 	if ($acl->isAllowed('Admin', 'AdminController','EditUser')) {
	// 	    echo 'allowed to edit user';
	// 	}



    
}