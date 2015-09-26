<?php

namespace OneDayJob\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="VacancyRepository")
 * @ORM\Table(name="odj_vacancy")
 */
class Vacancy
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $title;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $employment;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $work_experience;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $education;

    /**
     * @ORM\Column(type="text")
     */
    protected $duty;

    /**
     * @ORM\Column(type="text")
     */
    protected $skill;

    /**
     * @ORM\Column(type="integer")
     */
    protected $salary_per_month;

    /**
     * @ORM\Column(type="integer")
     */
    protected $salary_per_day;

    /**
     * @ORM\Column(type="string", length=3)
     */
    protected $currency;

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="vacancies")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     **/
    protected $company;

    /**
     * @ORM\ManyToOne(targetEntity="Country")
     */
    protected $country;

    /**
     * @ORM\ManyToOne(targetEntity="City")
     */
    protected $city;

    /**
     * @ORM\ManyToOne(targetEntity="Branch")
     */
    protected $branch;

    /**
     * @ORM\ManyToOne(targetEntity="Specialization")
     */
    protected $specialization;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="response_vacancy")
     **/
    protected $response_employee;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="favorite_vacancy")
     **/
    protected $favorite_user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $up;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $vip;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $urgent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $views;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $extra;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $term;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $responses;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->response_employee = new ArrayCollection();
        $this->responses = 0;
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
     * Set title
     *
     * @param string $title
     *
     * @return Vacancy
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

    /**
     * Set employment
     *
     * @param integer $employment
     *
     * @return Vacancy
     */
    public function setEmployment($employment)
    {
        $this->employment = $employment;

        return $this;
    }

    /**
     * Get employment
     *
     * @return integer
     */
    public function getEmployment()
    {
        return $this->employment;
    }

    /**
     * Set workExperience
     *
     * @param integer $workExperience
     *
     * @return Vacancy
     */
    public function setWorkExperience($workExperience)
    {
        $this->work_experience = $workExperience;

        return $this;
    }

    /**
     * Get workExperience
     *
     * @return integer
     */
    public function getWorkExperience()
    {
        return $this->work_experience;
    }

    /**
     * Set education
     *
     * @param integer $education
     *
     * @return Vacancy
     */
    public function setEducation($education)
    {
        $this->education = $education;

        return $this;
    }

    /**
     * Get education
     *
     * @return integer
     */
    public function getEducation()
    {
        return $this->education;
    }

    /**
     * Set duty
     *
     * @param string $duty
     *
     * @return Vacancy
     */
    public function setDuty($duty)
    {
        $this->duty = $duty;

        return $this;
    }

    /**
     * Get duty
     *
     * @return string
     */
    public function getDuty()
    {
        return $this->duty;
    }

    /**
     * Set skill
     *
     * @param string $skill
     *
     * @return Vacancy
     */
    public function setSkill($skill)
    {
        $this->skill = $skill;

        return $this;
    }

    /**
     * Get skill
     *
     * @return string
     */
    public function getSkill()
    {
        return $this->skill;
    }

    /**
     * Set salaryPerMonth
     *
     * @param integer $salaryPerMonth
     *
     * @return Vacancy
     */
    public function setSalaryPerMonth($salaryPerMonth)
    {
        $this->salary_per_month = $salaryPerMonth;

        return $this;
    }

    /**
     * Get salaryPerMonth
     *
     * @return integer
     */
    public function getSalaryPerMonth()
    {
        return $this->salary_per_month;
    }

    /**
     * Set salaryPerDay
     *
     * @param integer $salaryPerDay
     *
     * @return Vacancy
     */
    public function setSalaryPerDay($salaryPerDay)
    {
        $this->salary_per_day = $salaryPerDay;

        return $this;
    }

    /**
     * Get salaryPerDay
     *
     * @return integer
     */
    public function getSalaryPerDay()
    {
        return $this->salary_per_day;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return Vacancy
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Vacancy
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set up
     *
     * @param \DateTime $up
     *
     * @return Vacancy
     */
    public function setUp($up)
    {
        $this->up = $up;

        return $this;
    }

    /**
     * Get up
     *
     * @return \DateTime
     */
    public function getUp()
    {
        return $this->up;
    }

    /**
     * Set vip
     *
     * @param \DateTime $vip
     *
     * @return Vacancy
     */
    public function setVip($vip)
    {
        $this->vip = $vip;

        return $this;
    }

    /**
     * Get vip
     *
     * @return \DateTime
     */
    public function getVip()
    {
        return $this->vip;
    }

    /**
     * Set urgent
     *
     * @param boolean $urgent
     *
     * @return Vacancy
     */
    public function setUrgent($urgent)
    {
        $this->urgent = $urgent;

        return $this;
    }

    /**
     * Get urgent
     *
     * @return boolean
     */
    public function getUrgent()
    {
        return $this->urgent;
    }

    /**
     * Set views
     *
     * @param integer $views
     *
     * @return Vacancy
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Get views
     *
     * @return integer
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Set extra
     *
     * @param string $extra
     *
     * @return Vacancy
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * Get extra
     *
     * @return string
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * Set company
     *
     * @param \OneDayJob\ApiBundle\Entity\Company $company
     *
     * @return Vacancy
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
     * Set country
     *
     * @param \OneDayJob\ApiBundle\Entity\Country $country
     *
     * @return Vacancy
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
     * Set city
     *
     * @param \OneDayJob\ApiBundle\Entity\City $city
     *
     * @return Vacancy
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
     * Set branch
     *
     * @param \OneDayJob\ApiBundle\Entity\Branch $branch
     *
     * @return Vacancy
     */
    public function setBranch(\OneDayJob\ApiBundle\Entity\Branch $branch = null)
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * Get branch
     *
     * @return \OneDayJob\ApiBundle\Entity\Branch
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * Set specialization
     *
     * @param \OneDayJob\ApiBundle\Entity\Specialization $specialization
     *
     * @return Vacancy
     */
    public function setSpecialization(\OneDayJob\ApiBundle\Entity\Specialization $specialization = null)
    {
        $this->specialization = $specialization;

        return $this;
    }

    /**
     * Get specialization
     *
     * @return \OneDayJob\ApiBundle\Entity\Specialization
     */
    public function getSpecialization()
    {
        return $this->specialization;
    }

    /**
     * Add responseEmployee
     *
     * @param \OneDayJob\ApiBundle\Entity\User $responseEmployee
     *
     * @return Vacancy
     */
    public function addResponseEmployee(\OneDayJob\ApiBundle\Entity\User $responseEmployee)
    {
        $this->response_employee[] = $responseEmployee;

        return $this;
    }

    /**
     * Remove responseEmployee
     *
     * @param \OneDayJob\ApiBundle\Entity\User $responseEmployee
     */
    public function removeResponseEmployee(\OneDayJob\ApiBundle\Entity\User $responseEmployee)
    {
        $this->response_employee->removeElement($responseEmployee);
    }

    /**
     * Get responseEmployee
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResponseEmployee()
    {
        return $this->response_employee;
    }

    /**
     * Set term
     *
     * @param integer $term
     *
     * @return Vacancy
     */
    public function setTerm($term)
    {
        $this->term = $term;

        return $this;
    }

    /**
     * Get term
     *
     * @return integer
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Add favoriteUser
     *
     * @param \OneDayJob\ApiBundle\Entity\User $favoriteUser
     *
     * @return Vacancy
     */
    public function addFavoriteUser(\OneDayJob\ApiBundle\Entity\User $favoriteUser)
    {
        $this->favorite_user[] = $favoriteUser;

        return $this;
    }

    /**
     * Remove favoriteUser
     *
     * @param \OneDayJob\ApiBundle\Entity\User $favoriteUser
     */
    public function removeFavoriteUser(\OneDayJob\ApiBundle\Entity\User $favoriteUser)
    {
        $this->favorite_user->removeElement($favoriteUser);
    }

    /**
     * Get favoriteUser
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFavoriteUser()
    {
        return $this->favorite_user;
    }

    /**
     * Set responses
     *
     * @param integer $responses
     *
     * @return Vacancy
     */
    public function setResponses($responses)
    {
        $this->responses = $responses;

        return $this;
    }

    /**
     * Get responses
     *
     * @return integer
     */
    public function getResponses()
    {
        return $this->responses;
    }
}
