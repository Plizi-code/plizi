<?php


namespace Domain\Neo4j\Repositories;

namespace Domain\Neo4j\Repositories;

use GraphAware\Neo4j\Client\ClientBuilder;
use GraphAware\Neo4j\Client\ClientInterface;

class BaseRepository
{

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $host = config('database.connections.neo4j.host');
        $username = config('database.connections.neo4j.username');
        $password = config('database.connections.neo4j.password');
        $port = config('database.connections.neo4j.bolt_port');
        $this->client = ClientBuilder::create()
            ->addConnection('bolt', "bolt://{$username}:{$password}@{$host}:{$port}")
            ->build();
    }

    /**
     * @return ClientInterface
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    protected function _clearAllRelations($type)
    {
        $sql = "match (a:{$type})-[r]-() delete r";
        $this->client->run($sql)->records();
    }
}
