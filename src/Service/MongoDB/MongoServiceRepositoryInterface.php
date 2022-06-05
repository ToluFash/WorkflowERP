<?php

namespace App\Service\MongoDB;


interface MongoServiceRepositoryInterface
{
    public function add($entity, bool $flush = false): void;
    public function remove($entity, bool $flush = false): void;
}