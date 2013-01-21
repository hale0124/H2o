<?php

// http://apps.zfdaily.com/dlutwbootstrap-demo/tw-bootstrap-demo

namespace Article\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\I18n\Translator\Translator;

class ArticleForm extends Form {

    const NS = "Article";
    
    public function __construct(Translator $translator) {
        parent::__construct('article');
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
            'name' => 'title',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => $translator->translate('Article title placeholder', self::NS),
            ),
            'options' => array(
                'label' => $translator->translate('Title', self::NS),
                'hint' => $translator->translate('Type title hint', self::NS),
                'description' => $translator->translate('Title description', self::NS),
            )
        ));
        
        $this->add(array(
            'name' => 'articles_group',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                //'placeholder' => $translator->translate('Article title placeholder', self::NS),
            ),
            'options' => array(
                'label' => $translator->translate('Select group', self::NS),
                'hint' => $translator->translate('Type select group hint', self::NS),
                'description' => $translator->translate('Select group description', self::NS),
            )
        ));
        
        $this->add(array(
            'name' => 'blockquote',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => $translator->translate('Article blockquote placeholder', self::NS),
            ),
            'options' => array(
                'label' => $translator->translate('Blockquote', self::NS),
                'hint' => $translator->translate('Type blocquote hint', self::NS),
                'description' => $translator->translate('Blockquote description.', self::NS),
            )
        ));
        $this->add(array(
            'name' => 'perex',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'placeholder' => $translator->translate('Article perex placeholder', self::NS),
                'class' => 'span10',
                'rows' => 10
            ),
            'options' => array(
                'label' => $translator->translate('Perex', self::NS),
                'hint' => $translator->translate('Type perex hint', self::NS),
                'description' => $translator->translate('Perex description', self::NS),
            ),
        ));
        $this->add(array(
            'name' => 'text',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'placeholder' => $translator->translate('Article text placeholder', self::NS),
                'class' => 'span10',
                'rows' => 10
            ),
            'options' => array(
                'label' => $translator->translate('Text', self::NS),
                'hint' => $translator->translate('Type text hint', self::NS),
                'description' => $translator->translate('Text description.', self::NS),
            )
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