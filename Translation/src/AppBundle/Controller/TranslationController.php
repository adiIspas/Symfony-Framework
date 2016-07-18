<?php
/**
 * Created by PhpStorm.
 * User: adrian.ispas
 * Date: 7/18/2016
 * Time: 9:23 AM
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\en_translation;
use AppBundle\Entity\fr_translation;

class TranslationController extends Controller
{
    private $knowLanguages = array('RO' => 'RO', 'EN' => 'EN', 'FR' => 'FR', 'BG' => 'BG', 'HU' => 'HU', 'PL' => 'PL');

    /**
     * @Route("/")
     * @return Response
     */
    public function welcome()
    {
        return new Response('Welcome in the application!');
    }

    /**
     * @Route("/add_translation/{language}")
     * @return Response
     */
    public function addTranslation(Request $request, $language)
    {
        switch ($language) {
            case 'en' : $translation = new en_translation(); break;
            case 'fr' : $translation = new fr_translation(); break;
            default : break;
        }

        $form = $this->createFormBuilder($translation)
                    ->add('text', TextType::class, array('label' => 'Original text '))
                    ->add('language_text', ChoiceType::class, array('label' => 'Language of text ',
                        'choices'  => $this->knowLanguages))
                    ->add('translation', TextType::class, array('label' => 'Translation of text '))
                    ->add('save', SubmitType::class, array('label' => 'Add translation'))
                    ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $text = $translation->getText();
            $language_text = $translation->getLanguageText();

            $em = $this->getDoctrine()->getManager();

            $repository = $this->getDoctrine()
                ->getRepository('AppBundle:' . $language . '_translation');

            $query = $repository->createQueryBuilder('t')
                ->where('t.text = :text')
                ->setParameter('text', $text)
                ->andWhere('t.languageText = :language_text')
                ->setParameter('language_text', $language_text)
                ->getQuery();

            $translations = $query->getResult();

            if(count($translations) == 0) {
                $em->persist($translation);
                $em->flush();

                return $this->render('successful/successful_add.html.twig', array('language' => $language,
                    'form' => $form->createView(),
                ));
            }
            else {
                return $this->render('unsuccessful/unsuccessful_add.html.twig', array('text' => $text,
                    'translation_text' => $translations[0]->getTranslation(), 'new_translation' => $translation->getTranslation(),
                    'language_translation' => $language, 'language_text' => $language_text,
                ));
            }
        }

        return $this->render('add_translation/index.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/add_translation/update/{languageText}/{text}/{languageTranslation}/{translationText}")
     * @param $languageText
     * @param $text
     * @param $languageTranslation
     * @param $translationText
     * @return Response
     */
    public function updateTranslation($languageText, $text, $languageTranslation, $translationText)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:' . $languageTranslation . '_translation');

        $query = $repository->createQueryBuilder('t')
            ->where('t.text = :text')
            ->setParameter('text', $text)
            ->andWhere('t.languageText = :language_text')
            ->setParameter('language_text', $languageText)
            ->getQuery();

        $translations = $query->getResult();


        $translations[0]->setTranslation($translationText);
        $em->flush();

        return $this->render('successful/successful_add.html.twig', array('language' => $languageTranslation,
        ));
    }

    /**
     * @Route("/search_translation/{language}")
     * @return Response
     */
    public function searchTranslation(Request $request, $language)
    {

        switch ($language) {
            case 'en' : $translation = new en_translation(); break;
            case 'fr' : $translation = new fr_translation(); break;
            default : break;
        }

        $form = $this->createFormBuilder($translation)
            ->add('text', TextType::class, array('label' => 'Text for translation '))
            ->add('language_text', ChoiceType::class, array('label' => 'Language of text ',
                'choices'  => $this->knowLanguages))
            ->add('save', SubmitType::class, array('label' => 'Search translation'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $text = $translation->getText();
            $language_text = $translation->getLanguageText();

            $repository = $this->getDoctrine()
                ->getRepository('AppBundle:' . $language . '_translation');

            $query = $repository->createQueryBuilder('t')
                ->where('t.text = :text')
                ->setParameter('text', $text)
                ->andWhere('t.languageText = :language_text')
                ->setParameter('language_text', $language_text)
                ->getQuery();


            $translations = $query->getResult();

            if (count($translations) > 0) {
                $translation_text = $translations[0]->getTranslation();
                return $this->render('successful/successful_search.html.twig', array('text' => $text, 'translation' => $translation_text,
                    'language' => $language, 'form' => $form->createView(),
                ));
            } else {
                return new Response('<center>This entry doesn\'t exist.</center>');
            }
        }

        return $this->render('search_translation/index.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}