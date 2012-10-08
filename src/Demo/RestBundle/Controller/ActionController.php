<?php

namespace Demo\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Demo\RestBundle\Entity\Customer;
use Demo\RestBundle\Entity\CustomerRepository;

class ActionController extends Controller
{

	public function getOneAction($id)
	{
		$customers = array();
		$customer = $this->getDoctrine()->getEntityManager()->getRepository('DemoRestBundle:Customer')->find($id);
		if ($customer === null) {
			throw $this->createNotFoundException('Customer[id=' . $id . '] inexistant.');
		}
		$customers[] = $customer;
		return $this->render('DemoRestBundle:Rest:show.' . $this->getTemplateName($this->getRequest()->getAcceptableContentTypes()) . '.twig', array('customers' => $customers));
	}

	public function getAllAction()
	{
		$customers = $this->getDoctrine()->getEntityManager()->getRepository('DemoRestBundle:Customer')->findAll();
		if (count($customers) == 0) {
			$this->addBuddies();
			$customers = $this->getDoctrine()->getEntityManager()->getRepository('DemoRestBundle:Customer')->findAll();
		}
		return $this->render('DemoRestBundle:Rest:show.' . $this->getTemplateName($this->getRequest()->getAcceptableContentTypes()) . '.twig', array('customers' => $customers));
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

	protected function getTemplateName($acceptableContentTypes)
	{
		foreach ($acceptableContentTypes as $contentType) {
			if ($contentType == 'application/xml') {
				return 'xml';
			} elseif ($contentType == 'application/json') {
				return 'json';
			} elseif ($contentType == 'text/html') {
				return 'html';
			}
		}
		throw $this->createNotFoundException('Bad content type requested');
	}
}
