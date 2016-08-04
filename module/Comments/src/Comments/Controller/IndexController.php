<?php
namespace Comments\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Comments\Model\CommentsModel;
use Comments\Model\CommentsTable;
use Zend\Db\TableGateway\TableGateway;

use Comments\Form\CommentsForm;

class IndexController extends AbstractActionController
{
	protected $commentsTable = null;

	public function addAction()
    {
				// \Zend\Debug\Debug::dump($this->getCommentsTable()->fetchAll()); die;
    	$form = new CommentsForm();
		$request = $this->getRequest();
        if ($request->isPost()) {
        	
        	$comments = new CommentsModel();
			$form->setInputFilter($comments->getInputFilter());    
			$form->setData($request->getPost());
			 if ($form->isValid()) {			 
				$data = $form->getData();
				$comments->exchangeArray($data);
				$this->getCommentsTable()->saveComment($comments);			
				return $this->redirect()->toRoute('comments/default', array('controller'=>'Index', 'action'=>'add'));

			}			 
		}
		return new ViewModel(array('form' => $form));   
	}

	public function indexAction() 
    { 
		return new ViewModel(array('rowset' => $this->getSelectCommentsTable()->select())); // pr afficher les data 
		// array('rowset' => $this->getCommentsTable()->select());					
	}
	

	public function addSuccessAction()
	{
		return new ViewModel(array('form' => $form));
	}	
	
	public function updateAction()
    {
		$id = $this->params()->fromRoute('id');
		if (!$id) return $this->redirect()->toRoute('comments/default', array('controller' => 'Index', 'action' => 'index'));
		$form = new CommentsForm();
		$request = $this->getRequest();

        if ($request->isPost()) {
			$form->setInputFilter(new UserFilter());
			$form->setData($request->getPost());
			 if ($form->isValid()) {
				$data = $form->getData();
				unset($data['submit']);
				if (empty($data['usr_registration_date'])) $data['usr_registration_date'] = '2013-07-19 12:00:00';
				$this->getUsersTable()->update($data, array('usr_id' => $id));
				return $this->redirect()->toRoute('auth/default', array('controller' => 'admin', 'action' => 'index'));													
			}			 
		}
		else {
			$form->setData($this->getUsersTable()->select(array('usr_id' => $id))->current());			
		}
		
		return new ViewModel(array('form' => $form, 'id' => $id));
	}

    public function deleteAction()
    {
		$id = $this->params()->fromRoute('id');
		if ($id) {
			$this->getCommentsTable()->delete(array('usr_id' => $id));
		}
		
		return $this->redirect()->toRoute('auth/default', array('controller' => 'admin', 'action' => 'index'));											
	}	

	public function getSelectCommentsTable()// pr l affichages des donnes 
    {        
    	if (!$this->commentsTable) {
			$this->commentsTable = new TableGateway(
				'comments', 
				$this->getServiceLocator()->get('Zend\Db\Adapter\Adapter')

			);
		}
		return $this->commentsTable;	
    } 	

    public function getCommentsTable()
    {
        if (!$this->commentsTable) {
            $sm = $this->getServiceLocator();
            $this->commentsTable = $sm->get('Comments\Model\CommentsTable');
        }
        return $this->commentsTable;
    }
}