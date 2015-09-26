<?php

namespace OneDayJob\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * SpecializationTranslation
 *
 * @ORM\Table(name="odj_specialization_translation")
 * @ORM\Entity
 */
class SpecializationTranslation
{
    use ORMBehaviors\Translatable\Translation;

    /**
     * @ORM\Column(type="string", length=120)
     */
    protected $title;

    /**
     * Set title
     *
     * @param string $title
     *
     * @return SpecializationTranslation
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}
