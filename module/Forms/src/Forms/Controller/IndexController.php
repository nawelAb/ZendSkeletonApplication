<?php 

namespace Forms\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Forms\Model\FormsModel;

use Forms\Form\FormsForm;
use Zend\Validator\File\Size; 
use Zend\Http\PhpEnvironment\Request;

use Zend\Db\TableGateway\TableGateway;
use Zend\View\Model\ViewModel;

use Forms\Model\FormsTable;

use Comments\Form\CommentsForm;
use Comments\Model\CommentsModel;
use Comments\Model\CommentsTable;

class IndexController extends AbstractActionController
{
    protected $formsTable;
    protected $commentsTable;
    protected $categoryTable;
    protected $formCommentTable;

    public function indexAction() 
    {   
        $formulaire = $this->getSelectFormsTable()->select();
        $categories = $this->getSelectCategoryTable()->select();
              // \Zend\Debug\Debug::dump($categories); die; 
        return new ViewModel(array('rowset' => $formulaire, 'categories' => $categories)); // pr afficher les data    
                                // return array(
        //     'iduser'    => $id,
        //     'user' => $this->getUserTable()->getUser($id)
        // );                  
    }   
// //////////////////////////////////////////////////////////////////:
    
    public function detailFormAction()
    {
        $id = $this->params()->fromRoute('id');
        // if (!$id) return $this->redirect()->toRoute('auth/default', array('controller' => 'admin', 'action' => 'index'));
        $unformulaire = $this->getSelectFormsTable()->select(array('id' => $id));
                $comment_id = $this->getSelectCommentsTable()->select();

        $form = new CommentsForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $comments = new CommentsModel();
            $form->setInputFilter($comments->getInputFilter());    
            $form->setData($request->getPost());
            if ($form->isValid()) {          
                $data = $form->getData();
          \Zend\Debug\Debug::dump($data); die;
                $comments->exchangeArray($data);
                $this->getCommentsTable()->saveComment($comments);          
                // return $this->redirect()->toRoute('comments/default', array('controller'=>'Index', 'action'=>'add'));
                // l id du formulairee est dans l url 
                // il faut sauvegarder id comment et id form dans formComment
                // recup id comment apres sauvegarde 
                $comment_id = $this->getSelectCommentsTable()->select();
                $ids = [$comment_id,$id];
                $commentform = new FormCommentModel();
                $commentform->exchangeArray($ids);

            }            
        }
        
        return new ViewModel(array('form'=> $form,'unformulaire' => $unformulaire, 'comment'=> $comment_id)); // pr afficher les data    
        // \Zend\Debug\Debug::dump($formulaire) ; die;
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



    public function uploadFormAction() // UploadForm
    {
        $form = new FormsForm();
        $request = $this->getRequest();    
        
        if ($request->isPost()) {
            
            $forms = new FormsModel();
            $form->setInputFilter($forms->getInputFilter());       
          
            $nonFile = $request->getPost()->toArray();
            $File    = $this->params()->fromFiles('fileUpload');
            $data = array_merge(
                 $nonFile,  
                 array('fileUpload'=> $File['name']) 
             );
            
            $form->setData($data);

            if ($form->isValid()) {
                
                $size = new Size(array('min'=>20000)); //min filesize                
                $adapter = new \Zend\File\Transfer\Adapter\Http();               
                $adapter->setValidators(array($size), $File['name']);

                // renomer le fichier en ajoutant un rand
                $destination = '.\data\UPLOADS';
                $ext = pathinfo($File['name'], PATHINFO_EXTENSION);

                $newName = md5(rand(). $File['name']) . '.' . $ext;
                $adapter->addFilter('File\Rename', array(
                     'target' => $destination . '/' . $File['name'].$newName,
                ));
                  
                
                if (!$adapter->isValid()){
                    $dataError = $adapter->getMessages();
                    $error = array();
                    foreach($dataError as $key=>$row)
                    {
                        $error[] = $row;
                    } //set formElementErrors
                    $form->setMessages(array('fileUpload'=>$error ));
                } else {
        // renomer avant de sauvegarder 
                    // $adapter->setDestination($destination);
                    if ($adapter->receive($File['name'])) { 

                        $data = $form->getData();
                        // $data = $this->prepareData($data);
                        $forms->exchangeArray($data);                        
                        $this->getFormsTable()->saveForm($forms);                    

                        echo 'forms success ';                       
                    }
                }  
            }
        }         
        return array('form' => $form);
    }
    public function getCategoriesAction()
    {
        return new ViewModel(array('rowset' => $this->getSelectCategoryTable()->select())); 
    }

    public function getFormsTable()
    {
        if (!$this->formsTable) {
            $sm = $this->getServiceLocator();
            $this->formsTable = $sm->get('Forms\Model\FormsTable');
        }
        return $this->formsTable;
    }

    public function getFormCommentTable()
    {
        if (!$this->formCommentTable) {
            $sm = $this->getServiceLocator();
            $this->formCommentTable = $sm->get('Forms\Model\FormCommentTable');
        }
        return $this->formCommentTable;
    }

    public function getSelectFormsTable()// pr l affichages des donnes 
    {        
        if (!$this->formsTable) {
            $this->formsTable = new TableGateway(
                'forms', 
                $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter')

            );
        }
        return $this->formsTable;    
    }   

    public function getSelectCategoryTable()// pr l affichages des donnes 
    {        
        if (!$this->categoryTable) {
            $this->categoryTable = new TableGateway(
                'category', 
                $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter')

            );
        }
        return $this->categoryTable;    
    }   
}