<?php

namespace OneDayJob\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity()
 * @ORM\Table(name="odj_city")
 */
class City
{
    use ORMBehaviors\Translatable\Translatable;

	/**
	 * @ORM\Id()
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="cities")
     */
    protected $country;

    /**
     * @ORM\OneToMany(targetEntity="Company", mappedBy="city", cascade={"persist", "remove"})
     */
    protected $companies;

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
     * Set country
     *
     * @param \OneDayJob\ApiBundle\Entity\Country $country
     *
     * @return City
     */
    public function setCountry(\OneDayJob\ApiBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \OneDayJob\ApiBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->companies = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add company
     *
     * @param \OneDayJob\ApiBundle\Entity\Company $company
     *
     * @return City
     */
    public function addCompany(\OneDayJob\ApiBundle\Entity\Company $company)
    {
        $this->companies[] = $company;

        return $this;
    }

    /**
     * Remove company
     *
     * @param \OneDayJob\ApiBundle\Entity\Company $company
     */
    public function removeCompany(\OneDayJob\ApiBundle\Entity\Company $company)
    {
        $this->companies->removeElement($company);
    }

    /**
     * Get companies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompanies()
    {
        return $this->companies;
    }
}
