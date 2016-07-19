<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        //$request->setLocale('fr');
        //$locale = $request->getLocale();

        //$translated = $this->get('translator')->trans('Symfony is great');

        return $this->render('translation/translation.html.twig');

        //$recipient = $this->container->getParameter('locale1');

        //return new Response('Locale is: ' . $recipient . '<br>' . 'Translated is: ' . $translated);
    }
}
