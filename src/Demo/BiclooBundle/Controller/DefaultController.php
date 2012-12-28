<?php

namespace Demo\BiclooBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        
        return $this->redirect($this->generateUrl('demo_bicloo_homepage'));
    }

}
