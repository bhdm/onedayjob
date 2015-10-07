<?php

namespace OneDayJob\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity()
 * @ORM\Table(name="odj_country")
 */
class Country
{
    use ORMBehaviors\Translatable\Translatable;

	/**
	 * @ORM\Id()
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

    /**
     * @ORM\OneToMany(targetEntity="City", mappedBy="country", cascade={"persist", "remove"})
     */
    protected $cities;

	
	public function __construct() {
		$this->cities = new ArrayCollection();
	}

    public function __toString()
    {
        return $this->getTitle();
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
     * Add city
     *
     * @param \OneDayJob\ApiBundle\Entity\City $city
     *
     * @return Country
     */
    public function addCity(\OneDayJob\ApiBundle\Entity\City $city)
    {
        $this->cities[] = $city;

        return $this;
    }

    /**
     * Remove city
     *
     * @param \OneDayJob\ApiBundle\Entity\City $city
     */
    public function removeCity(\OneDayJob\ApiBundle\Entity\City $city)
    {
        $this->cities->removeElement($city);
    }

    /**
     * Get cities
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCities()
    {
        return $this->cities;
    }
}
