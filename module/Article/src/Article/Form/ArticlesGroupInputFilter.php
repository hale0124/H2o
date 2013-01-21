<?php

namespace Article\Form;

use Zend\InputFilter\InputFilter;

class ArticlesGroupInputFilter extends InputFilter {

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
                        'max' => 150,
                    )
                )
            )
        ));
    }

}
?>
