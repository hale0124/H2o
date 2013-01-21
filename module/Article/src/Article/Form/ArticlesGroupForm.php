<?php

// http://apps.zfdaily.com/dlutwbootstrap-demo/tw-bootstrap-demo

namespace Article\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\I18n\Translator\Translator;

class ArticlesGroupForm extends Form {

    const NS = "Article";
    
    public function __construct(Translator $translator) {
        parent::__construct('articles-group');
        $this->setAttribute('method', 'post');
        

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'name',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => $translator->translate('Articles grouptitle placeholder', self::NS),
            ),
            'options' => array(
                'label' => $translator->translate('Name', self::NS),
                'hint' => $translator->translate('Type name hint', self::NS),
                'description' => $translator->translate('Name description', self::NS),
            )
        ));
        
        $this->add(new Element\Csrf('csrf'));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => $translator->translate('Submit', self::NS),
            ),
            'options' => array(
                'primary' => true,
            ),
        ));
    }

}