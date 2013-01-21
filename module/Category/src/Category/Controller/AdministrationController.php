<?php
namespace Category\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;
use Category\Entity\Category;
use Category\Entity\CategoryRoute;
use Category\Entity\CategoryRouteType;
use Zend\Form\Form;
use Category\Form\CategoryForm;

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

    public function addAction() {
        $parent_id = $this->getEvent()->getRouteMatch()->getParam('id');
        $em = $this->getEntityManager();
        $category = $em->getRepository('Category\Entity\Category');
        $root = $category->findOneByTitle('Navigation');
        if ($parent_id) {
            $parentCategory = $category->find($parent_id);
        } else {
            $parentCategory = $root;
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $type = $data->category_route_type;
            $form = new CategoryForm($this->getServiceLocator()->get('translator'), $this->getEntityManager(), $type);
            $form->setData($data);
            if (isset($data->submit)) {
                // Add category here
                $this->addCategory($parentCategory, $data);
                $this->flashMessenger()->setNamespace('info')
                     ->addMessage($this->getServiceLocator()->get('translator')->translate('Category succesffully added'));
                return $this->redirect()->toRoute('zfcadmin/categories-administration');
            }
        } else {
            $form = new CategoryForm($this->getServiceLocator()->get('translator'), $this->getEntityManager());
        }

        $vm = new ViewModel();
        $vm->setVariable('category', $category);
        $vm->setVariable('root', $root);
        $vm->setVariable('parent_id', $parent_id);
        $vm->setVariable('form', $form);
        return $vm;
    }

    public function editAction() {
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        $em = $this->getEntityManager();
        $category = $em->getRepository('Category\Entity\Category');
        $root = $category->findOneByTitle('Navigation');
        $request = $this->getRequest();

        if ($id) {
            $selectedCategory = $category->find($id);
            $type = $selectedCategory->getCategoryRoute()->getCategoryRouteType()->getId();

            if ($request->isPost()) {
                $data = $request->getPost();
                $form = new CategoryForm($this->getServiceLocator()->get('translator'), $this->getEntityManager(), $data->category_route_type);
                $form->setData($data);
                if (isset($data->submit)) {
                    // Update category here
                    $this->updateCategory($selectedCategory, $data);
                    $this->flashMessenger()->setNamespace('info')
                     ->addMessage($this->getServiceLocator()->get('translator')->translate('Category succesffully updated'));
                    return $this->redirect()->toRoute('zfcadmin/categories-administration');
                }
            } else {
                $form = new CategoryForm($this->getServiceLocator()->get('translator'), $this->getEntityManager(), $type);
                // set defaults
                $defaults = new \ArrayObject;
                $defaults['category_route_type'] = $type;
                $defaults['id'] = $id;
                $defaults['category_name'] = $selectedCategory->getTitle();
                switch ($type) {
                    case 1:
                        $params = $selectedCategory->getCategoryRoute()->getParams();
                        $defaults['page_route_name'] = $params['name'];
                        break;
                    case 2:
                        $params = $selectedCategory->getCategoryRoute()->getParams();
                        $defaults['articles_group_id'] = $params['group'];
                        break;
                    case 3:
                        $defaults['route'] = $selectedCategory->getCategoryRoute()->getRoute();
                        break;
                }
                $form->bind($defaults);
            }
        } else {
            return $this->redirect()->toRoute('zfcadmin/categories-administration', array('action' => 'add'));
        }

        $vm = new ViewModel();
        $vm->setVariable('category', $category);
        $vm->setVariable('root', $root);
        $vm->setVariable('id', $id);
        $vm->setVariable('form', $form);
        return $vm;
    }

    public function deleteAction() {
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        $em = $this->getEntityManager();
        $category = $em->getRepository('Category\Entity\Category');
        $root = $category->findOneByTitle('Navigation');
        $request = $this->getRequest();
        
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if (!$id) {
            return $this->redirect()->toRoute('zfcadmin/categories-administration');
        }
        
        if ($request->isPost()) {
            $del = $request->getPost()->get('del', 'No');
            if ($del == 'Yes') {
                $id = (int) $request->getPost()->get('id');
                // delete category
                $this->deleteCategory($id);
                $this->flashMessenger()->setNamespace('info')
                     ->addMessage($this->getServiceLocator()->get('translator')->translate('Category succesffully deleted'));
                return $this->redirect()->toRoute('zfcadmin/categories-administration');
            }
            return $this->redirect()->toRoute('zfcadmin/categories-administration');
        }
        $vm = new ViewModel();
        $vm->setVariable('category', $category);
        $vm->setVariable('root', $root);
        $vm->setVariable('id', $id);
        return $vm;
    }

    public function indexAction() {
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        $em = $this->getEntityManager();
        $category = $em->getRepository('Category\Entity\Category');
        $root = $category->findOneByTitle('Navigation');

        $vm = new ViewModel();
        $vm->setVariable('category', $category);
        $vm->setVariable('root', $root);
        $vm->setVariable('id', $id);
        return $vm;
    }

    // Example action returning json
    public function jsonAction() {
        $result = new JsonModel(array(
                    'some_parameter' => 'some value',
                    'success' => true,
                ));
        return $result;
    }
    
    private function prepareData($type, $form_data){
        switch($type){
            case 1:
                $route_name = $form_data->page_route_name;
                $category_name = $form_data->category_name;
                $route = 'page';
                $resource = 'route/page';
                $params = array('name' => $route_name);
            break;
            case 2:
                $ag_id = $form_data->articles_group_id;
                $category_name = $form_data->category_name;
                $route = 'articles';
                $resource = 'route/articles';
                $params = array('group' => $ag_id);
            break;
            case 3:
                $category_name = $form_data->category_name;
                $route = $form_data->route;
                $resource = 'route/'.$route;
                $params = null;
            break;
        }
        return array(
                    'category_name' => $category_name, 
                    'route' => $route,
                    'resource' => $resource,
                    'params' => $params
        );
    }
    
    public function addCategory($parent, $form_data){
        $type = $form_data->category_route_type;
        $categoryRouteType = $this->getEntityManager()->getRepository('Category\Entity\CategoryRouteType')->find($type);
        
        $data = $this->prepareData($type, $form_data);
        
        // new Category
        $newCategory = new Category();
        $newCategory->setTitle($data['category_name']);
        $newCategory->setParent($parent);
        $this->getEntityManager()->persist($newCategory);

        // new Category route
        $categoryRoute = new CategoryRoute();
        $categoryRoute->setCategoryRouteType($categoryRouteType);
        $categoryRoute->setCategory($newCategory);
        $categoryRoute->setParams($data['params']);
        $categoryRoute->setResource($data['resource']);
        $categoryRoute->setRoute($data['route']);
        $this->getEntityManager()->persist($categoryRoute);
        
        $this->getEntityManager()->flush();
        $this->getEventManager()->trigger('addCategory.post', $this, array('category' => $newCategory));
    }
    
    public function updateCategory($category, $form_data){
        $type = $form_data->category_route_type;
        $categoryRouteType = $this->getEntityManager()->getRepository('Category\Entity\CategoryRouteType')->find($type);
        
        $data = $this->prepareData($type, $form_data);
        
        $category->setTitle($data['category_name']);
        $this->getEntityManager()->persist($category);

        // Category route
        $categoryRoute = $category->getCategoryRoute();
        $categoryRoute->setCategoryRouteType($categoryRouteType);
        $categoryRoute->setParams($data['params']);
        $categoryRoute->setResource($data['resource']);
        $categoryRoute->setRoute($data['route']);
        $this->getEntityManager()->persist($categoryRoute);
        
        $this->getEntityManager()->flush();
        $this->getEventManager()->trigger('editCategory.post', $this, array('category' => $category));
    }
    
    public function deleteCategory($id){
        $category = $this->getEntityManager()->getRepository('Category\Entity\Category')->find($id);
        $this->getEntityManager()->remove($category);
        $this->getEntityManager()->flush();
        $this->getEventManager()->trigger('deleteCategory.post', $this, array('category' => $category));
    }

}