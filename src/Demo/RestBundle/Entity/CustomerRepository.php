<?php

namespace Demo\RestBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CustomerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CustomerRepository extends EntityRepository
{
    
    /**
     *  Add a new customer
     * 
     * @param array $postParams
     * @return \Demo\RestBundle\Entity\Customer
     * @throws \Exception
     */
    public function addCustomer($postParams)
    {
        if (is_array($postParams) === false) {
            throw new \Exception('Missing parameter');
        }
        if (empty($postParams['first_name']) === true) {
            throw new \Exception('Missing parameter "first_name"');
        }
        if (empty($postParams['last_name']) === true) {
            throw new \Exception('Missing parameter "last_name"');
        }
        if (empty($postParams['email_address']) === true) {
            throw new \Exception('Missing parameter "email_address"');
        }
        $customer = new Customer();
        $customer->setFirstName($postParams['first_name']);
        $customer->setLastName($postParams['last_name']);
        $customer->setEmailAddress($postParams['email_address']);
        $this->getEntityManager()->persist($customer);
        $this->getEntityManager()->flush();
        
        return $customer;
    }

    /**
     *  Edit an existing customer
     * 
     * @param array $postParams
     * @return boolean
     * @throws \Exception
     */
    public function editCustomer($postParams)
    {
        if (is_array($postParams) === false) {
            throw new \Exception('Missing parameter');
        }
        if (empty($postParams['id']) === true) {
            throw new \Exception('Missing parameter "id"');
        }
        if (empty($postParams['first_name']) === true) {
            throw new \Exception('Missing parameter "first_name"');
        }
        if (empty($postParams['last_name']) === true) {
            throw new \Exception('Missing parameter "last_name"');
        }
        if (empty($postParams['email_address']) === true) {
            throw new \Exception('Missing parameter "email_address"');
        }
        
        $customer = $this->find($postParams['id']);
        if ($customer == null) {
            throw new \Exception('Can\'t find customer with id : ' . $postParams['id'] . '.');
        }
        
        $customer->setFirstName($postParams['first_name']);
        $customer->setLastName($postParams['last_name']);
        $customer->setEmailAddress($postParams['email_address']);
        $this->getEntityManager()->persist($customer);
        $this->getEntityManager()->flush();
        
        return true;
    }
    
}
