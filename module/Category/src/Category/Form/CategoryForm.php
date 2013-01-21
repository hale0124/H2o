<?php

// http://apps.zfdaily.com/dlutwbootstrap-demo/tw-bootstrap-demo

namespace Category\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\I18n\Translator\Translator;
use Doctrine\ORM\EntityManager;

class CategoryForm extends Form {

    const NS = "Category";

    private $em;
    private $translator;
    private $type = 1;

    public function __construct(Translator $translator, EntityManager $em, $type = 1) {
        parent::__construct('article');
        $this->em = $em;
        $this->translator = $translator;
        $this->setAttribute('method', 'post');
        $this->setType($type);
        $this->prepareElements();
    }

    protected function getTranslator() {
        return $this->translator;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function getType() {
        return $this->type;
    }

    public function prepareElements() {
        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'category_route_type',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => $this->getTranslator()->translate('Select group', self::NS),
                'hint' => $this->getTranslator()->translate('Type select group hint', self::NS),
                'description' => $this->getTranslator()->translate('Select group description', self::NS),
                'value_options' => $this->getRoutesTypes()
            )
        ));

        switch ($this->getType()) {
            case 1:
                $this->add(array(
                    'name' => 'page_route_name',
                    'type' => 'Zend\Form\Element\Select',
                    'options' => array(
                        'label' => $this->getTranslator()->translate('Select page', self::NS),
                        'hint' => $this->getTranslator()->translate('Type select page hint', self::NS),
                        'description' => $this->getTranslator()->translate('Select page description', self::NS),
                        'value_options' => $this->getPages()
                    )
                ));
                break;
            case 2:
                $this->add(array(
                    'name' => 'articles_group_id',
                    'type' => 'Zend\Form\Element\Select',
                    'options' => array(
                        'label' => $this->getTranslator()->translate('Select articles group', self::NS),
                        'hint' => $this->getTranslator()->translate('Type select page hint', self::NS),
                        'description' => $this->getTranslator()->translate('Select page description', self::NS),
                        'value_options' => $this->getArticlesGroups()
                    )
                ));
                break;
            case 3:
                $this->add(array(
                    'name' => 'route',
                    'type' => 'Zend\Form\Element\Text',
                    'options' => array(
                        'label' => $this->getTranslator()->translate('Route', self::NS),
                        'hint' => $this->getTranslator()->translate('Type route hint', self::NS),
                        'description' => $this->getTranslator()->translate('Route description', self::NS),
                    )
                ));
                break;
        }
        
        $this->add(array(
                    'name' => 'category_name',
                    'type' => 'Zend\Form\Element\Text',
                    'options' => array(
                        'label' => $this->getTranslator()->translate('Category name', self::NS),
                        'hint' => $this->getTranslator()->translate('Type category name hint', self::NS),
                        'description' => $this->getTranslator()->translate('Category name description', self::NS),
                    )
                ));

        $this->add(new Element\Csrf('csrf'));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => $this->getTranslator()->translate('Submit', self::NS),
            ),
            'options' => array(
                'primary' => true,
            ),
        ));

        $this->add(array(
            'name' => 'reload',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => 'reload',
                'class' => 'hidden'
            )
        ));
    }

    protected function getEntityManager() {
        return $this->em;
    }

    private function getRoutesTypes() {
        $categoryRouteTypes = $this->getEntityManager()->getRepository('Category\Entity\CategoryRouteType')->findAll();
        $names = array();
        foreach ($categoryRouteTypes as $categoryRouteType) {
            $names[$categoryRouteType->getId()] = $categoryRouteType->getName();
        }
        return $names;
    }

    private function getPages() {
        $pages = $this->getEntityManager()->getRepository('Page\Entity\Page')->findAll();
        $pg = array();
        foreach ($pages as $page) {
            $pg[$page->getRouteName()] = $page->getName();
        }
        return $pg;
    }

    private function getArticlesGroups() {
        $articlesGroups = $this->getEntityManager()->getRepository('Article\Entity\ArticlesGroup')->findAll();
        $ag = array();
        foreach ($articlesGroups as $articleGroup) {
            $ag[$articleGroup->getId()] = $articleGroup->getName();
        }
        return $ag;
    }

}