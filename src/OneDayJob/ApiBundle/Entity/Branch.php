<?php

namespace OneDayJob\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity()
 * @ORM\Table(name="odj_branch")
 */
class Branch
{
    use ORMBehaviors\Translatable\Translatable;

	/**
	 * @ORM\Id()
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Specialization", mappedBy="parent")
     **/
    private $children;

    public function __construct() {
        $this->children = new ArrayCollection();
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
     * Add child
     *
     * @param \OneDayJob\ApiBundle\Entity\Specialization $child
     *
     * @return Branch
     */
    public function addChild( $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \OneDayJob\ApiBundle\Entity\Specialization $child
     */
    public function removeChild( $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }
}
