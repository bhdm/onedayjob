<?php

namespace OneDayJob\ApiBundle\Entity;

class Search
{
	protected $city;

    protected $text;

    protected $branch;

    protected $specialization;

    protected $work_experience;

    protected $work_schedule;

    protected $salary_from;

    protected $education;

    protected $employment;

    protected $currency;


    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
        
        return $this;
    }

    public function getWorkShelude()
    {
        return $this->work_schedule;
    }

    public function setWorkShelude($work_schedule)
    {
        $this->work_schedule = $work_schedule;
        
        return $this;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
        
        return $this;
    }

    public function getBranch()
    {
        return $this->branch;
    }

    public function setBranch($branch)
    {
        $this->branch = $branch;
        
        return $this;
    }

    public function getSpecialization()
    {
        return $this->specialization;
    }

    public function setSpecialization($specialization)
    {
        $this->specialization = $specialization;
        
        return $this;
    }

    public function getWorkExperience()
    {
        return $this->work_experience;
    }

    public function setWorkExperience($work_experience)
    {
        $this->work_experience = $work_experience;
        
        return $this;
    }

    public function getSalaryFrom()
    {
        return $this->salary_from;
    }

    public function setSalaryFrom($salary_from)
    {
        $this->salary_from = $salary_from;
        
        return $this;
    }

    public function getEducation()
    {
        return $this->education;
    }

    public function setEducation($education)
    {
        $this->education = $education;
        
        return $this;
    }

    public function getEmployment()
    {
        return $this->employment;
    }

    public function setEmployment($employment)
    {
        $this->employment = $employment;
        
        return $this;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
        
        return $this;
    }

    
}
