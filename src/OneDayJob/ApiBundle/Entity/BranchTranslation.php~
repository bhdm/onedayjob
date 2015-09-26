<?php

namespace OneDayJob\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * BranchTranslation
 *
 * @ORM\Table(name="odj_branch_translation")
 * @ORM\Entity
 */
class BranchTranslation
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
     * @return BranchTranslation
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
