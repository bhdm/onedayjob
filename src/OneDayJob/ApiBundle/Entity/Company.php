<?php

namespace OneDayJob\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass = "CompanyRepository")
 * @ORM\Table(name="odj_company")
 *
 */
class Company
{
	/**
	 * @ORM\Id()
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string", length=150, nullable=true)
	 */
	protected $site;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	protected $phone;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $description;

	/**
	 * @ORM\ManyToOne(targetEntity="City", inversedBy="companies")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
	 */
	protected $city;

	/**
	 * @ORM\OneToMany(targetEntity="Vacancy", mappedBy="company")
	 **/
	private $vacancies;

	/**
     * @ORM\OneToOne(targetEntity="Image")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     **/
    protected $image;

    /**
     * @ORM\OneToMany(targetEntity="Gallery", mappedBy="company")
     **/
    protected $gallery;


	public function __construct()
	{
        $this->gallery = new ArrayCollection();
		$this->vacancies = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Company
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set site
     *	$this->vacancies = new ArrayCollection();
     * @param string $site
     *
     * @return Company
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set phone
     *	$this->vacancies = new ArrayCollection();
     * @param string $phone
     *
     * @return Company
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Company
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set city
     *
     * @param \OneDayJob\ApiBundle\Entity\City $city
     *
     * @return Company
     */
    public function setCity(\OneDayJob\ApiBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \OneDayJob\ApiBundle\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Add vacancy
     *
     * @param \OneDayJob\ApiBundle\Entity\Vacancy $vacancy
     *
     * @return Company
     */
    public function addVacancy($vacancy)
    {
        $this->vacancies[] = $vacancy;

        return $this;
    }

    /**
     * Remove vacancy
     *
     * @param \OneDayJob\ApiBundle\Entity\Vacancy $vacancy
     */
    public function removeVacancy($vacancy)
    {
        $this->vacancies->removeElement($vacancy);
    }

    /**
     * Get vacancies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVacancies()
    {
        return $this->vacancies;
    }

    /**
     * Set image
     *
     * @param \OneDayJob\ApiBundle\Entity\Image $image
     *
     * @return Company
     */
    public function setImage(\OneDayJob\ApiBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \OneDayJob\ApiBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add gallery
     *
     * @param \OneDayJob\ApiBundle\Entity\Gallery $gallery
     *
     * @return Company
     */
    public function addGallery($gallery)
    {
        $this->gallery[] = $gallery;

        return $this;
    }

    /**
     * Get gallery
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * @param mixed $gallery
     */
    public function setGallery($gallery)
    {
        $this->gallery = $gallery;
    }



}
