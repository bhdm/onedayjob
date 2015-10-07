<?php

namespace OneDayJob\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity(repositoryClass="SpecializationRepository")
 * @ORM\Table(name="odj_specialization")
 */
class Specialization
{
    use ORMBehaviors\Translatable\Translatable;

	/**
	 * @ORM\Id()
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Branch", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    private $parent;

    public function __toString()
    {
        return ''.$this->getTitle();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set parent
     *
     * @param \OneDayJob\ApiBundle\Entity\Branch $parent
     *
     * @return Specialization
     */
    public function setParent(\OneDayJob\ApiBundle\Entity\Branch $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \OneDayJob\ApiBundle\Entity\Branch
     */
    public function getParent()
    {
        return $this->parent;
    }
}
