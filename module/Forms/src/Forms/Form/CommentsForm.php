<?php 
namespace Forms\Form;
use Zend\Form\Form;

class CommentsForm extends Form
{
    public function __construct($name = null)
    {        
        parent::__construct('comments');
        $this->setAttribute('method', 'post');
       
        $this->add(array(
            'name' => 'value',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                // 'label' => 'Value',
            ),
        ));    

        $this->add(array(
            'name' => 'form_id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
            'options' => array(
                'label' => 'id',
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