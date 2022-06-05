<?php

namespace App\Service\MongoDB;

use MongoDB\BSON\ObjectId;

interface MongoDoc
{
    public function getDocument(): array;
    public function setID(ObjectId $id): void;
    public function getID(): ?ObjectId;
    public function getCollectionName(): string;
}