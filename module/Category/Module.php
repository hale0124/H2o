<?php

namespace Category;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
  
    public function getViewHelperConfig() {
        return array(
            'factories' => array(
                'navlist' => function($sm) {
                    return new View\Helper\Navlist();
                },
            ),
        );
    }
    
    public function getServiceConfig() {
        return array(
            'factories' => array(
                'navigation' => function($sm) {
                    $em = $sm->get('doctrine.entitymanager.orm_default');
                    $factory = new Navigation\Service\CategoryNavigationFactory($em);
                    return $factory->createService($sm);
                },
            ),
        );
    }
    
    public function onBootstrap(MvcEvent $e) {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager = $e->getApplication()->getEventManager();
        $sharedManager = $eventManager->getSharedManager();
        $serviceManager = $e->getApplication()->getServiceManager();
        // Log edit action
        $sharedManager->attach('Category\Controller\AdministrationController', 'editCategory.post', function($e) use ($serviceManager) {
            $category = $e->getParam('category');
            $user = $serviceManager->get('zfcuser_auth_service')->getIdentity();
            $serviceManager->get('logger')->log('Edit category '.$category->getTitle().' by user '.$user->getDisplayName());
        });
        // Log Add action
        $sharedManager->attach('Category\Controller\AdministrationController', 'addCategory.post', function($e) use ($serviceManager) {
            $category = $e->getParam('category');
            $user = $serviceManager->get('zfcuser_auth_service')->getIdentity();
            $serviceManager->get('logger')->log('Add category '.$category->getTitle().' by user '.$user->getDisplayName());
        });
        // Log delete action
        $sharedManager->attach('Category\Controller\AdministrationController', 'deleteArticle.post', function($e) use ($serviceManager) {
            $category = $e->getParam('category');
            $user = $serviceManager->get('zfcuser_auth_service')->getIdentity();
            $serviceManager->get('logger')->log('Delete category '.$category->getTitle().' by user '.$user->getDisplayName());
        });
    }
    
}
