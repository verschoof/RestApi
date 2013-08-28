<?php

namespace MV\ApiBundle\Service;

use Doctrine\ORM\EntityManager;

abstract class EntityBaseService
{
    protected $em;
    protected $repository;
    protected $entityClass;

    public function __construct(EntityManager $em)
    {
        $class = get_class( $this );
        $classExplode = explode( "\\", $class );

        $serviceClass = $classExplode[0] . $classExplode[1] . ':' . end($classExplode);
        $entityClass  = str_replace('Service', '', $serviceClass);
        $repository   = $em->getRepository($entityClass);

        $this->em          = $em;
        $this->repository  = $repository;
        $this->entityClass = $entityClass;
    }

    public function find($id)
    {
        $findOne = $this->em
            ->getRepository($this->entityClass)
            ->find($id);

        return $findOne;
    }

    public function findOneBy( array $criteria )
    {
        $findOne = $this->em
            ->getRepository($this->entityClass)
            ->findOneBy( $criteria );

        return $findOne;
    }

    public function findAll()
    {
        $findAll = $this->em
            ->getRepository($this->entityClass)
            ->findAll();

        return $findAll;
    }

    public function findBy( array $criteria, array $orderBy = null, $limit = null, $offset = null )
    {
        $findBy = $this->em
            ->getRepository($this->entityClass)
            ->findBy($criteria, $orderBy, $limit, $offset);

        return $findBy;
    }

    public function newInstance()
    {
        $entityInfo   = $this->em->getClassMetadata($this->entityClass);
        $entityMember = new $entityInfo->name;

        return $entityMember;
    }
}