<?php

namespace Application;

use Zend\Mvc\ModuleRouteListener,
    Zend\Mvc\MvcEvent,
    Doctrine\Common\Annotations\AnnotationRegistry,
    Zend\ModuleManager\ModuleManager,
    Application\Service\ApplicationLogger,
    Zend\Log\Writer\Stream,
    Zend\Log\Logger;

class Module {

    public function onBootstrap(MvcEvent $e) {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $application = $e->getApplication();
        $serviceManager = $application->getServiceManager();
        $controllerLoader = $serviceManager->get('ControllerLoader');
        $sharedManager = $eventManager->getSharedManager();
        // Add initializer to Controller Service Manager that check if controllers needs entity manager injection
        $controllerLoader->addInitializer(function ($instance) use ($serviceManager) {
                    if (method_exists($instance, 'setEntityManager')) {
                        $instance->setEntityManager($serviceManager->get('doctrine.entitymanager.orm_default'));
                    }
                });
        // Log exceptions
        $sharedManager->attach('Zend\Mvc\Application', 'dispatch.error', function($e) use ($serviceManager) {
                    if ($e->getParam('exception')) {
                        $serviceManager->get('logger')->log($e->getParam('exception'), Logger::CRIT);
                    }
                }
        );
    }

    public function init(ModuleManager $moduleManager) {
        $namespace = 'Gedmo\Mapping\Annotation';
        $lib = 'vendor/gedmo/doctrine-extensions/lib';
        AnnotationRegistry::registerAutoloadNamespace($namespace, $lib);
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getControllerPluginConfig() {
        return array(
            'factories' => array(
                'logger' => function ($sm) {
                    $serviceLocator = $sm->getServiceLocator();
                    $logger = $serviceLocator->get('logger');
                    $controllerPlugin = new Controller\Plugin\ApplicationLoggerPlugin();
                    $controllerPlugin->setLogger($logger);
                    return $controllerPlugin;
                },
            ),
        );
    }

    public function getViewHelperConfig() {
        return array(
            'factories' => array(
                'flashMessage' => function($sm) {
                    $flashmessenger = $sm->getServiceLocator()
                            ->get('ControllerPluginManager')
                            ->get('flashmessenger');
                    $message = new View\Helper\FlashMessages( );
                    $message->setFlashMessenger($flashmessenger);
                    return $message;
                },
                'mainMenu' => function($sm){
                    $nav = $sm->get('navigation')->menu();
                    $serviceLocator = $sm->getServiceLocator();
                    $acl = $serviceLocator->get('BjyAuthorize\Service\Authorize')->getAcl();
                    $role = $serviceLocator->get('BjyAuthorize\Service\Authorize')->getIdentity();
                    $nav->setAcl($acl);
                    $nav->setRole($role); // Todo replace
                    $nav->setUseAcl();
                    $nav->setUlClass('nav');
                    $nav->setTranslatorTextDomain(__NAMESPACE__); 
                    return $nav;
                }
            ),
        );
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'logger' => function($sm) {
                    $zfLogger = new Logger();
                    $writer = new Stream(ROOT_DIR . "/data/application.log");
                    $zfLogger->addWriter($writer);
                    $logger = new ApplicationLogger();
                    $logger->setServiceManager($sm);
                    return $logger->setLogger($zfLogger);
                },
            )
        );
    }

}
