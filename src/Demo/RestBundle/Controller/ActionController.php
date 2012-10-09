<?php

namespace Demo\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Demo\RestBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\Response;

class ActionController extends Controller
{

    public function getOneAction($id)
    {
        $response = new Response();
        
        $acceptableContentTypes = $this->getRequest()->getAcceptableContentTypes();
        
        $customers = array();
        $customer = $this->getDoctrine()->getEntityManager()->getRepository('DemoRestBundle:Customer')->find($id);
        if ($customer === null) {
            $response->setContent($this->renderView('DemoRestBundle:failure:show.' . $this->getTemplateName($acceptableContentTypes[0]) . '.twig', array('error_message' => 'Couldn\'t find customer with id : ' . $id . '.')));
            return $response;
        }

        $customers[] = $customer;

        $response->setContent($this->renderView('DemoRestBundle:success:show.' . $this->getTemplateName($acceptableContentTypes[0]) . '.twig', array('customers' => $customers)));
        $response->headers->set('Content-Type', $acceptableContentTypes[0]);

        return $response;
    }

    public function getAllAction()
    {
        $response = new Response();
        
        $acceptableContentTypes = $this->getRequest()->getAcceptableContentTypes();
        
        $customers = $this->getDoctrine()->getEntityManager()->getRepository('DemoRestBundle:Customer')->findAll();
        if (count($customers) == 0) {
            $this->addBuddies();
            $customers = $this->getDoctrine()->getEntityManager()->getRepository('DemoRestBundle:Customer')->findAll();
        }

        $response->setContent($this->renderView('DemoRestBundle:success:show.' . $this->getTemplateName($acceptableContentTypes[0]) . '.twig', array('customers' => $customers)));
        $response->headers->set('Content-Type', $acceptableContentTypes[0]);

        return $response;
    }

    public function newAction()
    {
        $acceptableContentTypes = $this->getRequest()->getAcceptableContentTypes();
        
        try {
            $customer = $this->getDoctrine()->getEntityManager()->getRepository('DemoRestBundle:Customer')->addCustomer($this->getRequest()->request->all());
            return $this->redirect($this->generateUrl('rest_get_one', array('id' => $customer->getId())));
        } catch (\Exception $exc) {
            $response = new Response();
            $response->setContent($this->renderView('DemoRestBundle:failure:show.' . $this->getTemplateName($acceptableContentTypes[0]) . '.twig', array('error_message' => $exc->getMessage())));
            return $response;            
        }
    }

    public function editAction()
    {
        $acceptableContentTypes = $this->getRequest()->getAcceptableContentTypes();
        
        try {
            $customer = $this->getDoctrine()->getEntityManager()->getRepository('DemoRestBundle:Customer')->editCustomer($this->getRequest()->request->all());
            return $this->redirect($this->generateUrl('rest_get_one', array('id' => $customer->getId())));
        } catch (\Exception $exc) {
            $response = new Response();
            $response->setContent($this->renderView('DemoRestBundle:failure:show.' . $this->getTemplateName($acceptableContentTypes[0]) . '.twig', array('error_message' => $exc->getMessage())));
            return $response;
        }
    }

    public function deleteAction($id)
    {
        $acceptableContentTypes = $this->getRequest()->getAcceptableContentTypes();
        
        $customer = $this->getDoctrine()->getEntityManager()->getRepository('DemoRestBundle:Customer')->find($id);
        if ($customer === null) {
            $response = new Response();
            $response->setContent($this->renderView('DemoRestBundle:failure:show.' . $this->getTemplateName($acceptableContentTypes[0]) . '.twig', array('error_message' => 'Couldn\'t find customer with id : ' . $id . '.')));
            return $response;
        }
        
        $this->getDoctrine()->getEntityManager()->remove($customer);
        $this->getDoctrine()->getEntityManager()->flush();
        
        return $this->redirect($this->generateUrl('rest_get_all'));
    }

    protected function addBuddies()
    {
        $customer = new Customer();
        $customer->setFirstName('Boby');
        $customer->setLastName('Jacks');
        $customer->setEmailAddress('bjacks@plop.com');
        $this->getDoctrine()->getEntityManager()->persist($customer);

        $customer = new Customer();
        $customer->setFirstName('John');
        $customer->setLastName('Doe');
        $customer->setEmailAddress('jdoe@plop.com');
        $this->getDoctrine()->getEntityManager()->persist($customer);

        $this->getDoctrine()->getEntityManager()->flush();
    }

    protected function getTemplateName($contentType)
    {
        if ($contentType == 'application/xml') {
            return 'xml';
        } elseif ($contentType == 'application/json') {
            return 'json';
        } else {
            return 'html';
        }
    }

}
