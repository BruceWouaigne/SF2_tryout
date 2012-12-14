<?php

namespace Demo\BiclooBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Demo\BiclooBundle\Entity\Station;

class DefaultController extends Controller
{
    
    public function indexAction()
    {
        $stations = $this->getDoctrine()->getEntityManager()->getRepository('DemoBiclooBundle:Station')->findAll();
        $stations = $this->getDoctrine()->getEntityManager()->createQuery('SELECT s FROM DemoBiclooBundle:Station s ORDER BY s.number ASC')->execute();
        return $this->render('DemoBiclooBundle:Default:index.html.twig', array('stations' => $stations));
    }    
    
    public function reloadStationsAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $xml = simplexml_load_file('http://www.bicloo.nantesmetropole.fr/service/carto');
        foreach ($xml->markers[0]->marker as $marker) {
            $station = $em->getRepository('DemoBiclooBundle:Station')->findOneByName(substr($marker['name'], trim(strpos($marker['name'], '-') +1)));
            
            if ($station === null) {
                $station = new Station();
            }
            $station->setName(substr($marker['name'], trim(strpos($marker['name'], '-') +1)));
            $station->setNumber($marker['number']);
            $station->setAddress($marker['address']);
            $station->setOpen($marker['open']);
            $station->setBonus($marker['bonus']);
            $station->setLat($marker['lat']);
            $station->setLng($marker['lng']);
            $em->persist($station);
        }
        
        $em->flush();
        
        return $this->redirect($this->generateUrl('demo_bicloo_homepage'));
    }

}
