<?php
namespace Tags\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Tags\Model\TagsModel;
use Tags\Model\TagsTable;
use Tags\Form\TagsForm;

use Zend\Db\TableGateway\TableGateway;

class IndexController extends AbstractActionController
{
	protected $tagsTable = null;

	public function indexAction()
    {
    	$form = new TagsForm();
		$request = $this->getRequest();
        if ($request->isPost()) {
        	
        	$tags = new TagsModel();
			$form->setInputFilter($tags->getInputFilter());    
			$form->setData($request->getPost());
			 if ($form->isValid()) {			 
				$data = $form->getData();
				$tags->exchangeArray($data);
				$this->getTagsTable()->saveTag($tags);			
				return $this->redirect()->toRoute('tags/default', array('controller'=>'Index', 'action'=>'index'));					
				// \Zend\Debug\Debug::dump("eddd"); die;
			}			 
		}
		return new ViewModel(array('form' => $form));   
	}

	public function addSuccessAction()
	{
		return new ViewModel(array('form' => $form));
	}	
	
	public function updateAction()
    {
		$id = $this->params()->fromRoute('id');
		if (!$id) return $this->redirect()->toRoute('comments/default', array('controller' => 'Index', 'action' => 'index'));
		$form = new TagsForm();
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
			$this->getTagsTable()->delete(array('usr_id' => $id));
		}
		
		return $this->redirect()->toRoute('auth/default', array('controller' => 'admin', 'action' => 'index'));											
	}	

	public function getSelectTagsTable() // pr afficher les donnees depuis la bdd 
    {
        if (!$this->tagsTable) {
			$this->tagsTable = new TableGateway(
				'tags', 
				$this->getServiceLocator()->get('Zend\Db\Adapter\Adapter')

			);
		}
		return $this->tagsTable;    
    } 		

    public function getTagsTable()
    {
        if (!$this->tagsTable) {
            $sm = $this->getServiceLocator();
            $this->tagsTable = $sm->get('Tags\Model\TagsTable');
        }
        return $this->tagsTable;
    }
}