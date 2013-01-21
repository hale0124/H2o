<?php

namespace Article\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Doctrine\ORM\EntityManager,
    Doctrine\Common\Collections\ArrayCollection,
    DoctrineModule\Paginator\Adapter\Collection as CollectionAdapter,
    Zend\Paginator\Paginator,
    DOMPDFModule\View\Model\PdfModel;

class ArticleController extends AbstractActionController {

    protected $em;

    const ARTICLES_PER_PAGE = 5;

    public function indexAction() {
        $em = $this->getEntityManager();
        $articles_group = (int) $this->params()->fromRoute('group');
        $items = $em->getRepository('Article\Entity\Article')
                ->findBy(array('articles_group'=> $articles_group,'active' => 1), array('created' => 'desc'));
        $doctrineCollection = new ArrayCollection($items);
        $adapter = new CollectionAdapter($doctrineCollection);
        /*
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb = $qb->select('a')->from('Article\Entity\Article','a')
                 ->leftJoin('Article\Entity\ArticlesGroup', 'ag')
                 ->where('ag.id = :id AND a.active = 1')
                 ->orderBy('a.created','DESC')
                 ->setParameters(array('id' => 2));
        
        $query = $qb->getQuery();
        $items = $query->getResult();
        $doctrineCollection = new ArrayCollection($items);
        $adapter = new CollectionAdapter($doctrineCollection);
        */
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(self::ARTICLES_PER_PAGE);
        $vm = new ViewModel();

        $vm->setVariable('articles', $paginator->getCurrentItems());
        $vm->setVariable('paginator', $paginator);
        return $vm;
    }

    public function getAction() {
        $id = (int) $this->params()->fromRoute('id');
        $article = $this->getEntityManager()->getRepository('Article\Entity\Article')->find($id);
        return new ViewModel(array(
                    'article' => $article
                ));
    }
    
    public function pdfAction()
    {
        //http://raymondkolbe.com/2012/07/01/dompdf-in-zf2/
        $id = (int) $this->params()->fromRoute('id');
        $article = $this->getEntityManager()->getRepository('Article\Entity\Article')->find($id);
        $pdf = new PdfModel(array('article' => $article));
        $pdf->setOption('filename', 'article'); // Triggers PDF download, automatically appends ".pdf"
        $pdf->setOption('paperSize', 'a4'); // Defaults to "8x11"
        $pdf->setOption('paperOrientation', 'portrait'); // Defaults to "landscape"
        return $pdf;
    }

    public function getEntityManager() {
        return $this->em;
    }

    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
    }
}