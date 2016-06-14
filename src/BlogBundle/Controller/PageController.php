<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Enquiry;
use BlogBundle\Form\Type\EnquiryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller {
    
    /**
     * 
     * @Route(
     *      "/{_locale}", 
     *      defaults={"_locale": "en"},
     *      requirements={"_locale": "en|ar"},
     *      name="home_page"
     * )
     */
    public function indexAction() {
        return $this->render('BlogBundle:Page:index.html.twig');
    }
    
    /**
     * 
     * @Route("/about", name="about_page")
     */
    public function aboutAction() {
        return $this->render('BlogBundle:Page:about.html.twig');
    }
    
    /**
     * 
     * @Route("/contact", name="contact_page")
     */
    public function contactAction(Request $request) {
        
        $enquiry = new Enquiry();
        $form = $this->createForm(EnquiryType::class, $enquiry, [
            'action' => $this->generateUrl('contact_page'),
            'method' => 'POST',
        ]);
        
        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if($form->isValid()) {
                //TODO: Some action such as send an email
                
                // Redirect to prevent re-post on refresh
                return $this->redirect($this->generateUrl('contact_page'));
            }
        }
        
        return $this->render('BlogBundle:Page:contact.html.twig', ['form' => $form->createView()]);
    }
    
}
