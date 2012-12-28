<?php

namespace Demo\BiclooBundle\Bicloo;

use Doctrine\Common\Persistence\ObjectManager;
use Demo\BiclooBundle\Entity\Station;
use Demo\BiclooBundle\Entity\OccupationDetail;
use Symfony\Component\Validator\Validator;

class BiclooManager
{

    private $cartoUrl;
    private $stationUrl;
    private $em;
    private $validator;
    
    public function __construct($cartoUrl, $stationUrl, ObjectManager $em, Validator $validator)
    {
        $this->cartoUrl = $cartoUrl;
        $this->stationUrl = $stationUrl;
        $this->em = $em;
        $this->validator = $validator;
    }
    
    public function reloadMap()
    {
        $xml = simplexml_load_file($this->cartoUrl);
        
        if (false === $xml) {
            throw new \Exeption(sprintf("Url '%s' cannot be reached.", $this->cartoUrl));
        }
        
        foreach ($xml->markers[0]->marker as $marker) {
            
            $station = $this->em->getRepository('BiclooBundle:Station')->findOneByName($this->cleanStationName($marker['name']));
            
            if ($station === null) {
                $station = new Station();
            }
            
            $station->setName($this->cleanStationName($marker['name']));
            $station->setNumber($marker['number']);
            $station->setAddress($marker['address']);
            $station->setOpen($marker['open']);
            $station->setBonus($marker['bonus']);
            $station->setLat($marker['lat']);
            $station->setLng($marker['lng']);
            
            $errors = $this->validator->validate($station);
            
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $errs[] = $error;
                }
                throw new \Exception(sprintf('Loading canceled : %s', implode(' | ', $errs)));
            }     
            
            $this->em->persist($station);
        }
        
        $this->em->flush();        
    }
    
    public function cleanStationName($dirtyName)
    {
        return trim(substr($dirtyName, strpos($dirtyName, '-') +1));
    }
    
    public function getOccupationFor($stationNumber)
    {
        $station = $this->em->getRepository('BiclooBundle:Station')->findOneByNumber($stationNumber);
        
        if (is_null($station)) {
            throw new \Exeption(sprintf("Station number '%s' does not exists.", $stationNumber));
        }
        
        $xml = simplexml_load_file(sprintf('%s%s', $this->stationUrl, $station->getNumber()));
        
        if (false === $xml) {
            throw new \Exeption(sprintf("Url '%s' cannot be reached.", $this->stationUrl));
        }
        
        $occupationDetail = new OccupationDetail();
        $occupationDetail->setStation($station);
        $occupationDetail->setAvailable($xml->available);
        $occupationDetail->setFree($xml->free);
        $occupationDetail->setTotal($xml->total);
        $occupationDetail->setDate(new \DateTime());

        return $occupationDetail;
    }
    
    public function processOccupationDetails()
    {
        $date = new \DateTime();
        
        $stations = $this->em->getRepository('BiclooBundle:Station')->findAll();
        
        foreach ($stations as $station) {
            $occupationDetail = $this->getOccupationFor($station->getNumber());
            $occupationDetail->setDate($date);
            
            $errors = $this->validator->validate($occupationDetail);
            
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $errs[] = $error;
                }
                throw new \Exception(sprintf('Process canceled : %s', implode(' | ', $errs)));
            }            
            
            $this->em->persist($occupationDetail);
        }
        
        $this->em->flush();
    }
}
