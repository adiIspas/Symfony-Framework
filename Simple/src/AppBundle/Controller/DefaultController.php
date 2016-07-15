<?php
/**
 * Created by PhpStorm.
 * User: adrian.ispas
 * Date: 7/15/2016
 * Time: 1:58 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/form/")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        // just setup a fresh $task object (remove the dummy data)
        $task = new Task();

        $form = $this->createFormBuilder($task)
            ->add('task', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Task'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $repository = $this->getDoctrine()
                ->getRepository('AppBundle:Task');

            $query = $repository->createQueryBuilder('t')
                ->where('t.task = :task')
                ->setParameter('task', $task->getTask())
                ->getQuery();

            $tasks = $query->getResult();

           if(count($tasks) == 0) {
               $em->persist($task);
               $em->flush();

               return $this->render('default/new.html.twig', array(
                   'form' => $form->createView(),
               ));
           }
           else {
               return new Response('This entry exist.' . dump($tasks));
            }
        }

        return $this->render('default/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}