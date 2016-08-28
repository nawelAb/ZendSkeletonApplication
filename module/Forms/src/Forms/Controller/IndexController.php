<?php 

namespace Forms\Controller;

use Zend\Mvc\Controller\AbstractActionController;

use Zend\Validator\File\Size; 
use Zend\Http\PhpEnvironment\Request;

use Zend\Db\TableGateway\TableGateway;
use Zend\View\Model\ViewModel;

use Forms\Form\FormsForm;

use Forms\Model\FormsTable;
use Forms\Model\FormsModel;

// comments
use Forms\Form\CommentsForm;
use Forms\Model\CommentsModel;
use Forms\Model\CommentsTable;

// tags
use Forms\Form\TagsForm;
use Forms\Model\TagsModel;
use Forms\Model\TagsTable;


use Forms\Model\FormCommentModel;
use Forms\Model\FormTagModel;

use Forms\Form\CategoryForm;
use Forms\Model\CategoryModel;
use Forms\Model\CategoryTable;
// use Comments\Form\CommentsForm;
// use Comments\Model\CommentsModel;
// use Comments\Model\CommentsTable;*
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;

use Zend\Db\Adapter\Adapter;

class IndexController extends AbstractActionController
{
    protected $formsTable;
    protected $commentsTable;
    protected $categoryTable;
    protected $formCommentTable;
    protected $tagsTable;
    protected $formTagTable;
    protected $CategoryTable;

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
////////////////////////////////////////Forms//////////////////////////////////////////////////////////  

     // UploadForm
    public function uploadFormAction() 
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

    // liste des formulaires 
    public function listFormAction()
    {
        $list = $this->getSelectFormsTable()->select(array('stat' => 1));
        $categories = $this->getSelectCategoryTable()->select();
        return new ViewModel(array('rowset' => $list, 'categories' => $categories)); 
    }

    // les commentaires d'un formulaire  
    public function detailFormAction() 
    {
        $formId = $this->params()->fromRoute('id');
        
        // if (!$id) return $this->redirect()->toRoute('auth/default', array('controller' => 'admin', 'action' => 'index'));
        $unformulaire = $this->getSelectFormsTable()->select(array('id' => $formId));
        // $comment = $this->getSelectCommentsTable()->select();
     
        // ajout d un commentauire 
        $form = new CommentsForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
        }
        
        return new ViewModel(array('form'=>$form,  'unformulaire' => $unformulaire,  'form_id'=>$formId)); // pr afficher les data    
        // \Zend\Debug\Debug::dump($formulaire) ; die;
         
        
         
    }

     public function getFormsTable()
    {
        if (!$this->formsTable) {
            $sm = $this->getServiceLocator();
            $this->formsTable = $sm->get('Forms\Model\FormsTable');
        }
        return $this->formsTable;
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
   
////////////////////////////////////////Comments//////////////////////////////////////////////////////////

    //list form avec  une requete where stat = 0 
    public function adminListFormAction()
    {
       $list = $this->getSelectFormsTable()->select(array('stat' => 0));
       // $categories = $this->getSelectCategoryTable()->select();
        return new ViewModel(array('rowset' => $list)); 
    }

    public function adminDetailFormAction() 
    {
        $formId = $this->params()->fromRoute('id');        
        // if (!$id) return $this->redirect()->toRoute('auth/default', array('controller' => 'admin', 'action' => 'index'));
        $unformulaire = $this->getSelectFormsTable()->select(array('id' => $formId));

        // category 
        $categories = $this->getSelectCategoryTable()->select();
  
/* la requete sql permet de recuperer les id des tags mais je n arrive pas a les afficher 
        // $TAGS = $this->getFormsTable()->formTagGet();
        $select = new Select();
        $select->from('form_tag')->columns(array('tag_id'))
            ->join('forms', 'form_tag.form_id = forms.id', array(), Select::JOIN_LEFT)
            ->join('comments', 'form_tag.tag_id = comments.id', array(), Select::JOIN_LEFT)
            ->where('forms.id ='.$formId);
        $select->getSqlString();
        return $this->tableGateway->selectWith($select);
        // \Zend\Debug\Debug::dump($select->getSqlString()); die;
*/      
     
        // ajout d un tag
        $form = new TagsForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
        }        
        return new ViewModel(array('form'=>$form,'unformulaire' => $unformulaire, 'categories' => $categories, 'formId'=>$formId)); // pr afficher les data          
         
    }

    public function addCommentAction()  // avec sauvegarde des deux id dans form_comment
    {           
        $form = new CommentsForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $comments = new CommentsModel();
            $form->setInputFilter($comments->getInputFilter());    
            $form->setData($request->getPost());
            if ($form->isValid()) {          
                $data = $form->getData();
                $comments->exchangeArray($data);
                            
                $comment_id = $this->getCommentsTable()->saveComment($comments);
                // $comment_id =$this->getCommentId();                
                $formComment = new FormCommentModel();
                $dataId['form_id'] = $data['form_id'];
                $dataId['comment_id'] = $comment_id;             

                $formComment->exchangeArray($dataId);
                $this->getFormCommentTable()->saveFormComment($formComment);

            }
             $this->redirect()->toRoute('forms/default', array('controller'=>'Index', 'action'=>'add-comment'));
        }
        return new ViewModel(array('form' => $form, 'comments'=>$comments));        
    }

    

    public function getCommentsTable()
    {
        if (!$this->commentsTable) {
            $sm = $this->getServiceLocator();
            $this->commentsTable = $sm->get('Forms\Model\CommentsTable');
        }
        return $this->commentsTable;
    }

    public function getFormCommentTable()
    {
        if (!$this->formCommentTable) {
            $sm = $this->getServiceLocator();
            $this->formCommentTable = $sm->get('Forms\Model\FormCommentTable');
        }
        return $this->formCommentTable;
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

//////////////////////////////////////// ESPACE ADMIN::::: Tags  //////////////////////////////////////////////////////////
    public function addTagAction()  // avec sauvegarde des deux id dans form_comment
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
                $tag_id = $this->getTagsTable()->saveTag($tags); 
               
                $formTag = new FormTagModel();

                $dataId['form_id'] = $data['form_id'];
                $dataId['tag_id'] = $tag_id;

                $formTag->exchangeArray($dataId);   
                $this->getFormTagTable()->saveFormTag($formTag);

                // \Zend\Debug\Debug::dump("fin"); die;
             
                return $this->redirect()->toRoute('forms/default', array('controller'=>'Index', 'action'=>'admin-detail-form'));                 
            }            
        }
        return new ViewModel(array('form' => $form));   
    }      
   

    public function getTagsTable()
    {
        if (!$this->tagsTable) {
            $sm = $this->getServiceLocator();
            $this->tagsTable = $sm->get('Forms\Model\TagsTable');
        }
        return $this->tagsTable;
    }

    public function getFormTagTable()
    {
        if (!$this->formTagTable) {
            $sm = $this->getServiceLocator();
            $this->formTagTable = $sm->get('Forms\Model\FormTagTable');
        }
        return $this->formTagTable;
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

////////////////////////////////////////  Category  //////////////////////////////////////////////////////////
    public function addCategoryAction()
    {
        $form = new CategoryForm();
        $request = $this->getRequest();
        if ($request->isPost()) {           
            $category = new CategoryModel();
            $form->setInputFilter($category->getInputFilter());    
            $form->setData($request->getPost());
             if ($form->isValid()) {             
                $data = $form->getData();
                $category->exchangeArray($data);                
                 $this->getCategoryTable()->saveCategory($category);
                 echo 'success'; 
                // \Zend\Debug\Debug::dump($this->getCategoryTable()->saveCategory($category)); die;
               
                    
                return $this->redirect()->toRoute('forms/default', array('controller'=>'Index', 'action'=>'admin-detail-form'));                 
            }            
        }
        return new ViewModel(array('form' => $form));   
    } 

    public function getCategoriesAction()
    {
        return new ViewModel(array('rowset' => $this->getSelectCategoryTable()->select())); 
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

    public function getCategoryTable()
    {
        if (!$this->CategoryTable) {
            $sm = $this->getServiceLocator();
            $this->CategoryTable = $sm->get('Forms\Model\CategoryTable');
        }
        return $this->CategoryTable;
    }
}