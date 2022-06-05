<?php

namespace App\Service\MongoDB;

use MongoDB\BSON\ObjectId;
use MongoDB\Client;
use MongoDB\Database;

class MongoClient extends Client
{
    private Database $db;
    const BOQCollection = 'boq';
    private array $documents;
    const  collections = [
        MongoClient::BOQCollection,
    ];

    /**
     * @param string $connectionString
     * @param string $db
     */
    public function __construct(string $connectionString, string $db)
    {
        parent::__construct($connectionString);
        $this->db = $this->selectDatabase($db);
        $this->documents = array();
    }

    /**
     * @param MongoDoc $doc
     */
    public function insert(MongoDoc $doc): void{
        $doc->setID($this->db->selectCollection($doc->getCollectionName())->insertOne($doc->getDocument())->getInsertedId());
    }

    /**
     * @param MongoDoc $doc
     */
    public function update(MongoDoc $doc): void{
        $this->db->selectCollection($doc->getCollectionName())->replaceOne(['_id' => $doc->getID()], $doc->getDocument());
    }

    /**
     * @param MongoDoc $doc
     */
    public function remove(MongoDoc $doc): void{
        $this->db->selectCollection($doc->getCollectionName())->deleteOne(['_id' => $doc->getID()]);
    }

    /**
     * @param string $collectionName
     * @param ObjectId $id
     * @return object|array|null
     */
    public function find(string $collectionName, ObjectId $id): object|array|null
    {
        return $this->db->selectCollection($collectionName)->findOne(['_id' => $id]);
    }

    /**
     * @param string $collectionName
     * @param array $query
     * @return object|array|null
     */
    public function findOneBy(string $collectionName, array $query): object|array|null
    {
        return $this->db->selectCollection($collectionName)->findOne($query);
    }

    /**
     * @param string $collectionName
     * @param array $query
     * @return object|array|null
     */
    public function findBy(string $collectionName, array $query): object|array|null{
        return $this->db->selectCollection($collectionName)->find($query);
    }

    /**
     * @return Database
     */
    public function getDatabase(): Database
    {
        return $this->db;
    }

    /**
     * @param MongoDoc $mongoDoc
     */
    public function persist(MongoDoc $mongoDoc): void{
        $this->documents[] = $mongoDoc;
    }

    /**
     *
     */
    public function flush(): void{
        foreach ($this->documents as $document){
        /**
         * @var $document MongoDoc
         */
            if(is_null($document->getID()))
                $this->insert($document);
            else
                $this->update($document);
        }
        $this->documents = array();
    }
}