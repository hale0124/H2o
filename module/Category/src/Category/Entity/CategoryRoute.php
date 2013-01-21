<?php

namespace Category\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * An category route
 *
 * @ORM\Entity
 * @ORM\Table(name="category_route")
 * 
 */

class CategoryRoute {
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private $resource;
    
    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $route;
    
    /** 
     * @ORM\Column(type="array", nullable=true) 
     */
    private $params = array();
    
    /**
     * @ORM\OneToOne(targetEntity="Category", inversedBy="category_route")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $category;
    
    /**
     * @ORM\ManyToOne(targetEntity="CategoryRouteType", inversedBy="category_routes")
     * @ORM\JoinColumn(name="category_route_type_id", referencedColumnName="id")
     **/
    private $category_route_type;
    
    
    public function getId(){
        return $this->id;
    }
    
    public function getResource(){
        return $this->resource;
    }
    
    public function getRoute(){
        return $this->route;
    }
    
    public function getParams(){
        return $this->params;
    }
    
    public function getCategory(){
        return $this->category;
    }

    public function getCategoryRoutetype(){
        return $this->category_route_type;
    }
    
    public function setId($id){
        $this->id = (isset($id)) ? $id : null;
        return $this;
    }
    
    public function setResource($resource){
        $this->resource = (isset($resource)) ? $resource : null;
        return $this;
    }
    
    public function setRoute($route){
        $this->route = (isset($route)) ? $route : null;
        return $this;
    }
    
    public function setParams($params){
        $this->params = $params;
        return $this;
    }
    
    public function setCategory(Category $category){
        $this->category = $category;
        return $this;
    }
    
    public function setCategoryRouteType(CategoryRouteType $category_route_type){
        $this->category_route_type = $category_route_type;
        return $this;
    }
}

?>
