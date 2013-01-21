<?php
namespace Category\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 *
 * @ORM\Entity
 * @ORM\Table(name="category_route_type")
 */
class CategoryRouteType {
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $name;
    
    /**
     * @ORM\OneToMany(targetEntity="CategoryRoute", mappedBy="category_route_type")
     **/
    private $category_routes;
    
    public function getId(){
        return $this->id;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function getCategoryRoutes(){
        return $this->category_routes;
    }
    
    public function setId($id){
        $this->id = $id;
        return $this;
    }
    
    public function setName($name){
        $this->name = $name;
        return $this;
    }
    
    public function setCategoryRoutes($category_routes){
        $this->category_routes = $category_routes;
        return $this;
    }
    
    
}