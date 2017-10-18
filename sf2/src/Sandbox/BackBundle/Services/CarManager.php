<?php

namespace Sandbox\BackBundle\Services;

use Doctrine\ORM\EntityManager;

class CarManager
{
    /**
     * @var EntityManager
     */
    private $em;
    private $repository;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository('SandboxBackBundle:Car');
    }

    public function persist($car)
    {
        $this->em->persist($car);
        $this->em->flush();
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

}