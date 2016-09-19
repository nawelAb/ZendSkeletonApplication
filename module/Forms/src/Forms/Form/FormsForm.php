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
                'label' => 'Nom du formulaire',
            ),
        ));
        
        $this->add(array(
            'name' => 'fileUpload',
            'attributes' => array(
                'type'  => 'file',
            ),
            'options' => array(
                'label' => ' selectionner un fichier',
            ),
        )); 

       
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Télécharger'
            ),
        )); 
    }
}