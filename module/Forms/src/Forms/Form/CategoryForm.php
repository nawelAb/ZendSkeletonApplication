<?php 
// filename : module/Forms/src/Forms/Form/FormsForm.php
namespace Forms\Form;

use Zend\Form\Form;

class CategoryForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Forms');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');
        
        
        
        $this->add(array(
            'name' => 'value',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Value',
            ),
        ));      
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Submit'
            ),
        )); 
    }
}