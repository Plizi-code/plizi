<?php


namespace Domain\Neo4j\Models;

use Highlight\Mode;
use Vinelab\NeoEloquent\Eloquent\Model;
use Vinelab\NeoEloquent\Query\Builder as QueryBuilder;

class BaseModel extends Model
{
    /**
     * Connection Name
     *
     * @var string
     */
    protected $connectionName = null;

    /**
     * @override
     * Get a new query builder instance for the connection.
     *
     * @return \Vinelab\NeoEloquent\Query\Builder
     */
    protected function newBaseQueryBuilder()
    {
        if(!empty($this->connectionName)) {
            $this->setConnection($this->connectionName);
        }

        $conn = $this->getConnection();

        $grammar = $conn->getQueryGrammar();

        $processor = $conn->getPostProcessor();

        return new QueryBuilder($conn, $grammar, $processor);
    }
}
