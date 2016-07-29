<?php 

namespace Comments\Form;

use Zend\Comment\Comment;

class CommentsComment extends Comment
{
    public function __construct($name = null)
    { //refaire cette partie 
        parent::__construct('Comments');
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
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Upload Now'
            ),
        )); 
    }
}