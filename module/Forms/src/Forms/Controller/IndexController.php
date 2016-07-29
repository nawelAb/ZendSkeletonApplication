<?php 
// filename : module/Forms/src/Forms/Controller/IndexController.php
namespace Forms\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Forms\Model\FormsModel;

use Forms\Form\FormsForm;
use Zend\Validator\File\Size; 

class IndexController extends AbstractActionController
{
    protected $formsTable;
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
                
                if (!$adapter->isValid()){
                    $dataError = $adapter->getMessages();
                    $error = array();
                    foreach($dataError as $key=>$row)
                    {
                        $error[] = $row;
                    } //set formElementErrors
                    $form->setMessages(array('fileUpload'=>$error ));
                } else {
        // \Zend\Debug\Debug::dump(); die; 
        // renomer avant de sauvegarder 
                    $adapter->setDestination('C:\xampp\htdocs\kwaret\data\formsDoc');
                    if ($adapter->receive($File['name'])) {

                        $data = $form->getData();
                        // $data = $this->prepareData($data);
                        $forms->exchangeArray($data);
                        ////////////////ajout de la sauvegarde dans la bdd/////////
                        $this->getFormsTable()->saveForm($forms);                    

                        echo 'forms success ';//.$forms->FormName.'upload'.$forms->fileUpload;
                        // \Zend\Debug\Debug::dump($a);die;
                    }
                }  
            }
        }         
        return array('form' => $form);
    }

    public function getFormsTable()
    {
        if (!$this->formsTable) {
            $sm = $this->getServiceLocator();
            $this->formsTable = $sm->get('Forms\Model\FormsTable');
        }
        return $this->formsTable;
    }
    
    public function uploadProgressAction()
    {
        $id = $this->params()->fromQuery('id', null);
        $progress = new \Zend\ProgressBar\Upload\SessionProgress();
        return new \Zend\View\Model\JsonModel($progress->getProgress($id));
    }
}