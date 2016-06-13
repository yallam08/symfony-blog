<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class PageController extends Controller {
    
    /**
     * 
     * @Route("/{_locale}", defaults={"_locale": "en"}, name="home_page")
     * @Method("GET")
     */
    public function indexAction() {
        return $this->render("BlogBundle:Page:index.html.twig");
    }
    
    /**
     * 
     * @Route("/about", name="about_page")
     * @Method("GET")
     */
    public function aboutAction() {
        return $this->render("BlogBundle:Page:about.html.twig");
    }
    
}
