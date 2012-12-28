<?php

namespace Demo\BiclooBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * OccupationDetail
 *
 * @ORM\Table(uniqueConstraints={@UniqueConstraint(name="unique", columns={"station_id", "date"})})
 * @ORM\Entity(repositoryClass="Demo\BiclooBundle\Entity\OccupationDetailRepository")
 * @UniqueEntity(fields={"station", "date"})
 */
class OccupationDetail
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="available", type="integer")
     */
    private $available;

    /**
     * @var integer
     *
     * @ORM\Column(name="free", type="integer")
     */
    private $free;

    /**
     * @var integer
     *
     * @ORM\Column(name="total", type="integer")
     */
    private $total;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Demo\BiclooBundle\Entity\Station", cascade={"remove"}, inversedBy="occupationDetails")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     * @Assert\NotBlank()
     */
    private $station;

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
     * Set available
     *
     * @param integer $available
     * @return OccupationDetail
     */
    public function setAvailable($available)
    {
        $this->available = $available;

        return $this;
    }

    /**
     * Get available
     *
     * @return integer 
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * Set free
     *
     * @param integer $free
     * @return OccupationDetail
     */
    public function setFree($free)
    {
        $this->free = $free;

        return $this;
    }

    /**
     * Get free
     *
     * @return integer 
     */
    public function getFree()
    {
        return $this->free;
    }

    /**
     * Set total
     *
     * @param integer $total
     * @return OccupationDetail
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return integer 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Get Station
     * 
     * @return Station
     */
    public function getStation()
    {
        return $this->station;
    }

    /**
     * Set Station
     * 
     * @param Station $station
     */
    public function setStation(Station $station)
    {
        $this->station = $station;
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
     * Set date
     * 
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

}
