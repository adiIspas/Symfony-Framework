<?php
/**
 * Created by PhpStorm.
 * User: adrian.ispas
 * Date: 7/14/2016
 * Time: 10:04 AM
 */

namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class LuckyController extends Controller
{
    /**
     * @Route("/lucky/{limit}")
     */
    public function numberAction($limit)
    {
        $number = rand(1,$limit);

        return $this->render('number/show.html.twig', ['number' => $number]);
    }

    /**
     * @Route("/api/lucky/{limit}")
     */
    public function apiNumberAction($limit)
    {
        $data = array(
            'lucky_number' => rand(1, $limit),
        );

        return new JsonResponse($data);
    }

    /**
     * @Route("/more/lucky/{limit}")
     * @param $limit
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function moreNumberAction($limit)
    {
        $count = rand(5, $limit);

        $numbers[] = array();
        for ($i = 0; $i < $count; $i++) {
            $numbers[] = rand(1, $limit);
        }

        return $this->render('number/show.html.twig', ['number' => 0, 'numbers' => $numbers]);
    }

    /**
     *  * @Route("/{_locale}", defaults={"_locale": "en"}, requirements={
     *     "_locale": "en|fr"
     * })
     * @param $_locale
     * @return Response
     */
    public function locationShow($_locale)
    {
        return new Response('Your location is ' . $_locale);
    }
}