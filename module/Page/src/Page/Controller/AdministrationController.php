<?php

namespace Page\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Page\Form\PageForm,
    Doctrine\ORM\EntityManager,
    Page\Entity\Page,
    Page\Form\PageInputFilter,
    Zend\Form\FormInterface;

class AdministrationController extends AbstractActionController {

    protected $em;

    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
    }

    public function getEntityManager() {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }

    public function indexAction() {
        return new ViewModel(array(
                    'pages' => $this->getEntityManager()->getRepository('Page\Entity\Page')->findAll()
                ));
    }

    public function addAction() {
        $form = new PageForm($this->getServiceLocator()->get('translator'));
        // Set default values
        $form->get('submit')->setValue('Add');
        
        
        $request = $this->getRequest();
        if ($request->isPost()) {

            $page = new Page();
            $form->setInputFilter(new PageInputFilter());

            $form->setData($request->getPost());
            if ($form->isValid()) {
                $page->populate($form->getData());
                $this->getEntityManager()->persist($page);
                $this->getEntityManager()->flush();
                // trigger event add article.post
                $this->getEventManager()->trigger('addPage.post', $this, array('page' => $page));
                $this->flashMessenger()->setNamespace('info')
                     ->addMessage($this->getServiceLocator()->get('translator')->translate('Page succesffully added'));
                return $this->redirect()->toRoute('zfcadmin/pages-administration');
            }
        }else{
            // Set default values
            $form->get('display_wysiwyg')->setAttribute('checked',true);
            $form->get('content')->setAttribute('class', 'span10 editable ckeditor');
        }
        return array('form' => $form);
    }

    public function editAction() {
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if (!$id) {
            return $this->redirect()->toRoute('zfcadmin/pages-administration', array('action' => 'add'));
        }
        $page = $this->getEntityManager()->find('Page\Entity\Page', $id);
        $form = new PageForm($this->getServiceLocator()->get('translator'));
        $form->setBindOnValidate(false);
        $form->bind($page);
        $form->get('submit')->setValue('Edit');
        
        $form->setInputFilter(new PageInputFilter());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $page->populate($form->getData(FormInterface::VALUES_AS_ARRAY));
                $this->getEntityManager()->persist($page);
                $this->getEntityManager()->flush();
                // trigger event edit article.post
                $this->getEventManager()->trigger('editPage.post', $this, array('page' => $page));
                $this->flashMessenger()->setNamespace('info')
                     ->addMessage($this->getServiceLocator()->get('translator')->translate('Page succesffully edited'));
                //$form->bindValues();
                //$this->getEntityManager()->flush();
                return $this->redirect()->toRoute('zfcadmin/pages-administration');
            }
        }else{
            if($page->getDisplayWysiwyg()){
            $form->get('content')->setAttribute('class', 'span10 editable ckeditor');
        }
        }
        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction() {
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if (!$id) {
            return $this->redirect()->toRoute('zfcadmin/pages-administration');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost()->get('del', 'No');
            if ($del == 'Yes') {
                $id = (int) $request->getPost()->get('id');
                $page = $this->getEntityManager()->find('Page\Entity\Page', $id);
                if ($page) {
                    $this->getEntityManager()->remove($page);
                    $this->getEntityManager()->flush();
                    $this->flashMessenger()->setNamespace('info')
                     ->addMessage($this->getServiceLocator()->get('translator')->translate('Page succesffully deleted'));
                    // trigger event delete article.post
                    $this->getEventManager()->trigger('deletePage.post', $this, array('page' => $page));
                }
            }
            return $this->redirect()->toRoute('zfcadmin/pages-administration');
         }

        return array(
            'id' => $id,
            'page' => $this->getEntityManager()->find('Page\Entity\Page', $id)
        );
    }

}

