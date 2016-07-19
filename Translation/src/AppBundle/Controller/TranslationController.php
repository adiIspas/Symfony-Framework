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
     * @Route("/add_translation/{languageTranslation}")
     * @param Request $request
     * @param String $languageTranslation
     * @return Response
     */
    public function addTranslation(Request $request, $languageTranslation)
    {

        $translationTable = $this->getTranslationTable($languageTranslation);

        $form = $this->createFormBuilder($translationTable)
                    ->add('text', TextType::class, array('label' => 'Original text '))
                    ->add('language_text', ChoiceType::class, array('label' => 'Language of text ',
                        'choices'  => $this->knowLanguages))
                    ->add('translation', TextType::class, array('label' => 'Translation of text '))
                    ->add('save', SubmitType::class, array('label' => 'Add translation'))
                    ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $text = $translationTable->getText();
            $languageText = $translationTable->getLanguageText();
            $translationText = $translationTable->getTranslation();


            $translationResult = $this->translation($text,$languageText,$translationText,$languageTranslation);

            if($translationResult == 1) {
                return $this->render('successful/successful_add.html.twig', array('language' => $languageTranslation,
                    'form' => $form->createView(),
                ));
            }
            else {
                return $this->render('unsuccessful/unsuccessful_add.html.twig', array('text' => $text,
                    'translation_text' => $translationResult, 'new_translation' => $translationText,
                    'language_translation' => $languageTranslation, 'language_text' => $languageText,));
            }

        }

        return $this->render('add_translation/index.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param String $language
     * @return Entity en_translation|fr_translation
     */
    private function getTranslationTable($language)
    {
        switch ($language) {
            case 'en' : $translationTable = new en_translation(); break;
            case 'fr' : $translationTable = new fr_translation(); break;
            default : break;
        }

        return $translationTable;
    }

    /**
     * @param String $text
     * @param String $languageText
     * @param String $translationText
     * @param String $languageTranslation
     * @return bool
     */
    public function translation($text, $languageText, $translationText, $languageTranslation)
    {
        $translationTable = $this->getTranslationTable($languageTranslation);

        $translationTable->setText($text);
        $translationTable->setLanguageText($languageText);
        $translationTable->setTranslation($translationText);


        $em = $this->getDoctrine()->getManager();

        $translations = $this->search($text, $languageText, $languageTranslation);

        if(count($translations) == 0) {
            $em->persist($translationTable);
            $em->flush();

            return 1;
        }
        else {
            return $translations[0]->getTranslation($languageTranslation);
        }
    }

    /**
     * @param String $text
     * @param String $languageText
     * @param String $languageTranslation
     * @return mixed
     */
    private function search($text, $languageText, $languageTranslation)
    {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:' . $languageTranslation . '_translation');

        $query = $repository->createQueryBuilder('t')
            ->where('t.text = :text')
            ->setParameter('text', $text)
            ->andWhere('t.languageText = :language_text')
            ->setParameter('language_text', $languageText)
            ->getQuery();

        $translations = $query->getResult();

        return $translations;
    }

    /**
     * @Route("/add_translation/update/{languageText}/{text}/{languageTranslation}/{translationText}")
     * @param String $languageText
     * @param String $text
     * @param String $languageTranslation
     * @param String $translationText
     * @return Response
     */
    public function updateTranslation($languageText, $text, $languageTranslation, $translationText)
    {
        $em = $this->getDoctrine()->getManager();
        $translations = $this->search($text, $languageText, $languageTranslation);

        $translations[0]->setTranslation($translationText);
        $em->flush();

        return $this->render('successful/successful_add.html.twig', array('language' => $languageTranslation,));
    }

    /**
     * @Route("/search_translation/{languageTranslation}")
     * @return Response
     */
    public function searchTranslation(Request $request, $languageTranslation)
    {

        switch ($languageTranslation) {
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
            $languageText = $translation->getLanguageText();

            $translations = $this->search($text, $languageText, $languageTranslation);

            if (count($translations) > 0) {
                $translation_text = $translations[0]->getTranslation();
                return $this->render('successful/successful_search.html.twig', array('text' => $text, 'translation' => $translation_text,
                    'language' => $languageTranslation, 'form' => $form->createView(),
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