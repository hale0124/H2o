<?php

namespace Article\Entity;

use Doctrine\ORM\Mapping as ORM;
use ZfcUserDoctrineORM\Entity\User;


/**
 * An article
 *
 * @ORM\Entity
 * @ORM\Table(name="articles")
 * @property \ZfcUserDoctrineORM\Entity\User $user
 * @property boolean $active
 * @property boolean $display_wysiwyg
 * @property datetime $created
 * @property string $text
 * @property string $perex
 * @property string $blockquote
 * @property string $title
 * @property int $id
 */
class Article {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=254, nullable=true)
     */
    protected $blockquote;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $perex;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $text;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $active;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $display_wysiwyg;
    
    /**
     * @ORM\ManyToOne(targetEntity="ZfcUserDoctrineORM\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", nullable=true)
     **/
    private $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="ArticlesGroup", inversedBy="articles")
     * @ORM\JoinColumn(name="articles_group_id", referencedColumnName="id", nullable=true)
     **/
    private $articles_group;

    /**
     * ------ Getters ------
     */
    
    public function __construct($user = null){
        $this->user = $user;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getTitle(){
        return $this->title;
    }
    
    public function getBlockquote(){
        return $this->blockquote;
    }
    
    public function getPerex(){
        return $this->perex;
    }
    
    public function getText(){
        return $this->text;
    }
    
    public function getCreated(){
        return $this->created;
    }
    
    public function getActive(){
        return $this->active;
    }
    
    public function getDisplayWysiwyg(){
        return $this->display_wysiwyg;
    }
    
    public function getUser(){
        return $this->user;
    }
    
    public function getArticlesGroup(){
        return $this->articles_group;
    }
    
    /**
     * ------ Setters ------
     */
    
    public function setId($id){
        $this->id = (isset($id)) ? $id : null;
        return $this;
    }
    
    public function setTitle($title){
        $this->title = (isset($title)) ? $title : null;
        return $this;
    }
    
    public function setBlockquote($blockquote){
        $this->blockquote = (isset($blockquote)) ? $blockquote : null;
        return $this;
    }
    
    public function setPerex($perex){
        $this->perex = (isset($perex)) ? $perex : null;
        return $this;
    }
    
    public function setText($text){
        $this->text = (isset($text)) ? $text : null;
        return $this;
    }
    
    public function setCreated($created){
        if(isset($created)){
            if(is_string($created)){
                $d = new \DateTime($created);
                $this->created = $d;
            }elseif($created instanceof \DateTime){
                $this->created = $created;
            }else{
                $this->created = null; 
            }
        }else{
            $this->created = null; 
        }
        return $this;
    }
    
    public function setActive($active){
        $this->active = (isset($active)) ? (bool) $active : null;
        return $this;
    }
    
    public function setDisplayWysiwyg($display_wysiwyg){
        $this->display_wysiwyg = (isset($display_wysiwyg)) ? (bool) $display_wysiwyg : null;
        return $this;
    }
    
    public function setUser($user){
        $this->user = ($user instanceof User)?$user:null;
        return $this;
    }
    
    public function setArticlesGroup(ArticlesGroup $articles_group){
        $this->articles_group = $articles_group;
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

    
    public function populate($data = array()) {
        $this->setId(isset($data['id'])?$data['id']:null);
        $this->setTitle(isset($data['title'])?$data['title']:null);
        $this->setBlockquote(isset($data['blockquote'])?$data['blockquote']:null);
        $this->setPerex(isset($data['perex'])?$data['perex']:null);
        $this->setText(isset($data['text'])?$data['text']:null);
        $this->setCreated(isset($data['created'])?$data['created']:null);
        $this->setActive(isset($data['active'])?$data['active']:null);
        $this->setDisplayWysiwyg(isset($data['display_wysiwyg'])?$data['display_wysiwyg']:null);
        $this->setUser(isset($data['user'])?$data['user']:null);
    }
    
    
}