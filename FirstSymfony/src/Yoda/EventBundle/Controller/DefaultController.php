<?php

namespace Yoda\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($count, $firstName)
    {

        //$em = $this->container->get('doctrine')->getManager();
//        $em = $this->getDoctrine()->getManager();
//        $repo = $em->getRepository('EventBundle:Event');
//
//        $event = $repo->findOneBy(array('name' => 'Darth\'s surprise birthday party'));

        return $this->render('EventBundle:Default:index.html.twig', array('name' => $firstName, 'count' => $count));

        //return new Response($content);

        //return $this->render('EventBundle:Default:index.html.twig', array('name' => $firstName));

//        $data = array(
//            'count' => $count,
//            'firstName' => $firstName,
//            'ackbar' => 'It\'s a trap'
//
//        );
//
//        $json = json_encode($data);
//
//        $response = new Response($json);
//        $response->headers->set('Content-Type', 'application/json');
//
//        return $response;


    }
}
