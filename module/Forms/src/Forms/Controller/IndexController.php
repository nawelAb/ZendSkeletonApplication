<?php 

namespace Forms\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Forms\Model\FormsModel;

use Forms\Form\FormsForm;
use Zend\Validator\File\Size; 
use Zend\Http\PhpEnvironment\Request;

use Zend\Db\TableGateway\TableGateway;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    protected $formsTable;

    public function indexAction() 
    { 
        return new ViewModel(array('rowset' => $this->getSelectFormsTable()->select())); // pr afficher les data                     
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
                $destination = 'C:\xampp\htdocs\kwaret\data\formsDoc';
                $ext = pathinfo($File['name'], PATHINFO_EXTENSION);

                $newName = md5(rand(). $File['name']) . '.' . $ext;
                $adapter->addFilter('File\Rename', array(
                     'target' => $destination . '/' . $File['name'].$newName,
                ));
        // \Zend\Debug\Debug::dump($newName); die;                 
                
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
}