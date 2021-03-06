<?php
namespace Forms\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class FormFilter extends InputFilter
{
	public function __construct()
	{		
         $this->add(array(
            'name'       => 'form_name',
            'required'   => false,
            'validators' => array(
                
            ),
        ));
		

		$this->add(array(
			'name'     => 'category_id',
			'required' => false,
			'filters'  => array(
				array('name' => 'Int'),
			),
			'validators' => array(
				array(
					'name'    => 'Digits',
				),
			),
		));	
        
	}
}