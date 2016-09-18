<?php 

namespace Forms\Form;

use Zend\Form\Form;

class TagsFormRech extends Form
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
                // 'label' => ' Tag ',
            ),
            'attributes'=> array(
                'class' => 'form-control1',
                'class' => 'form-control1',
                //'class'=>'form-horizontal1',

                // 'class' => 'form-control1',
                // 'class'=>'form-horizontal1',
                // 
                // 
                // 
                // 'class' => 'form-control1',

                // 'class'=>'well form-search',
                // 'class'=>'input-medium search-query'
            )
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