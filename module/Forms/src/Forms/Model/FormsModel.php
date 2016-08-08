<?php
// filename : module/Forms/src/Forms/Model/forms.php
namespace Forms\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

use Zend\Filter\File\RenameUpload;
use Zend\Validator\File\Size;

class FormsModel implements InputFilterAwareInterface
{   
    public $fileUpload;
    public $id;
    public $form_name;
    protected $inputFilter;
    
    public function exchangeArray($data)
    {        
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->form_name = (isset($data['form_name'])) ? $data['form_name'] : null;
        $this->fileUpload  = (isset($data['fileUpload']))  ? $data['fileUpload'] : null;  

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
                    'name'     => 'form_name',
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
            
            $inputFilter->add(
                $factory->createInput(array(
                    'name'     => 'fileUpload',
                    'required' => true,
                    'validators' => array(
                    //     [
                    //     'name'  => 'Zend\Validator\File\Size',
                    //     'options' =>
                    //         [
                    //             'max'      => '30MB',
                    //         ],
                    // ],
                    // 
                        
                        
                    )
                ))
            );


            
            $this->inputFilter = $inputFilter;
        }        
        return $this->inputFilter;
    }
}
