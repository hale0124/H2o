<?php

namespace Article\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Article\Form\ArticleForm,
    Article\Form\ArticlesGroupForm,
    Doctrine\ORM\EntityManager,
    Article\Entity\Article,
    Article\Entity\ArticlesGroup,
    Article\Form\ArticleInputFilter,
    Article\Form\ArticlesGroupInputFilter,
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

    private function getArticlesGroupsNames(){
        $q = $this->getEntityManager()->createQuery("select ag from Article\Entity\ArticlesGroup ag");
        $names = array();
        foreach ($q->getResult() as $ag){
            $names[$ag->getId()] = $ag->getName();
        }
        return $names;
    }
    
    public function indexAction() {
        return new ViewModel(array(
                    'tab' => $this->getEvent()->getRouteMatch()->getParam('tab'),
                    'articles' => $this->getEntityManager()->getRepository('Article\Entity\Article')->findAll(),
                    'articlesGroups' => $this->getEntityManager()->getRepository('Article\Entity\ArticlesGroup')->findAll(),
                ));
    }

    public function addAction() {
        $form = new ArticleForm($this->getServiceLocator()->get('translator'));
        $form->get('submit')->setValue('Add');
        $form->get('articles_group')->setOptions(array('value_options' => $this->getArticlesGroupsNames()));
        $request = $this->getRequest();
        if ($request->isPost()) {
            $article = new Article();
            $form->setInputFilter(new ArticleInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $article->populate($form->getData());
                $article->setCreated(new \DateTime());
                if($this->zfcUserAuthentication()->hasIdentity()){
                    $article->setUser($this->zfcUserAuthentication()->getIdentity());
                }
                $article->setArticlesGroup($this->getEntityManager()->getRepository('Article\Entity\ArticlesGroup')->find($form->get('articles_group')->getValue()));
                $this->getEntityManager()->persist($article);
                $this->getEntityManager()->flush();
                $this->getEventManager()->trigger('addArticle.post', $this, array('article' => $article));
                $this->flashMessenger()->setNamespace('info')
                     ->addMessage($this->getServiceLocator()->get('translator')->translate('Article succesffully added'));
                return $this->redirect()->toRoute('zfcadmin/articles-administration');
            }
        }else{
            $form->get('display_wysiwyg')->setAttribute('checked',true);
            $form->get('text')->setAttribute('class', 'span10 editable ckeditor');
            $form->get('perex')->setAttribute('class', 'span10 editable ckeditor');
        }
        return array('form' => $form);
    }

    public function editAction() {
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if (!$id) {
            return $this->redirect()->toRoute('zfcadmin/articles-administration', array('action' => 'add'));
        }
        $article = $this->getEntityManager()->find('Article\Entity\Article', $id);
        $created = $article->getCreated();
        $form = new ArticleForm($this->getServiceLocator()->get('translator'));
        $form->get('articles_group')->setOptions(array('value_options' => $this->getArticlesGroupsNames()));
        $form->setBindOnValidate(false);
        
        $form->bind($article);
        $form->get('submit')->setValue('Edit');
        $form->get('articles_group')->setValue($article->getArticlesGroup()->getId());
        
        $form->setInputFilter(new ArticleInputFilter());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $article->populate($form->getData(FormInterface::VALUES_AS_ARRAY));
                $article->setCreated($created);
                if($this->zfcUserAuthentication()->hasIdentity()){
                    $article->setUser($this->zfcUserAuthentication()->getIdentity());
                }
                $article->setArticlesGroup($this->getEntityManager()->getRepository('Article\Entity\ArticlesGroup')->find($form->get('articles_group')->getValue()));
                $this->getEntityManager()->persist($article);
                $this->getEntityManager()->flush();
                
                // trigger event edit article.post
                $this->getEventManager()->trigger('editArticle.post', $this, array('article' => $article));
                $this->flashMessenger()->setNamespace('info')
                     ->addMessage($this->getServiceLocator()->get('translator')->translate('Article succesffully edited'));
                //$form->bindValues();
                //$this->getEntityManager()->flush();
                return $this->redirect()->toRoute('zfcadmin/articles-administration');
            }
        }else{
            if($article->getDisplayWysiwyg()){
            $form->get('text')->setAttribute('class', 'span10 editable ckeditor');
            $form->get('perex')->setAttribute('class', 'span10 editable ckeditor');
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
            return $this->redirect()->toRoute('zfcadmin/articles-administration');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost()->get('del', 'No');
            if ($del == 'Yes') {
                $id = (int) $request->getPost()->get('id');
                $article = $this->getEntityManager()->find('Article\Entity\Article', $id);
                if ($article) {
                    $this->getEntityManager()->remove($article);
                    $this->getEntityManager()->flush();
                    // trigger event delete article.post
                    $this->getEventManager()->trigger('deleteArticle.post', $this, array('article' => $article));
                    $this->flashMessenger()->setNamespace('info')
                     ->addMessage($this->getServiceLocator()->get('translator')->translate('Article succesffully deleted'));
                }
            }
            return $this->redirect()->toRoute('zfcadmin/articles-administration');
         }
        return array(
            'id' => $id,
            'article' => $this->getEntityManager()->find('Article\Entity\Article', $id)
        );
    }

    // =========================================================================
    
     public function addGroupAction(){
        $form = new ArticlesGroupForm($this->getServiceLocator()->get('translator'));
        $form->get('submit')->setValue('Add');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $ag = new ArticlesGroup();
            $form->setInputFilter(new ArticlesGroupInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $ag->populate($form->getData());
                $this->getEntityManager()->persist($ag);
                $this->getEntityManager()->flush();
                $this->getEventManager()->trigger('addArticlesGroup.post', $this, array('articlesGroup' => $ag));
                $this->flashMessenger()->setNamespace('info')
                     ->addMessage($this->getServiceLocator()->get('translator')->translate('Articles group succesffully added'));
                return $this->redirect()->toRoute('zfcadmin/articles-administration', array('tab' => 'articlesGroups'));
            }
        }
        return array('form' => $form);
    }
    
    public function editGroupAction() {
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if (!$id) {
            return $this->redirect()->toRoute('zfcadmin/articles-administration', array('action' => 'addGroup', 'tab' => 'articlesGroups'));
        }
        $ag = $this->getEntityManager()->find('Article\Entity\ArticlesGroup', $id);
        $form = new ArticlesGroupForm($this->getServiceLocator()->get('translator'));
        $form->setBindOnValidate(false);
        $form->bind($ag);
        $form->get('submit')->setValue('Edit');
        $form->setInputFilter(new ArticlesGroupInputFilter());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $ag->populate($form->getData(FormInterface::VALUES_AS_ARRAY));
                $this->getEntityManager()->persist($ag);
                $this->getEntityManager()->flush();
                $this->getEventManager()->trigger('editArticlesGroup.post', $this, array('articlesGroup' => $ag));
                $this->flashMessenger()->setNamespace('info')
                     ->addMessage($this->getServiceLocator()->get('translator')->translate('Articles group succesffully edited'));
                return $this->redirect()->toRoute('zfcadmin/articles-administration', array('tab' => 'articlesGroups'));
            }
        }
        return array(
            'id' => $id,
            'form' => $form,
        );
    }
    
    public function deleteGroupAction() {
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if (!$id) {
            return $this->redirect()->toRoute('zfcadmin/articles-administration',array('tab' => 'articlesGroups'));
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost()->get('del', 'No');
            if ($del == 'Yes') {
                $id = (int) $request->getPost()->get('id');
                $ag = $this->getEntityManager()->find('Article\Entity\ArticlesGroup', $id);
                if ($ag) {
                    $this->getEntityManager()->remove($ag);
                    $this->getEntityManager()->flush();
                    $this->getEventManager()->trigger('deleteArticlesGroup.post', $this, array('articlesGroup' => $ag));
                    $this->flashMessenger()->setNamespace('info')
                     ->addMessage($this->getServiceLocator()->get('translator')->translate('Articles group succesffully deleted'));
                }
            }
            return $this->redirect()->toRoute('zfcadmin/articles-administration',array('tab' => 'articlesGroups'));
         }
        return array(
            'id' => $id,
            'articlesGroup' => $this->getEntityManager()->find('Article\Entity\ArticlesGroup', $id)
        );
    }
    
}

