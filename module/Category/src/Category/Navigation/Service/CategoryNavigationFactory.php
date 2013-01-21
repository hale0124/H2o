<?php
// http://stackoverflow.com/questions/12972316/how-to-set-up-2-navigations-in-zf2

namespace Category\Navigation\Service;

use Zend\Navigation\Service\AbstractNavigationFactory;
use Doctrine\ORM\EntityManager;

class CategoryNavigationFactory extends AbstractNavigationFactory{
    
    protected $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
        $this->preparePages();
    }
    
    public function getEntityManager(){
        return $this->em;
    }
    
    public function setPages(array $pages){
        $this->pages = $pages;
        return $this;
    }
    
    public function getName() {
        return "default";
    }
    
    private function buildNavigationArray($node){
        $children = $node->getChildren();
        $array = array();
        foreach($children as $child){
            if($child->hasChildren()){
                $pages = $this->buildNavigationArray($child);
            }else{
                $pages = array();        
            }
            $array[$child->getTitle()] = array(
                    'label' => $child->getTitle(),
                    'route' => $child->getCategoryRoute()->getRoute(),
                    'resource' => $child->getCategoryRoute()->getResource(),
                    'params' => $child->getCategoryRoute()->getParams(),
                    'pages' => $pages,
            );
        }
        return $array;
    }
    
    protected function preparePages(){
        $repository = $this->getEntityManager()->getRepository("Category\Entity\Category");
        $navigation = $repository->findOneByTitle('Navigation');
        $p = $this->buildNavigationArray($navigation);
        $this->setPages($p);
    }
    
}

?>
