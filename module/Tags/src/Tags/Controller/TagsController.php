<?php 
// filename : module/Tags/src/Tags/Controller/IndexController.php
namespace Tags\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Tags\Model\TagsModel;

use Zend\View\Model\ViewModel;

use Tags\Form\TagsForm;
use Zend\Validator\File\Size; 

class TagsController extends AbstractActionController
{
    protected $tagsTable;
    public function addTagsAction() // UploadForm

    {  
        $form = new TagsForm();
        $request = $this->getRequest();  
                // \Zend\Debug\Debug::dump($request->isPost());die;
  
    
        if ($request->isPost()) {

            $tags = new TagsModel();
            $form->setInputFilter($tags->getInputFilter());              
            $form->setData($data);
            if ($form->isValid()) {
        
                $data = $form->getData();          
                $tags->exchangeArray($data);               
                $this->getTagsTable()->saveTag($tags);      

                echo 'tags success ';
               
        //     }
        // }         
        // return array('form' => $form);
        return $this->redirect()->toRoute('tags/default', array('controller'=>'Tags', 'action'=>'addTagsAction'));                   
            }            
        }
        return new ViewModel(array('form' => $form));
    


        // $form = new TagsForm();
        // $request = $this->getRequest();  
        
        // if ($request->isPost()) {
        //     $form->setInputFilter(new RegistrationFilter($this->getServiceLocator()));
        //     $form->setData($request->getPost());
        //      if ($form->isValid()) {             
        //         $data = $form->getData();
        //         $data = $this->prepareData($data);
        //         $auth = new Auth();
        //         $auth->exchangeArray($data);

        //         $this->getUsersTable()->saveUser($auth);
        //     /* la confirmation de l inscription ne fonctionne pas 
        //         $this->sendConfirmationEmail($auth);
        //         $this->flashMessenger()->addMessage($auth->usr_email);
        //         // \Zend\Debug\Debug::dump('Success'); die;
        //     */
        //         return $this->redirect()->toRoute('auth/default', array('controller'=>'registration', 'action'=>'registration-success'));                   
        //     }            
        // }
        // return new ViewModel(array('form' => $form));
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