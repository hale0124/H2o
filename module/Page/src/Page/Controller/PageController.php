<?php

namespace Page\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;

class PageController extends AbstractActionController
{
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
    
    public function indexAction()
    {
        $name = $this->getEvent()->getRouteMatch()->getParam('name');
        if (!$name) {
            $this->getResponse()->setStatusCode(404);
            return; 
        }
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb = $qb->select('p')->from('Page\Entity\Page','p')
                 ->where('p.route_name = :name')
                 ->setParameter('name', $name);
        
        $query = $qb->getQuery();
        $page = $query->getOneOrNullResult();
        if(!$page){
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $vm = new ViewModel();
        $vm->setVariable('page', $page);
        return $vm;
    }
}
