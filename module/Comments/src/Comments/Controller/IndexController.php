<?php
namespace Comments\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Comments\Model\CommentsModel;
use Comments\Model\CommentsTable;

use Comments\Form\CommentsForm;


class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	$form = new CommentsForm();
		$request = $this->getRequest();
        if ($request->isPost()) {

        	$comments = new CommentsModel();
				// \Zend\Debug\Debug::dump($comments); die;
			$form->setInputFilter($comments->getInputFilter());    
			$form->setData($request->getPost());
			 if ($form->isValid()) {			 
				$data = $form->getData();
				$comments->exchangeArray($data);
				$this->getCommentsTable()->saveComment($comments);			
				return $this->redirect()->toRoute('comments/default', array('controller'=>'Index', 'action'=>'index'));					
			}			 
		}
		return new ViewModel(array('form' => $form));   
	}
	
	public function getCommentsTable()
    {
        if (!$this->getCommentsTable) {
            $sm = $this->getServiceLocator();
            $this->getCommentsTable = $sm->get('Comments\Model\CommentsTable');
        }
        return $this->getCommentsTable;
    }  
		
}