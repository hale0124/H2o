<?php

namespace Page\Form;

use Zend\InputFilter\InputFilter;

class PageInputFilter extends InputFilter {

    public function __construct() {
        $this->add(array(
            'name' => 'name',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 200,
                    )
                )
            )
        ));
//Password
        $this->add(array(
            'name' => 'route_name',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 150,
                    ),
                ),
                array(
                    'name'=>'Regex',
                    'options' => array(
                        'pattern' => '/^[a-zA-Z0-9]*$/'
                    ),
                ),
            ),
        ));
//Textarea
        $this->add(array(
            'name' => 'content',
            'required' => true,
            'filters' => array(
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 64000,
                    ),
                ),
            ),
        ));

        
        //$this->add($fs1InputFilter, 'fsOne');
    }

}
?>
