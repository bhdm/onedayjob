<?php

namespace OneDayJob\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="odj_language")
 */
class Language
{
	/**
	 * @ORM\Id()
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=150)
	 */
	protected $title;

	/**
	 * @ORM\ManyToMany(targetEntity="Resume", mappedBy="languages")
	 */
	protected $resume;

	public function __construct()
	{
		$this->resume = new ArrayCollection();
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
	 * @return Language
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
	 * Add resume
	 *
	 * @param \OneDayJob\ApiBundle\Entity\Resume $resume
	 * @return Language
	 */
	public function addResume(\OneDayJob\ApiBundle\Entity\Resume $resume)
	{
		$this->resume[] = $resume;

		return $this;
	}

	/**
	 * Remove resume
	 *
	 * @param \OneDayJob\ApiBundle\Entity\Resume $resume
	 */
	public function removeResume(\OneDayJob\ApiBundle\Entity\Resume $resume)
	{
		$this->resume->removeElement($resume);
	}

	/**
	 * Get resume
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getResume()
	{
		return $this->resume;
	}
}
