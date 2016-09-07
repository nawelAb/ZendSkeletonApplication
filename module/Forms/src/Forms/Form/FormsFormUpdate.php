<?php 
// filename : module/Forms/src/Forms/Form/FormsForm.php
namespace Forms\Form;

use Zend\Form\Form;

class FormsFormUpdate extends Form
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
            'name' => 'category_id',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'category_id',
            ),
        ));  

        $this->add(array(
             'name' => 'submit',
             'attributes' => array(
                 'type'  => 'submit',
                 'value' => 'ENVOYER'
             ),
        )); 
    }
}