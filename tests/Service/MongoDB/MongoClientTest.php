<?php

namespace App\Tests\Service\MongoDB;

use App\Service\MongoDB\MongoClient;
use App\Service\MongoDB\MongoDoc;
use JetBrains\PhpStorm\Pure;
use MongoDB\BSON\ObjectId;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MongoClientTest extends KernelTestCase
{

    private MongoClient $mongoClient;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->mongoClient = $container->get(MongoClient::class);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testInsert(MongoDocument $document)
    {
        $this->mongoClient->insert($document);
        $this->assertNotNull($document->getId());
        $dbDock = $this->mongoClient->find($document->getCollectionName(), $document->getId());
        unset($dbDock['_id']);
        $this->assertSame($document->getDocument(), (array) $dbDock);

    }

    /**
     * @dataProvider dataProvider
     */
    public function testRemove(MongoDocument $document)
    {
        $this->mongoClient->insert($document);
        $this->mongoClient->remove($document);
        $this->assertNull($this->mongoClient->find($document->getCollectionName(), $document->getId()));

    }
    /**
     * @dataProvider dataProviderMultiple
     */
    public function testPersistAndFlush(array $documents)
    {
        foreach ($documents as $document){
            $this->mongoClient->persist($document);
        }
        $this->mongoClient->flush();
        foreach ($documents as $document){
            $this->assertNotNull($document->getId());
            $dbDock = $this->mongoClient->find($document->getCollectionName(), $document->getId());
            unset($dbDock['_id']);
            $this->assertSame($document->getDocument(), (array) $dbDock);
        }

    }

    /**
     * @dataProvider dataProvider
     */
    public function testUpdate(MongoDocument $document)
    {
        $this->mongoClient->insert($document);
        $document->getDocument()['var4'] = "testvar4";
        $this->mongoClient->update($document);
        $dbDock = $this->mongoClient->find($document->getCollectionName(), $document->getId());
        unset($dbDock['_id']);
        $this->assertSame($document->getDocument(), (array) $dbDock);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testFind(MongoDocument $document)
    {
        $this->mongoClient->getDatabase()->dropCollection($document->getCollectionName());
        $this->mongoClient->insert($document);
        $dbDock = $this->mongoClient->find($document->getCollectionName(), $document->getId());
        $this->assertNotNull($dbDock);
    }

    /**
     * @dataProvider dataProviderMultiple
     */
    public function testFindBy(array $documents)
    {
        $this->mongoClient->getDatabase()->dropCollection($documents[0]->getCollectionName());
        foreach ($documents as $document){
            $this->mongoClient->persist($document);
        }
        $this->mongoClient->flush();
        $dbDock = $this->mongoClient->findBy($documents[0]->getCollectionName(), $documents[0]->getDocument());
        $this->assertSameSize($documents, $dbDock);
    }

    /**
     * @dataProvider dataProviderMultiple
     */
    public function testFindOneBy(array $documents)
    {
        $this->mongoClient->getDatabase()->dropCollection($documents[0]->getCollectionName());
        foreach ($documents as $document){
            $this->mongoClient->persist($document);
        }
        $this->mongoClient->flush();
        $dbDock = $this->mongoClient->findOneBy($documents[0]->getCollectionName(), $documents[0]->getDocument());
        $this->assertArrayHasKey('var1', $dbDock);

    }

    #[Pure] public function dataProvider():array{

        return [
            [new MongoDocument(["var1" => "test01", "var2" => "test02", "var3" => "test03"])],
            [new MongoDocument(["var1" => "test11", "var2" => "test12", "var3" => "test13"])],
            [new MongoDocument(["var1" => "test21", "var2" => "test22", "var3" => "test23"])],
            [new MongoDocument(["var1" => "test31", "var2" => "test32", "var3" => "test33"])],
            [new MongoDocument(["var1" => "test41", "var2" => "test42", "var3" => "test43"])],
            [new MongoDocument(["var1" => "test51", "var2" => "test52", "var3" => "test53"])],
        ];
    }
    #[Pure] public function dataProviderMultiple():array{

        return [
            [
                [
                    new MongoDocument(["var1" => "test61", "var2" => "test62", "var3" => "test63"]),
                    new MongoDocument(["var1" => "test61", "var2" => "test62", "var3" => "test63"])
                ],
            ],
            [
                [
                    new MongoDocument(["var1" => "test71", "var2" => "test72", "var3" => "test73"]),
                    new MongoDocument(["var1" => "test71", "var2" => "test72", "var3" => "test73"]),
                    new MongoDocument(["var1" => "test71", "var2" => "test72", "var3" => "test73"]),
                ],
            ],
            [
                [
                    new MongoDocument(["var1" => "test81", "var2" => "test82", "var3" => "test83"]),
                    new MongoDocument(["var1" => "test81", "var2" => "test82", "var3" => "test83"]),
                    new MongoDocument(["var1" => "test81", "var2" => "test82", "var3" => "test83"]),
                    new MongoDocument(["var1" => "test81", "var2" => "test82", "var3" => "test83"]),
                ],
            ],
        ];
    }
    protected function tearDown(): void
    {
        $this->mongoClient->getDatabase()->drop();
    }
}

class MongoDocument implements MongoDoc{

    private ?ObjectId $id;
    private array $document;

    /**
     * @param array $document
     */
    public function __construct(array $document)
    {
        $this->id = null;
        $this->document = $document;
    }


    /**
     * @return ObjectId|null
     */
    public function getId(): ?ObjectId
    {
        return $this->id;
    }

    /**
     * @param ObjectId $id
     */
    public function setId(ObjectId $id): void
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function &getDocument(): array
    {
        return $this->document;
    }

    /**
     * @param array $document
     */
    public function setDocument(array $document): void
    {
        $this->document = $document;
    }

    public function getCollectionName(): string
    {
        return "MongoTestObject";
    }
}