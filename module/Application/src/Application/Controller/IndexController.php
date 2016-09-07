<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Forms\Form\TagsFormRech;
use Zend\Db\TableGateway\TableGateway;

class IndexController extends AbstractActionController
{
	protected $categoryTable;
	protected $tagsTable;
    
	public function indexAction() 
    {   
        $categories = $this->getSelectCategoryTable()->select();
        $tags = $this->getSelectTagsTable()->select();
              // \Zend\Debug\Debug::dump($categories); die; 
             
        $form = new TagsFormRech();
        $request = $this->getRequest();

        return new ViewModel(array(
                                    'form'      =>$form,
                                    'categories'=> $categories,
                                    'tags' 		=>$tags
        ));                           
    } 

    public function getSelectCategoryTable()
    {        
        if (!$this->categoryTable) {
            $this->categoryTable = new TableGateway(
                'category', 
                $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter')

            );
        }
        return $this->categoryTable;    
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


    public function contactAction()
    {
        return new ViewModel();
    }
}
