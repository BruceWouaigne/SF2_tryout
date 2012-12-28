<?php

namespace Demo\BiclooBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $stations = $em->getRepository('BiclooBundle:Station')->getOrderedStations();
        
        return $this->render('BiclooBundle:Default:index.html.twig', array('stations' => $stations));
    }    
    
    public function reloadStationsAction()
    {
        $manager = $this->container->get('bicloo_manager');
        $manager->reloadMap();
        
        return $this->redirect($this->generateUrl('bicloo_homepage'));
    }

    public function loadStationInfoAction(Request $request)
    {
        $manager = $this->container->get('bicloo_manager');
        $occupationDetail = $manager->getOccupationFor($request->request->get('stationNumber'));
        
        $response = new Response(json_encode(array(
            'available' => (int) $occupationDetail->getAvailable(),
            'free' => (int) $occupationDetail->getFree(),
            'total' => (int) $occupationDetail->getTotal(),
        )));
        
        $response->headers->set('content-type', 'application/json');
        
        return $response;
    }
    
}
