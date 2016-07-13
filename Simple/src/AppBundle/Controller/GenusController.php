<?php
/**
 * Created by PhpStorm.
 * User: adrian.ispas
 * Date: 7/13/2016
 * Time: 5:20 PM
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class GenusController
{
    /**
     * @Route("/genus")
     */
    public function showAction()
    {
        return new Response('Under the Sea!');
    }
}