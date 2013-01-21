<?php
namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Application\Service\ApplicationLogger;
use Zend\Log\Logger;


class ApplicationLoggerPlugin extends AbstractPlugin{
    
    protected $loger;
    
    public function setLogger(ApplicationLogger $loger){
        $this->loger = $loger;
        return $this;
    }
    
    public function log($message, $priority = Logger::INFO){
        $this->loger->log($message, $priority);
    }
}
