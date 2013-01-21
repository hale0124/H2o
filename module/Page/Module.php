<?php

namespace Page;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface
{
    
    public function onBootstrap(MvcEvent $e) {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager = $e->getApplication()->getEventManager();
        $sharedManager = $eventManager->getSharedManager();
        $serviceManager = $e->getApplication()->getServiceManager();
        // Log edit action
        $sharedManager->attach('Page\Controller\AdministrationController', 'editPage.post', function($e) use ($serviceManager) {
            $page = $e->getParam('page');
            $user = $serviceManager->get('zfcuser_auth_service')->getIdentity();
            $serviceManager->get('logger')->log('Edit page '.$page->getName().' by user '.$user->getDisplayName());
        });
        // Log Add action
        $sharedManager->attach('Page\Controller\AdministrationController', 'addPage.post', function($e) use ($serviceManager) {
            $page = $e->getParam('page');
            $user = $serviceManager->get('zfcuser_auth_service')->getIdentity();
            $serviceManager->get('logger')->log('Add page '.$page->getName().' by user '.$user->getDisplayName());
        });
        // Log delete action
        $sharedManager->attach('Page\Controller\AdministrationController', 'deletePage.post', function($e) use ($serviceManager) {
            $page = $e->getParam('page');
            $user = $serviceManager->get('zfcuser_auth_service')->getIdentity();
            $serviceManager->get('logger')->log('Delete page '.$page->getName().' by user '.$user->getDisplayName());
        });
    }
    
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

}
