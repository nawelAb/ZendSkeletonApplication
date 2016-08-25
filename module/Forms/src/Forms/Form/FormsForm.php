<?php 
// filename : module/Forms/src/Forms/Form/FormsForm.php
namespace Forms\Form;

use Zend\Form\Form;

class FormsForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Forms');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');
        
        $this->add(array(
            'name' => 'form_name',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Form Name',
            ),
        ));
        
        $this->add(array(
            'name' => 'fileUpload',
            'attributes' => array(
                'type'  => 'file',
            ),
            'options' => array(
                'label' => 'File Upload',
            ),
        )); 

        $this->add(array(
            'name' => 'category',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'category Name',
            ),
        ));        
        
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
                'value' => 'Upload Now'
            ),
        )); 
    }
}