<?php

namespace Article\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * An ArticlesGroup
 *
 * @ORM\Entity
 * @ORM\Table(name="articles_group")
 */
class ArticlesGroup {
    
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
     * @ORM\OneToMany(targetEntity="Article", mappedBy="articles_group")
     **/
    private $articles;
    
    public function __construct(){
        $this->articles = new ArrayCollection();
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function getArticles(){
        return $this->articles;
    }
    
    public function setId($id){
        $this->id = (isset($id)) ? $id : null;
        return $this;
    }
    
    public function setName($name){
        $this->name = (isset($name)) ? $name : null;;
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
    }
}

?>
