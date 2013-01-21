<?php

// http://apps.zfdaily.com/dlutwbootstrap-demo/tw-bootstrap-demo

namespace Page\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\I18n\Translator\Translator;

class PageForm extends Form {

    const NS = "Page";
    
    public function __construct(Translator $translator) {
        parent::__construct('page');
        $this->setAttribute('method', 'post');
        

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'display_wysiwyg',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'value' => 1,
            ),
            'options' => array(
                'label' => $translator->translate('Display wysiwyg', self::NS),
                'hint' => $translator->translate('Type display wysiwyg hint', self::NS),
                'description' => $translator->translate('Display wysiwyg description', self::NS),
            )
        ));
        
        $this->add(array(
            'name' => 'name',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => $translator->translate('Page name placeholder', self::NS),
            ),
            'options' => array(
                'label' => $translator->translate('Name', self::NS),
                'hint' => $translator->translate('Type name hint', self::NS),
                'description' => $translator->translate('Name description', self::NS),
            )
        ));
        
        $this->add(array(
            'name' => 'route_name',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => $translator->translate('Page route name placeholder', self::NS),
            ),
            'options' => array(
                'label' => $translator->translate('Page route', self::NS),
                'hint' => $translator->translate('Type page route hint', self::NS),
                'description' => $translator->translate('Page route description.', self::NS),
            )
        ));
        $this->add(array(
            'name' => 'content',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'placeholder' => $translator->translate('Page content placeholder', self::NS),
                'class' => 'span10',
                'rows' => 20
            ),
            'options' => array(
                'label' => $translator->translate('Content', self::NS),
                'hint' => $translator->translate('Type content hint', self::NS),
                'description' => $translator->translate('Content description', self::NS),
            ),
        ));
        $this->add(array(
            'name' => 'active',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'value' => 1,
            ),
            'options' => array(
                'label' => $translator->translate('Active', self::NS),
                'hint' => $translator->translate('Type active hint', self::NS),
                'description' => $translator->translate('Active description', self::NS),
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