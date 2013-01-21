<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use Zend\Log\Logger;
use Zend\Log\LoggerInterface;

class ApplicationLogger extends EventProvider implements ServiceManagerAwareInterface
{
    protected $serviceManager;
    
    protected $logger;
    
    public function log($message, $priority = Logger::INFO){
        $this->getLogger()->log($priority, $message);
    }
    
    public function getLogger(){
        return $this->logger;
    }
    
    public function setLogger(LoggerInterface $logger){
        $this->logger = $logger;
        return $this;
    }
    
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }
}
