<?php

namespace App\Service;

use Twig\Environment;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

class Pagination{

    private $entityClass;
    private $limit = 6;
    private $currentPage = 1;
    private $manager;
    private $route;

    public function __construct(ObjectManager $manager, Environment $twig, RequestStack $request){
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
        $this->manager = $manager;
        $this->twig    = $twig;
    }

    public function setRoute($route){
        $this->route = $route;

        return $this;
    }

    public function getRoute(){
        return $route;
    }
    
    public function display()
    {
       $this->twig->display('elements/pagination.html.twig',[
           'page' => $this->currentPage,
           'pages' => $this->getPages(),
           'route' => $this->route
       ]);
    }

    public function GetPages()
    {
        //count entry in table
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());
        //Calculate Number of pages
        $pages = ceil($total / $this->limit);
        
        return $pages;
    }

    public function getData()
    {
        // Calculate start page
        $offset = $this->currentPage * $this->limit - $this->limit;
        // find element in repository
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([], [], $this->limit, $offset);
        // return element
        return $data;
    }
    
    public function setPage($page)
    {
        $this->currentPage = $page;
        return $this;
    }
    public function getPage()
    {
        return $this->currentPage;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }
    public function getLimit()
    {
        return $this->limit;
    }

    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }


}