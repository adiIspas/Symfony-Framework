<?php
/**
 * Created by PhpStorm.
 * User: adrian.ispas
 * Date: 7/14/2016
 * Time: 12:14 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Fruit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class DBController extends Controller
{
    /**
     * @Route("/db")
     */
    public function welcome()
    {
       return new Response('Welcome!');
    }

    /**
     * @Route("/db/add/{name}/{color}")
     * @param $name
     * @param $color
     * @return Response
     */
    public function addFruit($name,$color)
    {
        $fruit = new Fruit();
        $fruit->setName($name);
        $fruit->setColor($color);

        $em = $this->getDoctrine()->getManager();

        $em->persist($fruit);
        $em->flush();

        return new Response('Saved ' . $fruit->getName() . ' with color ' . $fruit->getColor() . ' and id ' . $fruit->getId() . ' in DB.');
    }

    /**
     * @Route("/db/search/{id}")
     * @param $id
     * @return Response
     */
    public function searchFruit($id)
    {
        $fruit = $this->getDoctrine()->getRepository('AppBundle:Fruit')->find($id);

        if($fruit) {
           return new Response('The fruit with id ' . $id . ' is ' . $fruit->getName());
        }
        else
            return new Response('The fruit with id ' . $id . ' not exist ');
    }

    /**
     * @Route("/db/advsearch/{name}")
     * @param $name
     * @return Response
     */
    public function advancedSearch($name) {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Fruit');

        $query = $repository->createQueryBuilder('f')
            ->where('f.name = :name')
            ->setParameter('name', $name)
            ->orderBy('f.name', 'ASC')
            ->getQuery();

        $fruits = $query->getResult();

        return new Response('' . dump($fruits));


    }

    /**
     * @Route("/db/update/{name}/{color}")
     * @param $name
     * @param $color
     * @return Response
     */
    public function updateColor($name,$color)
    {
        $em = $this->getDoctrine()->getManager();
        $fruit = $em->getRepository('AppBundle:Fruit')->findByName($name);

        if($fruit) {
            $fruit[0]->setColor($color);
            $em->flush();
            return new Response('The fruit ' . $name . ' has been update color at ' . $color);
        }
        else
            return new Response('The fruit ' . $name . ' not exist.');
    }

    /**
     * @Route("db/delete/{name}")
     * @param $name
     * @return Response
     */
    public function deleteFruit($name)
    {
        $em = $this->getDoctrine()->getManager();
        $fruit = $em->getRepository('AppBundle:Fruit')->findByName($name);

        if($fruit) {
            $em->remove($fruit[0]);
            $em->flush();
            return new Response('The fruit ' . $name . ' has been deleted.');
        }
        else
            return new Response('The fruit ' . $name . ' not exist.');
    }
}