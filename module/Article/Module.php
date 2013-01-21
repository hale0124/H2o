<?php

namespace Article;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface
{
    
    public function onBootstrap(MvcEvent $e) {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager = $e->getApplication()->getEventManager();
        $sharedManager = $eventManager->getSharedManager();
        $serviceManager = $e->getApplication()->getServiceManager();
        // Log edit action
        $sharedManager->attach('Article\Controller\AdministrationController', 'editArticle.post', function($e) use ($serviceManager) {
            $article = $e->getParam('article');
            $user = $serviceManager->get('zfcuser_auth_service')->getIdentity();
            $serviceManager->get('logger')->log('Edit article '.$article->getTitle().' by user '.$user->getDisplayName());
        });
        // Log Add action
        $sharedManager->attach('Article\Controller\AdministrationController', 'addArticle.post', function($e) use ($serviceManager) {
            $article = $e->getParam('article');
            $user = $serviceManager->get('zfcuser_auth_service')->getIdentity();
            $serviceManager->get('logger')->log('Add article '.$article->getTitle().' by user '.$user->getDisplayName());
        });
        // Log delete action
        $sharedManager->attach('Article\Controller\AdministrationController', 'deleteArticle.post', function($e) use ($serviceManager) {
            $article = $e->getParam('article');
            $user = $serviceManager->get('zfcuser_auth_service')->getIdentity();
            $serviceManager->get('logger')->log('Delete article '.$article->getTitle().' by user '.$user->getDisplayName());
        });
        // =====================================================================
        // Log edit action
        $sharedManager->attach('Article\Controller\AdministrationController', 'editArticlesGroup.post', function($e) use ($serviceManager) {
            $articlesGroup = $e->getParam('articlesGroup');
            $user = $serviceManager->get('zfcuser_auth_service')->getIdentity();
            $serviceManager->get('logger')->log('Edit articles group '.$articlesGroup->getName().' by user '.$user->getDisplayName());
        });
        // Log add action
        $sharedManager->attach('Article\Controller\AdministrationController', 'addArticlesGroup.post', function($e) use ($serviceManager) {
            $articlesGroup = $e->getParam('articlesGroup');
            $user = $serviceManager->get('zfcuser_auth_service')->getIdentity();
            $serviceManager->get('logger')->log('Add articles group '.$articlesGroup->getName().' by user '.$user->getDisplayName());
        });
        // Log delete action
        $sharedManager->attach('Article\Controller\AdministrationController', 'deleteArticlesGroup.post', function($e) use ($serviceManager) {
            $articlesGroup = $e->getParam('articlesGroup');
            $user = $serviceManager->get('zfcuser_auth_service')->getIdentity();
            $serviceManager->get('logger')->log('Delete articles group '.$articlesGroup->getName().' by user '.$user->getDisplayName());
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
