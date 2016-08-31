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
use Forms\Form\CommentFilter;
use Forms\Model\CommentsModel;
use Forms\Model\CommentsTable;

// tags
use Forms\Form\TagsForm;
use Forms\Form\TagFilter;
use Forms\Model\TagsModel;
use Forms\Model\TagsTable;

use Forms\Model\FormCommentModel;
use Forms\Model\FormTagModel;

use Forms\Form\CategoryFilter;
use Forms\Model\CategoryModel;
use Forms\Model\CategoryTable;
use Forms\Form\CategoryForm;
use Forms\Form\CategoryFormUpdate;

use Forms\Form\FormFilter;
use Forms\Form\FormsFormUpdate;

use Forms\Form\TagsFormRech;
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
        $categories = $this->getSelectCategoryTable()->select();
              // \Zend\Debug\Debug::dump($categories); die; 
              // 
        $form = new CategoryForm();
        $request = $this->getRequest();

        return new ViewModel(array(
                                    'form'      =>$form,
                                    'categories'=> $categories
        )); // pr afficher les data    
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


    public function updateAction() // modifier les donnes d'un formulaire avec ajout de categorie  
    {        
        $id = $this->params()->fromRoute('id');
        if (!$id) return $this->redirect()->toRoute('forms/default', array('controller' => 'Index', 'action' => 'list-form'));
        
        $form = new FormsFormUpdate();
        $request = $this->getRequest();
     
        if ($request->isPost()) {
            $form->setInputFilter(new FormFilter());
            $form->setData($request->getPost());        
            if ($form->isValid()) {
                $data = $form->getData();
                $data['state'] = 1;                          
                unset($data['submit']);          
                $this->getSelectFormsTable()->update($data, array('id' => $id));
                var_dump( "success");          
                         
                return $this->redirect()->toRoute('forms/default', array('controller' => 'Index', 'action' => 'update'));                                                 
            }            
        } else {
            
            $form->setData($this->getSelectFormsTable()->select(array('id' => $id))->current());          
        }

        return new ViewModel(array('form'=> $form, 'id' => $id));
    }

    // liste des formulaires 
    public function listFormAction()
    {
        $list = $this->getSelectFormsTable()->select(array('state' => 1));
        $categories = $this->getSelectCategoryTable()->select();
        return new ViewModel(array('rowset' => $list, 'categories' => $categories, 'list'=>$list)); 
    }

    // les commentaires d'un formulaire  
    public function detailFormAction() 
    {       
        $formId = $this->params()->fromRoute('id');        
        if (!$formId) return $this->redirect()->toRoute('forms/default', array('controller' => 'index', 'action' => 'listForm'));

        $unformulaire = $this->getSelectFormsTable()->select(array('id' => $formId))->current();
        $comments = $this->getSelectCommentsTable()->select(array('form_id' => $formId));
        // $comments = $this->getFormsTable()->getFormComment($formId);         
         
        // les tags du formulaire 
        $formtags = $this->getFormTagTable()->getFormTags($formId); 
      
        // $comment = $this->getSelectCommentsTable()->select();
     
        // \Zend\Debug\Debug::dump($unformulaire) ; die;
        // ajout d un commentauire 
        $form = new CommentsForm();
        $request = $this->getRequest();             
        
        return new ViewModel(array(
                                    'form_id'=>$formId, // pr afficher les data    
                                    'unformulaire' => $unformulaire, 
                                    'formtags'=>$formtags,
                                    'comments'=> $comments,  
                                    'form'=>$form
        ));        
    }

    public function deleteFormAction()
    {    
        $id = $this->params()->fromRoute('id');
         
        if ($id) { 
            $this->getFormsTableDelete()->delete(array('id' => $id));
        }        
        return $this->redirect()->toRoute('forms/default', array('controller' => 'index', 'action' => 'adminListForm'));                                         
    }

    public function getFormsTable()
    {
        if (!!$this->formsTable) {
            $sm = $this->getServiceLocator();
            $this->formsTable = $sm->get('Forms\Model\FormsTable');
        }
        return $this->formsTable;
    }

    public function getFormsTableDelete()
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
   
////////////////////////////////////////ADMIN  Comments//////////////////////////////////////////////////////////

    //list form avec  une requete where state = 0 
    public function adminListFormAction()
    {
       $list = $this->getSelectFormsTable()->select(array('state' => 0));
       // $categories = $this->getSelectCategoryTable()->select();
        return new ViewModel(array('rowset' => $list)); 
    }

    public function adminDetailFormAction() // permet l ajout d un tag a un formulaire 
    {
        $formId = $this->params()->fromRoute('id');

        // if (!$id) return $this->redirect()->toRoute('auth/default', array('controller' => 'admin', 'action' => 'index'));
        $unformulaire = $this->getSelectFormsTable()->select(array('id' => $formId));

        // category 
        $categories = $this->getSelectCategoryTable()->select();

        // ajout d un tag
        $form = new TagsForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
        }        
        return new ViewModel(array('form'=>$form,'unformulaire' => $unformulaire, 'categories' => $categories, 'formId'=>$formId)); // pr afficher les data          
    
    }

    public function updateCommentAction()   
    {        
        $id = $this->params()->fromRoute('id');
        if (!$id) return $this->redirect()->toRoute('forms/default', array('controller' => 'Index', 'action' => 'detailForm', 'id'=>$id));
        
        $form = new CommentsForm();
        $request = $this->getRequest();
           
        if ($request->isPost()) {
            $form->setInputFilter(new CommentFilter());
            $form->setData($request->getPost());        
            if ($form->isValid()) {
                $data = $form->getData();                                      
                unset($data['submit'], $data['form_id']);          
                $this->getSelectCommentsTable()->update($data, array('id' => $id)); 

                return $this->redirect()->toRoute('forms/default', array('controller' => 'Index', 'action' => 'detailForm'));                                                 
            }            
        } else {
            
            $form->setData($this->getSelectCommentsTable()->select(array('id' => $id))->current());          
        }

        return new ViewModel(array('form'=> $form, 'id' => $id));
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
                $this->getCommentsTable()->saveComment($comments);
                // \Zend\Debug\Debug::dump($this->getCommentsTable()); die;
            }
            $this->redirect()->toRoute('forms/default', array('controller'=>'Index', 'action'=>'detailForm', 'id' => $data['form_id']));
        }
        return new ViewModel(array('form' => $form));        
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

   public function deleteCommentAction() //delete tag valide 
    {
        $id = $this->params()->fromRoute('id');

        if ($id) {
            $this->getCommentsTable()->delete(array('id' => $id));
        }        
        return $this->redirect()->toRoute('forms/default', array('controller' => 'index', 'action' => 'detailForm'));                                         
    }

 
//////////////////////////////////////// ESPACE ADMIN::::: Tags  //////////////////////////////////////////////////////////
    
    public function addTagFormAction() 
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
               
                return $this->redirect()->toRoute('forms/default', array('controller'=>'Index', 'action'=>'list-tag'));                 
                // \Zend\Debug\Debug::dump("fin"); die;                         
            }            
        }
        return new ViewModel(array('form' => $form));        
    }


    public function addTagAction()  // avec sauvegarde des deux id dans form_comment sans liaison avec un formulaire
    {
       
        $form = new TagsForm();
        $request = $this->getRequest();
        if ($request->isPost()) {           
        // var_dump($data['form_id']);        die;

            $tags = new TagsModel();
            $form->setInputFilter($tags->getInputFilter());    
            $form->setData($request->getPost());
             if ($form->isValid()) {             
                $data = $form->getData();
                $tags->exchangeArray($data);                
                $tag_id = $this->getTagsTable()->saveTag($tags);               
                         
                return $this->redirect()->toRoute('forms/default', array('controller'=>'Index', 'action'=>'list-tag'));                 
                
                // \Zend\Debug\Debug::dump("fin"); die;             
            }            
        }
        return new ViewModel(array('form' => $form));   
    }     
   
    public function updateTagAction()   
    {        
        $id = $this->params()->fromRoute('id');
        if (!$id) return $this->redirect()->toRoute('forms/default', array('controller' => 'Index', 'action' => 'listTag'));
        
        $form = new TagsForm();
        $request = $this->getRequest();
           
        if ($request->isPost()) {
            $form->setInputFilter(new TagFilter());
            $form->setData($request->getPost());        
            if ($form->isValid()) {
                $data = $form->getData();                                      
                unset($data['submit']);          
                $this->getSelectTagsTable()->update($data, array('id' => $id));                         
                return $this->redirect()->toRoute('forms/default', array('controller' => 'Index', 'action' => 'updateTag'));                                                 
            }            
        } else {
            
            $form->setData($this->getSelectTagsTable()->select(array('id' => $id))->current());          
        }

        return new ViewModel(array('form'=> $form, 'id' => $id));
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

    public function deleteTagAction() //delete tag valide 
    {
        $id = $this->params()->fromRoute('id');
        if ($id) {
            $this->getTagsTable()->delete(array('id' => $id));
        }
        
        return $this->redirect()->toRoute('forms/default', array('controller' => 'index', 'action' => 'listTag'));                                         
    }

    public function listTagAction()
    {
        // ajouter une nouvelle categorie        
        $form = new TagsForm();
        $request = $this->getRequest();

        $list = $this->getSelectTagsTable()->select();
        return new ViewModel(array('form' =>$form ,'rowset' => $list)); 
    }


////////////////////////////////////////  Category  //////////////////////////////////////////////////////////
    // manque la recherche des formulaires suivant la categorie 

    public function addCategoryAction()
    {   
/* la requete sql permet de recuperer les id des formulaire de la category specifié  mais je n arrive pas a les afficher 
       
        $categoryId = 1;

        $select = new Select();
        $select->from('forms')->columns(array('id', 'form_name'))
            ->join('category', 'forms.category_id = category.id', array(), Select::JOIN_LEFT)            
            ->where('category.id ='.$categoryId);
        \Zend\Debug\Debug::dump($select->getSqlString()); die;
        $select->getSqlString();
        return $this->tableGateway->selectWith($select);
*/
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
                    
                return $this->redirect()->toRoute('forms/default', array('controller'=>'Index', 'action'=>'listCategory'));                 
            }            
        }
        return new ViewModel(array('form' => $form));   
    } 

    public function listCategoryAction()
    {
        // ajouter une nouvelle categorie        
        $form = new CategoryForm();
        $request = $this->getRequest();

        $list = $this->getSelectCategoryTable()->select();
        return new ViewModel(array('form' =>$form ,'rowset' => $list)); 
    }

    public function updateCategoryAction() // modifier les donnes d'un formulaire avec ajout de categorie  
    {        
        $id = $this->params()->fromRoute('id');
        if (!$id) return $this->redirect()->toRoute('forms/default', array('controller' => 'Index', 'action' => 'listCategory'));
        
        $form = new CategoryForm();
        $request = $this->getRequest();
           
        if ($request->isPost()) {
            $form->setInputFilter(new CategoryFilter());
            $form->setData($request->getPost());        
            if ($form->isValid()) {
                $data = $form->getData();                                      
                unset($data['submit']);          
                $this->getSelectCategoryTable()->update($data, array('id' => $id));                         
                return $this->redirect()->toRoute('forms/default', array('controller' => 'Index', 'action' => 'updateCategory'));                                                 
            }            
        } else {
            
            $form->setData($this->getSelectCategoryTable()->select(array('id' => $id))->current());          
        }

        return new ViewModel(array('form'=> $form, 'id' => $id));
    }
   
    public function deleteCategoryAction() //delete category valide 
    {
        $id = $this->params()->fromRoute('id');
        if ($id) {
            $this->getCategoryTable()->delete(array('id' => $id));
        }
        
        return $this->redirect()->toRoute('forms/default', array('controller' => 'index', 'action' => 'listCategory'));                                         
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


// ################################################### affichages ################################################### 
public function FormByCategoryAction()
{
    $categoryId = $this->params()->fromRoute('id');
    // if (!$id) return $this->redirect()->toRoute('auth/default', array('controller' => 'admin', 'action' => 'index'));
    // $categories = $this->getSelectFormsTable()->select();

    $list = $this->getSelectFormsTable()->select(array('category_id'=>$categoryId));


    return new ViewModel(array( 
                                'form'=>$form,
                                'list' => $list,
    ));
}

public function findFormByTagAction()
{
    $list = $this->getFormTagTable()->getFormTags(11);

    return new ViewModel(array(
                                'list' => $list,
    ));           
}




}