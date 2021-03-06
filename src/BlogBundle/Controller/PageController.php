<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Enquiry;
use BlogBundle\Form\Type\EnquiryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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


        $blogs = $this->getDoctrine()
                ->getRepository('BlogBundle:Blog')
                ->getLatestBlogs();


        // Another method for quering the blogs, but using the entity repository is preferred.
//        $em = $this->getDoctrine()->getEntityManager();
//        $blogs = $em->createQueryBuilder()
//                ->select('b')
//                ->from('BlogBundle:Blog', 'b')
//                ->addOrderBy('b.createdAt', 'DESC')
//                ->getQuery()->getResult();

        return $this->render('BlogBundle:Page:index.html.twig', ['blogs' => $blogs]);
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
     * @Method("GET|POST")
     */
    public function contactAction(Request $request) {

        $enquiry = new Enquiry();
        $form = $this->createForm(EnquiryType::class, $enquiry, [
            'action' => $this->generateUrl('contact_page'),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message = \Swift_Message::newInstance()
                    ->setSubject($form->get("subject")->getData())
                    ->setFrom($form->get("email")->getData())
                    ->setTo($this->container->getParameter('symfonyblog.emails.contact'))
                    ->setBody($this->renderView(
                            'BlogBundle:Page:contactEmail.html.twig', ['enquiry' => $enquiry]), 'text/html'
                    )
            ;
            $this->get('mailer')->send($message);

            $this->addFlash('blogger-notice', 'Your enquiry was sent. Thank you!');

            // Redirect to prevent re-post on refresh
            return $this->redirect($this->generateUrl('contact_page'));
        }

        return $this->render('BlogBundle:Page:contact.html.twig', ['form' => $form->createView()]);
    }

    /**
     * 
     * @Route(
     *      "/{id}",
     *      requirements={"id": "\d+"},
     *      name="show_page",
     * )
     * @Method("GET")
     */
    public function showAction($id) {
        $blog = $this->getDoctrine()->getRepository('BlogBundle:Blog')->find($id);
        if (!$blog) {
            throw $this->createNotFoundException('No blog post found for id ' . $id);
        }

        $comments = $this->getDoctrine()
                ->getRepository('BlogBundle:Comment')
                ->getCommentsForBlog($id);

        return $this->render('BlogBundle:Page:show.html.twig', [
                    'blog' => $blog,
                    'comments' => $comments
        ]);
    }

}
