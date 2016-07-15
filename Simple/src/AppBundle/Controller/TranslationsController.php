<?php
/**
 * Created by PhpStorm.
 * User: adrian.ispas
 * Date: 7/15/2016
 * Time: 3:23 PM
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class TranslationsController
{
    /**
     * @Route("/translate")
     * @param $text
     * @return Response
     */
    public function indexAction()
    {
        $translated = $this->get('translator')->trans('Symfony is great');

        return new Response($translated);
    }
}