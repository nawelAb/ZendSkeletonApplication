<?php 

namespace Forms\Form;

use Zend\Form\Form;

class TagsForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Tags');
        $this->setAttribute('method', 'post');
      
        $this->add(array(
            'name' => 'value',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => ' Tag ',
            ),
            'attributes' => [
                'class' => 'form-control',
                'placeholder'=>'taper un tag pour effectuer une recherche '
            ]
        ));


        $this->add(array(
            'name' => 'form_id',
            'attributes' => array(
                'type'  => 'hidden',
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