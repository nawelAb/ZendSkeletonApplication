<?php
namespace Auth\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class RegistrationFilter extends InputFilter
{
	public function __construct($sm)
	{
		$this->add(array(
            'name'       => 'usr_email',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'EmailAddress'
                ),
				array(
					'name'		=> 'Zend\Validator\Db\NoRecordExists',
					'options' => array(
						'table'   => 'users',
						'field'   => 'usr_email',
						'adapter' => $sm->get('Zend\Db\Adapter\Adapter'),
					),
				),
            ),
        ));
		
		$this->add(array(
			'name'     => 'usr_password',
			'required' => true,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
			'validators' => array(
				array(
					'name'    => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'min'      => 6,
						'max'      => 12,
					),
				),
			),
		));	

		$this->add(array(
			'name'     => 'usr_password_confirm',
			'required' => true,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
			'validators' => array(
				array(
					'name'    => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'min'      => 6,
						'max'      => 12,
					),
				),
                array(
                    'name'    => 'Identical',
                    'options' => array(
                        'token' => 'usr_password',
                    ),
                ),
			),
		));		
	}
}