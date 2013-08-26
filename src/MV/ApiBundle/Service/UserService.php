<?php

namespace MV\ApiBundle\Service;

class UserService extends EntityBaseService
{
    protected $serializer;

    public function __construct($em, $serializer)
    {
        parent::__construct($em);

        $this->serializer = $serializer;
    }

    public function findAll()
    {
        $usersArray = parent::findAll();

        return $this->serializer->serialize($usersArray, 'json');
    }
}