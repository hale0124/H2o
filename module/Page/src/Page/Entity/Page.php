<?php

namespace Page\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A page
 *
 * @ORM\Entity
 * @ORM\Table(name="pages")
 * @property boolean $active
 * @property boolean $display_wysiwyg
 * @property string $content
 * @property string $route_name
 * @property string $name
 * @property int $id
 */
class Page {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length=150, unique=true, nullable=true)
     */
    protected $route_name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $display_wysiwyg;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $active;
    
    /**
     * ------ Getters ------
     */
    
    public function __construct($user = null){
        $this->user = $user;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getRouteName(){
        return $this->route_name;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function getContent(){
        return $this->content;
    }
    
    public function getDisplayWysiwyg(){
        return $this->display_wysiwyg;
    }
    
    public function getActive(){
        return $this->active;
    }
    /**
     * ------ Setters ------
     */
    
    public function setId($id){
        $this->id = (isset($id)) ? $id : null;
        return $this;
    }
    
    public function setRouteName($route_name){
        $this->route_name = (isset($route_name)) ? $route_name : null;
        return $this;
    }
    
    public function setName($name){
        $this->name = (isset($name)) ? $name : null;
        return $this;
    }
    
    public function setContent($content){
        $this->content = (isset($content)) ? $content : null;
        return $this;
    }
    
    public function setDisplayWysiwyg($display_wysiwyg){
        $this->display_wysiwyg = (isset($display_wysiwyg)) ? $display_wysiwyg : null;
        return $this;
    }
    
    public function setActive($active){
        $this->active = (isset($active)) ? (bool) $active : null;
        return $this;
    }
    
    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy() {
        return get_object_vars($this);
    }

    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function populate($data = array()) {
        $this->setId(isset($data['id'])?$data['id']:null);
        $this->setName(isset($data['name'])?$data['name']:null);
        $this->setRouteName(isset($data['route_name'])?$data['route_name']:null);
        $this->setContent(isset($data['content'])?$data['content']:null);
        $this->setDisplayWysiwyg(isset($data['display_wysiwyg'])?$data['display_wysiwyg']:null);
        $this->setActive(isset($data['active'])?$data['active']:null);
    }
    
    
}