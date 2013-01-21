<?php
namespace Application\View\Helper;
use Zend\View\Helper\AbstractHelper;
use Zend\Json\Json;
/*
 * $this->flashMessenger()->setNamespace('warning')->addMessage('Mail sending failed!');
 */
class FlashMessages extends AbstractHelper {

    protected $flashMessenger;

    public function setFlashMessenger($flashMessenger) {
        $this->flashMessenger = $flashMessenger;
    }

    public function __invoke() {

        $namespaces = array(
            'error', 'success',
            'info' //,'warning' 
        );

        // messages as string
        $msgs = array();
        foreach ($namespaces as $ns) {

            $this->flashMessenger->setNamespace($ns);

            $messages = array_merge(
                    $this->flashMessenger->getMessages(), $this->flashMessenger->getCurrentMessages()
            );

            if (!$messages)
                continue;
            
            foreach($messages as $m){
                $msgs[$ns][] = $m;
            }
        }
        $this->flashMessenger->clearCurrentMessages();
        if(count($msgs)>0){
            $json = Json::encode($msgs, Json::TYPE_ARRAY);
        }else{
            $json = Json::encode(false);
        }
        return "<div id='flash_messages' class='hidden' data-flash-messages='$json'></div>";
    }

}