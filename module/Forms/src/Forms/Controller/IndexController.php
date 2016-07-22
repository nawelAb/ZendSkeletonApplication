<?php 
// filename : module/Forms/src/Forms/Controller/IndexController.php
namespace Forms\Controller;

use Zend\Mvc\Controller\AbstractActionController;

use Forms\Model\forms;
use Forms\Form\FormsForm;
use Zend\Validator\File\Size;

class IndexController extends AbstractActionController
{
    public function uploadFormAction() // UploadForm
    {
        $form = new FormsForm();
        $request = $this->getRequest();  
    
        if ($request->isPost()) {
            
            $profile = new forms();
            $form->setInputFilter($profile->getInputFilter());
            
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
                    $adapter->setDestination(dirname(__DIR__));
                    if ($adapter->receive($File['name'])) {
        // \Zend\Debug\Debug::dump($form->getData()); die; 

                        $profile->exchangeArray($form->getData());
                        echo 'forms Name '.$profile->FormName.' upload '.$profile->fileUpload;
                    }
                }  
            }
        }         
        return array('form' => $form);
    }


    public function uploadProgressAction()
    {
        $id = $this->params()->fromQuery('id', null);
        $progress = new \Zend\ProgressBar\Upload\SessionProgress();
        return new \Zend\View\Model\JsonModel($progress->getProgress($id));
    }
}