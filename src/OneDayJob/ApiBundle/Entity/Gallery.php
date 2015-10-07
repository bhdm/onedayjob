<?php

namespace OneDayJob\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="odj_gallery")
 * @ORM\Entity
 */
class Gallery
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $file_name;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="gallery")
     */
    protected $company;


    public function __construct()
    {
        $this->setDate(new \DateTime());
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * @param mixed $file_name
     */
    public function setFileName($file_name)
    {
        $this->file_name = $file_name;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }



    /**
     * Get path to the file
     *
     * @return string
     */
    public function getSrc($absolute = false)
    {
        $src = $this->getRelativePath() .'/'. $this->getFileName();

        if ($absolute) {
            $src = $this->getAbsolutePath() .'/'. $this->getFileName();
        }

        return $src;
    }

    /**
     * Get absolute path
     *
     * @return string
     */
    public function getAbsolutePath()
    {
        return __DIR__ .'/../../../../web/uploads/images';
    }

    /**
     * Get relative path
     *
     * @return string
     */
    public function getRelativePath()
    {
        return '/uploads/images';
    }

    public function __toString()
    {
        return $this->getFileName();
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }


}