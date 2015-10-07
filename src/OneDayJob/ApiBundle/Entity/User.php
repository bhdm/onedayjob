<?php

namespace OneDayJob\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Entity
 * @ORM\Table(name="odj_user")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	protected $first_name;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	protected $last_name;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $get_news_by_email = false;

	/**
	 * @ORM\OneToOne(targetEntity="Company")
	 * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
	 **/
	protected $company;

	/**
	 * @ORM\Column(type="string", length=10, nullable=true)
	 */
	protected $type;

	/**
	 * @ORM\Column(type="date", nullable=true)
	 */
	protected $birthdate;

	/**
	 * @ORM\OneToOne(targetEntity="Resume", mappedBy="employee")
	 **/
	protected $resume;

	/**
	 * @ORM\OneToOne(targetEntity="Image")
	 * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
	 **/
	protected $image;

	/**
	 * @ORM\ManyToMany(targetEntity="Vacancy", inversedBy="favorite_user")
	 * @ORM\JoinTable(name="odj_favorite_vacancy")
	 **/
	protected $favorite_vacancy;

	/**
     * @ORM\ManyToMany(targetEntity="Vacancy", inversedBy="response_employee")
     * @ORM\JoinTable(name="odj_response_vacancy")
     **/
    protected $response_vacancy;

    /**
	 * @ORM\ManyToMany(targetEntity="Company")
	 * @ORM\JoinTable(name="odj_favorite_company",
	 *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="company_id", referencedColumnName="id")}
	 *      )
	 **/
    protected $favorite_company;

    /**
	 * @ORM\ManyToMany(targetEntity="Resume", mappedBy="favorite_user")
	 */
	protected $favorite_resume;

    /**
     * @ORM\Column(type="integer")
     */
    protected $balance;

    /**
	 * @ORM\Column(type="string", length=20, nullable=true)
	 */
	protected $social_id;

	protected $favorite_company_array = array();

	public function __construct()
	{
		parent::__construct();
		$this->favorite_vacancy = new ArrayCollection();
		$this->response_vacancy = new ArrayCollection();
		$this->favorite_company = new ArrayCollection();
		$this->favorite_resume  = new ArrayCollection();
		$this->balance = 0;
	}

	/**
     * @ORM\PrePersist
     */
    public function setUsernameValue()
    {
        $this->username = $this->email;
    }

	public function getFavoriteCompanyArray()
	{
		if (empty($this->favorite_company_array)) {
			foreach ($this->getFavoriteCompany() as $company) {
				$this->favorite_company_array[] = $company->getId();
			}
		}

		return $this->favorite_company_array;
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
	 * Set first_name
	 *
	 * @param string $firstName
	 * @return User
	 */
	public function setFirstName($firstName)
	{
		$this->first_name = $firstName;

		return $this;
	}

	/**
	 * Get first_name
	 *
	 * @return string 
	 */
	public function getFirstName()
	{
		return $this->first_name;
	}

	/**
	 * Set last_name
	 *
	 * @param string $lastName
	 * @return User
	 */
	public function setLastName($lastName)
	{
		$this->last_name = $lastName;

		return $this;
	}

	/**
	 * Get last_name
	 *
	 * @return string 
	 */
	public function getLastName()
	{
		return $this->last_name;
	}

	/**
	 * Set get_news_by_email
	 *
	 * @param boolean $get_news_by_email
	 * @return User
	 */
	public function setGetNewsByEmail($get_news_by_email)
	{
		$this->get_news_by_email = $get_news_by_email;

		return $this;
	}

	/**
	 * Get get_news_by_email
	 *
	 * @return boolean 
	 */
	public function getGetNewsByEmail()
	{
		return $this->get_news_by_email;
	}

	/**
	 * Set company
	 *
	 * @param \OneDayJob\ApiBundle\Entity\Company $company
	 * @return User
	 */
	public function setCompany(\OneDayJob\ApiBundle\Entity\Company $company = null)
	{
		$this->company = $company;

		return $this;
	}

	/**
	 * Get company
	 *
	 * @return \OneDayJob\ApiBundle\Entity\Company 
	 */
	public function getCompany()
	{
		return $this->company;
	}

	/**
	 * Set type
	 *
	 * @param string $type
	 * @return User
	 */
	public function setType($type = 'employee')
	{
		$this->type = $type == 'employee' ? 'employee' : 'employer';

		return $this;
	}

	/**
	 * Get type
	 *
	 * @return string 
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Set birthdate
	 *
	 * @param \DateTime $birthdate
	 * @return User
	 */
	public function setBirthdate($birthdate)
	{
		$this->birthdate = $birthdate;

		return $this;
	}

	/**
	 * Get birthdate
	 *
	 * @return \DateTime 
	 */
	public function getBirthdate()
	{
		return $this->birthdate;
	}

	/**
	 * Set resume
	 *
	 * @param \OneDayJob\ApiBundle\Entity\Resume $resume
	 * @return User
	 */
	public function setResume(\OneDayJob\ApiBundle\Entity\Resume $resume = null)
	{
		$this->resume = $resume;

		return $this;
	}

	/**
	 * Get resume
	 *
	 * @return \OneDayJob\ApiBundle\Entity\Resume 
	 */
	public function getResume()
	{
		return $this->resume;
	}

	/**
	 * Add favorite_vacancy
	 *
	 * @param \OneDayJob\ApiBundle\Entity\Vacancy $favoriteVacancy
	 * @return User
	 */
	public function addFavoriteVacancy(\OneDayJob\ApiBundle\Entity\Vacancy $favoriteVacancy)
	{
		$this->favorite_vacancy[] = $favoriteVacancy;

		return $this;
	}

	/**
	 * Remove favorite_vacancy
	 *
	 * @param \OneDayJob\ApiBundle\Entity\Vacancy $favoriteVacancy
	 */
	public function removeFavoriteVacancy(\OneDayJob\ApiBundle\Entity\Vacancy $favoriteVacancy)
	{
		$this->favorite_vacancy->removeElement($favoriteVacancy);
	}

	/**
	 * Get favorite_vacancy
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getFavoriteVacancy()
	{
		return $this->favorite_vacancy;
	}

	/**
	 * Set image
	 *
	 * @param \OneDayJob\ApiBundle\Entity\Image $image
	 * @return User
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
     * Add response_vacancy
     *
     * @param \OneDayJob\ApiBundle\Entity\Vacancy $responseVacancy
     * @return User
     */
    public function addResponseVacancy(\OneDayJob\ApiBundle\Entity\Vacancy $responseVacancy)
    {
        $this->response_vacancy[] = $responseVacancy;

        return $this;
    }

    /**
     * Remove response_vacancy
     *
     * @param \OneDayJob\ApiBundle\Entity\Vacancy $responseVacancy
     */
    public function removeResponseVacancy(\OneDayJob\ApiBundle\Entity\Vacancy $responseVacancy)
    {
        $this->response_vacancy->removeElement($responseVacancy);
    }

    /**
     * Get response_vacancy
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResponseVacancy()
    {
        return $this->response_vacancy;
    }

    /**
     * Set balance
     *
     * @param integer $balance
     * @return User
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return integer 
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set social_id
     *
     * @param string $socialId
     * @return User
     */
    public function setSocialId($socialId)
    {
        $this->social_id = $socialId;

        return $this;
    }

    /**
     * Get social_id
     *
     * @return string 
     */
    public function getSocialId()
    {
        return $this->social_id;
    }

    /**
     * Add favoriteCompany
     *
     * @param \OneDayJob\ApiBundle\Entity\Company $favoriteCompany
     *
     * @return User
     */
    public function addFavoriteCompany(\OneDayJob\ApiBundle\Entity\Company $favoriteCompany)
    {
        $this->favorite_company[] = $favoriteCompany;

        return $this;
    }

    /**
     * Remove favoriteCompany
     *
     * @param \OneDayJob\ApiBundle\Entity\Company $favoriteCompany
     */
    public function removeFavoriteCompany(\OneDayJob\ApiBundle\Entity\Company $favoriteCompany)
    {
        $this->favorite_company->removeElement($favoriteCompany);
    }

    /**
     * Get favoriteCompany
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFavoriteCompany()
    {
        return $this->favorite_company;
    }

    public function getImageSrc()
    {
    	return $this->image ? $this->image->getSrc() : '/bundles/onedayjobfrontend/content/img5.png';
    }

    /**
     * Add favoriteResume
     *
     * @param \OneDayJob\ApiBundle\Entity\Resume $favoriteResume
     *
     * @return User
     */
    public function addFavoriteResume(\OneDayJob\ApiBundle\Entity\Resume $favoriteResume)
    {
        $this->favorite_resume[] = $favoriteResume;

        return $this;
    }

    /**
     * Remove favoriteResume
     *
     * @param \OneDayJob\ApiBundle\Entity\Resume $favoriteResume
     */
    public function removeFavoriteResume(\OneDayJob\ApiBundle\Entity\Resume $favoriteResume)
    {
        $this->favorite_resume->removeElement($favoriteResume);
    }

    /**
     * Get favoriteResume
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFavoriteResume()
    {
        return $this->favorite_resume;
    }
}
