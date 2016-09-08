<?php
namespace MyAcl\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;


class IndexController extends AbstractActionController
{

	public function myAclAction()
    {
		return new ViewModel();
	}	

	public function indexAction()
    {

    		$acl = new Acl();

			$acl->addRole(new Role('guest'))
			    ->addRole(new Role('member'))
			    ->addRole(new Role('admin'));

			$parents = array('guest', 'member', 'admin');
			$acl->addRole(new Role('someUser'), $parents);

			// $acl->addResource(new Resource('someResource'));

			// $acl->deny('guest', 'someResource');
			// $acl->allow('member', 'someResource');
			$acl->addResource(new Resource('login'));
			
			$acl->deny('guest', 'login');
			$acl->allow('member', 'login');

			echo $acl->isAllowed('someUser', 'login') ? 'allowed' : 'denied';


			// $roleGuest = new Role('guest');
			// $acl->addRole($roleGuest);

			// Staff inherits from guest
			// ***$acl->addRole(new Role('staff'), $roleGuest);

			/*
			Alternatively, the above could be written:
			$acl->addRole(new Role('staff'), 'guest');
			*/

    	
			// Editor inherits from staff
			// $acl->addRole(new Role('editor'), 'staff');

			// // Administrator does not inherit access controls
			// $acl->addRole(new Role('administrator'));

			// $acl->allow('gest', null, array('login'));
			// echo 'guest view'.$acl->isAllowed('guest', null, 'view') ?
			//      "allowed" : "denied";
			//      die;

			// $acl->allow('staff', null, array('edit', 'submit', 'revise'));

			// // Editor inherits view, edit, submit, and revise privileges from
			// // staff, but also needs additional privileges
			// $acl->allow('editor', null, array('publish', 'archive', 'delete'));

			// // Administrator inherits nothing, but is allowed all privileges
			// $acl->allow('administrator');
			// // allowed

			// echo $acl->isAllowed('guest', null, 'view') ?
			//      "allowed" : "denied";

			// echo $acl->isAllowed('staff', null, 'publish') ?
			//      "allowed" : "denied";
			// // denied

			// echo $acl->isAllowed('staff', null, 'revise') ?
			//      "allowed" : "denied";
			// // allowed

			// echo $acl->isAllowed('editor', null, 'view') ?
			//      "allowed" : "denied";
			// // allowed because of inheritance from guest

			// echo $acl->isAllowed('editor', null, 'update') ?
			//      "allowed" : "denied";
			// // denied because no allow rule for 'update'

			// echo $acl->isAllowed('administrator', null, 'view') ?
			//      "allowed" : "denied";
			// // allowed because administrator is allowed all privileges

			// echo $acl->isAllowed('administrator') ?
			//      "allowed" : "denied";
			// // allowed because administrator is allowed all privileges

			// echo $acl->isAllowed('administrator', null, 'update') ?
			//      "allowed" : "denied";
			// // allowed because administrator is allowed all privileges
			var_dump("var");   die;
					
	}	

	
}
