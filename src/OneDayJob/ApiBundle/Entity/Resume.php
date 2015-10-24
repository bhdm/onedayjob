<?php

namespace OneDayJob\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="ResumeRepository")
 * @ORM\Table(name="odj_resume")
 */
class Resume
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $first_name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $last_name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $middle_name;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $birthdate;

    /**
     * @ORM\Column(type="string", length=6)
     */
    protected $sex;

    /**
     * @ORM\ManyToOne(targetEntity="City")
     */
    protected $city;

    /**
     * @ORM\OneToMany(targetEntity="Experience", mappedBy="resume", cascade={"persist", "remove"})
     **/
    protected $experience;

    /**
     * @ORM\OneToMany(targetEntity="Education", mappedBy="resume", cascade={"persist", "remove"})
     **/
    protected $education;

    /**
     * @ORM\Column(type="smallint", length=1)
     */
    protected $relocate;

    /**
     * @ORM\Column(type="smallint", length=1)
     */
    protected $citizenship;

    /**
     * @ORM\Column(type="smallint", length=1)
     */
    protected $work_permit;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    protected $alternative_phone;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $skype;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    protected $preferred_communication;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="resume")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    protected $employee;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\ManyToMany(targetEntity="Language", inversedBy="resume", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="obj_resume_language")
     **/
    protected $languages;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $vip;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $up;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $extra;

    /**
     * @ORM\ManyToOne(targetEntity="Image")
     **/
    protected $image;

    /**
     * @ORM\Column(type="date" , nullable=true)
     */
    private $termfrom;

    /**
     * @ORM\Column(type="date" , nullable=true)
     */
    private $termto;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $salary;

    /**
     * @ORM\Column(type="string", length=3)
     */
    protected $currency;

    /**
     * @ORM\ManyToOne(targetEntity="Branch")
     **/
    protected $branch;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $specialty;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="favorite_resume")
     * @ORM\JoinTable(name="odj_favorite_resume")
     **/
    protected $favorite_user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->experience = new ArrayCollection();
        $this->education = new ArrayCollection();
        $this->languages = new ArrayCollection();
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Resume
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Resume
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set middleName
     *
     * @param string $middleName
     *
     * @return Resume
     */
    public function setMiddleName($middleName)
    {
        $this->middle_name = $middleName;

        return $this;
    }

    /**
     * Get middleName
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middle_name;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     *
     * @return Resume
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
     * Set sex
     *
     * @param string $sex
     *
     * @return Resume
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set relocate
     *
     * @param integer $relocate
     *
     * @return Resume
     */
    public function setRelocate($relocate)
    {
        $this->relocate = $relocate;

        return $this;
    }

    /**
     * Get relocate
     *
     * @return integer
     */
    public function getRelocate()
    {
        return $this->relocate;
    }

    /**
     * Set citizenship
     *
     * @param integer $citizenship
     *
     * @return Resume
     */
    public function setCitizenship($citizenship)
    {
        $this->citizenship = $citizenship;

        return $this;
    }

    /**
     * Get citizenship
     *
     * @return integer
     */
    public function getCitizenship()
    {
        return $this->citizenship;
    }

    /**
     * Set workPermit
     *
     * @param integer $workPermit
     *
     * @return Resume
     */
    public function setWorkPermit($workPermit)
    {
        $this->work_permit = $workPermit;

        return $this;
    }

    /**
     * Get workPermit
     *
     * @return integer
     */
    public function getWorkPermit()
    {
        return $this->work_permit;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Resume
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
     * Set alternativePhone
     *
     * @param string $alternativePhone
     *
     * @return Resume
     */
    public function setAlternativePhone($alternativePhone)
    {
        $this->alternative_phone = $alternativePhone;

        return $this;
    }

    /**
     * Get alternativePhone
     *
     * @return string
     */
    public function getAlternativePhone()
    {
        return $this->alternative_phone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Resume
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set skype
     *
     * @param string $skype
     *
     * @return Resume
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;

        return $this;
    }

    /**
     * Get skype
     *
     * @return string
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * Set preferredCommunication
     *
     * @param string $preferredCommunication
     *
     * @return Resume
     */
    public function setPreferredCommunication($preferredCommunication)
    {
        $this->preferred_communication = $preferredCommunication;

        return $this;
    }

    /**
     * Get preferredCommunication
     *
     * @return string
     */
    public function getPreferredCommunication()
    {
        return $this->preferred_communication;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Resume
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
     * Set vip
     *
     * @param \DateTime $vip
     *
     * @return Resume
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
     * Set up
     *
     * @param \DateTime $up
     *
     * @return Resume
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
     * Set city
     *
     * @param \OneDayJob\ApiBundle\Entity\City $city
     *
     * @return Resume
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
     * Add experience
     *
     * @param \OneDayJob\ApiBundle\Entity\Experience $experience
     *
     * @return Resume
     */
    public function addExperience(\OneDayJob\ApiBundle\Entity\Experience $experience)
    {
        $this->experience[] = $experience;

        return $this;
    }

    /**
     * Remove experience
     *
     * @param \OneDayJob\ApiBundle\Entity\Experience $experience
     */
    public function removeExperience(\OneDayJob\ApiBundle\Entity\Experience $experience)
    {
        $this->experience->removeElement($experience);
    }

    /**
     * Get experience
     *
     * @return Collection
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * Add education
     *
     * @param \OneDayJob\ApiBundle\Entity\Education $education
     *
     * @return Resume
     */
    public function addEducation(\OneDayJob\ApiBundle\Entity\Education $education)
    {
        $this->education[] = $education;

        return $this;
    }

    /**
     * Remove education
     *
     * @param \OneDayJob\ApiBundle\Entity\Education $education
     */
    public function removeEducation(\OneDayJob\ApiBundle\Entity\Education $education)
    {
        $this->education->removeElement($education);
    }

    /**
     * Get education
     *
     * @return Collection
     */
    public function getEducation()
    {
        return $this->education;
    }

    /**
     * Set employee
     *
     * @param \OneDayJob\ApiBundle\Entity\User $employee
     *
     * @return Resume
     */
    public function setEmployee(\OneDayJob\ApiBundle\Entity\User $employee = null)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return \OneDayJob\ApiBundle\Entity\User
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * Add language
     *
     * @param \OneDayJob\ApiBundle\Entity\Language $language
     *
     * @return Resume
     */
    public function addLanguage(\OneDayJob\ApiBundle\Entity\Language $language)
    {
        $this->languages[] = $language;

        return $this;
    }

    /**
     * Remove language
     *
     * @param \OneDayJob\ApiBundle\Entity\Language $language
     */
    public function removeLanguage(\OneDayJob\ApiBundle\Entity\Language $language)
    {
        $this->languages->removeElement($language);
    }

    /**
     * Get languages
     *
     * @return Collection
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * Set image
     *
     * @param \OneDayJob\ApiBundle\Entity\Image $image
     *
     * @return Resume
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
     * Set extra
     *
     * @param string $extra
     *
     * @return Resume
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

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAge()
    {
        return $this->birthdate ? (new \DateTime())->diff($this->birthdate)->y : 0 ;
    }

    /**
     * Set salary
     *
     * @param integer $salary
     *
     * @return Resume
     */
    public function setSalary($salary)
    {
        $this->salary = $salary;

        return $this;
    }

    /**
     * Get salary
     *
     * @return integer
     */
    public function getSalary()
    {
        return $this->salary;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return Resume
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

    public function getImageSrc()
    {
        return $this->image ? $this->image->getSrc() : '/bundles/onedayjobfrontend/content/img5.png';
    }

    /**
     * Set branch
     *
     * @param \OneDayJob\ApiBundle\Entity\Branch $branch
     *
     * @return Resume
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
     * Set specialty
     *
     * @param string $specialty
     *
     * @return Resume
     */
    public function setSpecialty($specialty)
    {
        $this->specialty = $specialty;

        return $this;
    }

    /**
     * Get specialty
     *
     * @return string
     */
    public function getSpecialty()
    {
        return $this->specialty;
    }

    /**
     * Add favoriteUser
     *
     * @param \OneDayJob\ApiBundle\Entity\User $favoriteUser
     *
     * @return Resume
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
     * @return mixed
     */
    public function getTermfrom()
    {
        return $this->termfrom;
    }

    /**
     * @param mixed $termfrom
     */
    public function setTermfrom($termfrom)
    {
        $this->termfrom = $termfrom;
    }

    /**
     * @return mixed
     */
    public function getTermto()
    {
        return $this->termto;
    }

    /**
     * @param mixed $termto
     */
    public function setTermto($termto)
    {
        $this->termto = $termto;
    }


}
