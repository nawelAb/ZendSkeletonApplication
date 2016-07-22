<?php
// filename : module/Forms/src/Forms/Model/forms.php
namespace Forms\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

use Zend\Validator\File\ExcludeExtension;
use Zend\Filter\File\RenameUpload;
use Zend\Validator\File\Size;

class Forms implements InputFilterAwareInterface
{
    public $FormName;
    public $fileUpload;
    protected $inputFilter;
    
    public function exchangeArray($data)
    {
        $this->FormName  = (isset($data['FormName']))  ? $data['FormName']     : null; 
        $this->fileUpload  = (isset($data['fileUpload']))  ? $data['fileUpload']     : null; 
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
                    'name'     => 'FormName',
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
                        // array(
                        //     'name'  => 'Zend\Validator\File\Extension',
                        //     'options' =>
                        //         array(
                        //             'extension' => array('pdf') // a corriger 
                        //         ),
                        // ),
                    )
                ))
            );
            
            $this->inputFilter = $inputFilter;
        }        
        return $this->inputFilter;
    }
}