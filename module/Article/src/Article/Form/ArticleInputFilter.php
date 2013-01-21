<?php

namespace Article\Form;

use Zend\InputFilter\InputFilter;

class ArticleInputFilter extends InputFilter {

    public function __construct() {
        //$fs1InputFilter = new InputFilter();
//Text
        $this->add(array(
            'name' => 'title',
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
                        'max' => 250,
                    )
                )
            )
        ));
//Password
        $this->add(array(
            'name' => 'blockquote',
            'required' => false,
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
                        'max' => 250,
                    ),
                ),
            ),
        ));
//Textarea
        $this->add(array(
            'name' => 'perex',
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

        $this->add(array(
            'name' => 'text',
            'required' => false,
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
