<?php
namespace Comments\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class CommentsModel implements InputFilterAwareInterface
{   
    public $id;
    public $value;
    protected $inputFilter;
    
    public function exchangeArray($data)
    {        
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->value     = (!empty($data['value'])) ? $data['value'] : null;
    } 
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
    
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();
             
            $inputFilter->add(
                $factory->createInput(array(
                    'name'     => 'value',
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
                                'min'      => 1,
                                'max'      => 100,
                            ),
                        ),
                    ),
                ))
            );           
           
            $this->inputFilter = $inputFilter;
        }        
        return $this->inputFilter;
    }
}
