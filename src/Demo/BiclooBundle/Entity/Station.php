<?php

namespace Demo\BiclooBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Demo\BiclooBundle\Entity\Station
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Demo\BiclooBundle\Entity\StationRepository")
 * @UniqueEntity(fields="name")
 * @UniqueEntity(fields="number")
 */
class Station
{
    
    const DOWN_TOWN_LAT = 47.21806;
    const DOWN_TOWN_LNG = -1.55278;
    const REMOTE_RATE = 1;
    
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var integer $number
     *
     * @ORM\Column(name="number", type="integer", unique=true)
     */
    private $number;

    /**
     * @var string $address
     *
     * @ORM\Column(name="address", type="string", length=1024)
     */
    private $address;

    /**
     * @var boolean $open
     *
     * @ORM\Column(name="open", type="boolean")
     */
    private $open;

    /**
     * @var boolean $bonus
     *
     * @ORM\Column(name="bonus", type="boolean")
     */
    private $bonus;

    /**
     * @var float $lat
     *
     * @ORM\Column(name="lat", type="float")
     */
    private $lat;

    /**
     * @var float $lng
     *
     * @ORM\Column(name="lng", type="float")
     */
    private $lng;

    /**
     * @ORM\OneToMany(targetEntity="Demo\BiclooBundle\Entity\OccupationDetail", mappedBy="station")
     */
    private $occupationDetails;

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
     * @return Station
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
     * Set number
     *
     * @param integer $number
     * @return Station
     */
    public function setNumber($number)
    {
        $this->number = $number;
    
        return $this;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Station
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set open
     *
     * @param boolean $open
     * @return Station
     */
    public function setOpen($open)
    {
        $this->open = $open;
    
        return $this;
    }

    /**
     * Get open
     *
     * @return boolean 
     */
    public function getOpen()
    {
        return $this->open;
    }

    /**
     * Set bonus
     *
     * @param boolean $bonus
     * @return Station
     */
    public function setBonus($bonus)
    {
        $this->bonus = $bonus;
    
        return $this;
    }

    /**
     * Get bonus
     *
     * @return boolean 
     */
    public function getBonus()
    {
        return $this->bonus;
    }

    /**
     * Set lat
     *
     * @param float $lat
     * @return Station
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    
        return $this;
    }

    /**
     * Get lat
     *
     * @return float 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param float $lng
     * @return Station
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    
        return $this;
    }

    /**
     * Get lng
     *
     * @return float 
     */
    public function getLng()
    {
        return $this->lng;
    }
    
    /**
     * Get OccupationDetails
     * 
     * @return OccupationDetail
     */
    public function getOccupationDetails()
    {
        return $this->occupationDetails;
    }

    /**
     * Is the station far from downtown
     * 
     * @return boolean
     */
    public function isFar()
    {
        return ($this->getDistance() > self::REMOTE_RATE ? true : false);
    }
    
    /**
     *  Get distance
     * 
     * @return double
     */
    public function getDistance()
    {
        $deltaLng = $this->getLng() - self::DOWN_TOWN_LNG ;
        
        $distance  = sin(deg2rad(self::DOWN_TOWN_LAT)) * sin(deg2rad($this->getLat())) + cos(deg2rad(self::DOWN_TOWN_LAT)) * cos(deg2rad($this->getLat())) * cos(deg2rad($deltaLng)) ;
        $distance  = acos($distance);
        $distance  = rad2deg($distance);
        $distance  = $distance * 60 * 1.1515;
        $distance  = round($distance, 4);
        
        return round($distance * 1.609344, 2);
    }
    
}
