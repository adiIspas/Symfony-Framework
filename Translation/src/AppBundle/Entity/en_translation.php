<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * en_translation
 *
 * @ORM\Table(name="en_translation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\en_translationRepository")
 */
class en_translation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @var string
     *
     * @ORM\Column(name="language_text", type="string", length=255)
     */
    private $languageText;

    /**
     * @var string
     *
     * @ORM\Column(name="translation", type="string", length=255)
     */
    private $translation;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return en_translation
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set languageText
     *
     * @param string $languageText
     *
     * @return en_translation
     */
    public function setLanguageText($languageText)
    {
        $this->languageText = $languageText;

        return $this;
    }

    /**
     * Get languageText
     *
     * @return string
     */
    public function getLanguageText()
    {
        return $this->languageText;
    }

    /**
     * Set translation
     *
     * @param string $translation
     *
     * @return en_translation
     */
    public function setTranslation($translation)
    {
        $this->translation = $translation;

        return $this;
    }

    /**
     * Get translation
     *
     * @return string
     */
    public function getTranslation()
    {
        return $this->translation;
    }
}

