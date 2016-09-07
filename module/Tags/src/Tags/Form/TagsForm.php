<?php 

namespace Tags\Form;

use Zend\Form\Form;

class TagsForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Tags');
        $this->setAttribute('method', 'post');
        // $this->setAttribute('enctype','multipart/form-data');
        
        $this->add(array(
            'name' => 'value',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => ' Tag ',
            ),
        ));    
   
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'submit'
            ),
        )); 
    }
}