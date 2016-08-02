<?php 
namespace Comments\Controller;
// filename : module/Comments/src/Comments/Controller/CommentsController.php

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Comments\Model\CommentsModel;

use Comments\Form\CommentsForm;
// use Zend\Validator\File\Size; 

class CommentsController extends AbstractActionController
{
    // public function addCommentsAction() // UploadForm
    // {
    //     // var_dump('controller ');die; // les routes sont correcte
    //     $form = new CommentsForm();
    //     $request = $this->getRequest();  
    
    //     // \Zend\Debug\Debug::dump($request->isPost()); die; 
    //     if ($request->isPost()) {
            
    //         $comments = new CommentsModel();
    //         $form->setInputFilter($comments->getInputFilter());         
           
    //         $form->setData($data);

    //         if ($form->isValid()) {
                
                                
    //             // $adapter = new \Zend\File\Transfer\Adapter\Http();               
    //             // $adapter->setValidators(array($size), $File['name']);
                
    //             // if (!$adapter->isValid()){
    //             //     $dataError = $adapter->getMessages();
    //             //     $error = array();
    //             //     foreach($dataError as $key=>$row)
    //             //     {
    //             //         $error[] = $row;
    //             //     } //set formElementErrors
    //             //     $form->setMessages(array('fileUpload'=>$error ));
    //             // } else {
    //     // renomer avant de sauvegarder 
    //                 // $adapter->setDestination('C:\xampp\htdocs\kwaret\data\commentsDoc');
    //                 // if ($adapter->receive($File['name'])) {

    //                     $data = $form->getData();
    //                     // $data = $this->prepareData($data);
    //                     $comments->exchangeArray($data);
    //                     ////////////////ajout de la sauvegarde dans la bdd/////////
    //                     $this->getCommentsTable()->saveComment($comments);                    

    //                     echo 'comments success ';die;//.$comments->FormName.'upload'.$comments->fileUpload;
    //                     // \Zend\Debug\Debug::dump($a);die;
    //                 // }
    //             // }  
    //         }
    //     }         
    //     // return array('form' => $comments);
    // }
   
    public function indexAction()
    {
        return new ViewModel();
    }
   
    public function addCommentsAction()
    {
    	$form = new CommentsForm();
		$form->get('submit')->setValue('Register');
		
		$request = $this->getRequest();
				// var_dump($request->isPost());die;
				// \Zend\Debug\Debug::dump(get_class_methods($request)); die;
        if ($request->isPost()) {
			// $form->setInputFilter(new RegistrationFilter($this->getServiceLocator()));
			// $form->setData($request->getPost());
			 // if ($form->isValid()) {			 
				$data = $form->getData();
				$data = $this->prepareData($data);
				$comment = new CommentsModel();
				$comment->exchangeArray($data);

				$this->getCommentsTable()->saveComment($comment);
			/* la confirmation de l inscription ne fonctionne pas 
				$this->sendConfirmationEmail($comment);
				$this->flashMessenger()->addMessage($comment->usr_email);
			*/
				return $this->redirect()->toRoute('comments/default', array('controller'=>'Comments', 'action'=>'addComments'));					
			// }			 
		}
		return new ViewModel(array('form' => $form));
    }

}