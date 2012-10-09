<?php

namespace Demo\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Demo\RestBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\Response;

class ActionController extends Controller
{

    public function getOneAction($id)
    {
        $customers = array();
        $customer = $this->getDoctrine()->getEntityManager()->getRepository('DemoRestBundle:Customer')->find($id);
        if ($customer === null) {
            throw $this->createNotFoundException('Can\'t find customer with id : ' . $id . '.');
        }
        $customers[] = $customer;

        $acceptableContentTypes = $this->getRequest()->getAcceptableContentTypes();

        $response = new Response();
        $response->setContent($this->renderView('DemoRestBundle:Rest:show.' . $this->getTemplateName($acceptableContentTypes[0]) . '.twig', array('customers' => $customers)));
        $response->headers->set('Content-Type', $acceptableContentTypes[0]);

        return $response;
    }

    public function getAllAction()
    {
        $customers = $this->getDoctrine()->getEntityManager()->getRepository('DemoRestBundle:Customer')->findAll();
        if (count($customers) == 0) {
            $this->addBuddies();
            $customers = $this->getDoctrine()->getEntityManager()->getRepository('DemoRestBundle:Customer')->findAll();
        }

        $acceptableContentTypes = $this->getRequest()->getAcceptableContentTypes();

        $response = new Response();
        $response->setContent($this->renderView('DemoRestBundle:Rest:show.' . $this->getTemplateName($acceptableContentTypes[0]) . '.twig', array('customers' => $customers)));
        $response->headers->set('Content-Type', $acceptableContentTypes[0]);

        return $response;
    }

    public function newAction()
    {
        try {
            $customer = $this->getDoctrine()->getEntityManager()->getRepository('DemoRestBundle:Customer')->addCustomer($this->getRequest()->request->all());
            return $this->redirect($this->generateUrl('rest_get_one', array('id' => $customer->getId())));
        } catch (\Exception $exc) {
            throw $this->createNotFoundException($exc->getMessage());
        }
    }

    public function editAction()
    {
        try {
            $customer = $this->getDoctrine()->getEntityManager()->getRepository('DemoRestBundle:Customer')->editCustomer($this->getRequest()->request->all());
            return $this->redirect($this->generateUrl('rest_get_one', array('id' => $customer->getId())));
        } catch (\Exception $exc) {
            throw $this->createNotFoundException($exc->getMessage());
        }
    }

    public function deleteAction()
    {
        $customer = $this->getDoctrine()->getEntityManager()->getRepository('DemoRestBundle:Customer')->find($this->getRequest()->request->get('id'));
        if ($customer === null) {
            throw $this->createNotFoundException('Can\'t find customer with id : ' . $this->getRequest()->request->get('id') . '.');
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
        } elseif ($contentType == 'text/html') {
            return 'html';
        }
        throw $this->createNotFoundException('Bad content type requested');
    }

}
