<?php

namespace OneDayJob\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 *
 * @ORM\Table(name="odj_image")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Image
{
	private $tmp;

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
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $title;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $date;


	public function __construct()
	{
		$this->setDate(new \DateTime());
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
	 * Get file name
	 *
	 * @return string 
	 */
	public function getFileName()
	{
		return $this->file_name;
	}

	/**
	 * Set title
	 *
	 * @param string $title
	 * @return Image
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
	 * Get relative path
	 *
	 * @return string 
	 */
	public function getRelativePath()
	{
		return '/uploads/images';
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
	 * Set date
	 *
	 * @param \DateTime $date
	 * @return Image
	 */
	public function setDate($date)
	{
		$this->date = $date;

		return $this;
	}

	/**
	 * Get date
	 *
	 * @return \DateTime 
	 */
	public function getDate()
	{
		return $this->date;
	}

	/**
	 * Set file_name
	 *
	 * @param string $fileName
	 * @return Image
	 */
	public function setFileName($fileName)
	{
		$this->file_name = $fileName;

		return $this;
	}

	/**
	 * @ORM\PreRemove()
	 */
	public function storeFilenameForRemove()
	{
		$this->tmp = $this->getSrc(true);
	}

	/**
	 * @ORM\PostRemove()
	 */
	public function removeFile()
	{
		if (isset($this->tmp) && file_exists($this->tmp)) {
			@unlink($this->tmp);
		}
	}

	public function __toString()
    {
        return $this->getFileName();
    }
}
