<?php

namespace App\Service\MongoDB;

class MongoServiceRepository
{

    private MongoClient $mongoClient;
    private string $docClass;

    /**
     * @param MongoClient $mongoClient
     * @param string $docClass
     */
    public function __construct(MongoClient $mongoClient, string $docClass)
    {
        $this->mongoClient = $mongoClient;
        $this->docClass = $docClass;
    }

}