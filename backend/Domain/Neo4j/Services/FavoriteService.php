<?php

namespace Domain\Neo4j\Service;

use Domain\Neo4j\Repositories\FavoriteNeo4jRepository;
use GraphAware\Common\Result\Record;

/**
 * Class FavoriteService
 * @package Domain\Neo4j\Service
 *
 * @method int|null getMemberId(int $user_oid, int $target_oid)
 * @method bool isMemberOf(int $user_oid, int $target_oid)
 * @method bool subscribeAsMember(int $user_oid, int $target_oid)
 * @method bool unsubscribeFromMember(int $user_oid, int $target_oid)
 */
class FavoriteService
{
    /**
     * @var FavoriteNeo4jRepository
     */
    public $repository;

    /**
     * UserService constructor.
     * @param $targetName
     * @param $relationName
     */
    public function __construct($targetName, $relationName)
    {
        $this->repository = new FavoriteNeo4jRepository($targetName, $relationName);
    }

    /**
     * @param $user_oid
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getIdList($user_oid, $limit = 20, $offset = 0)
    {
        $list = $this->repository->getIdList($user_oid, $limit, $offset);

        return array_map(static function (Record $item) {
            return $item->get('oid');
        }, $list);
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->repository, $name)) {
            return call_user_func_array([$this->repository, $name], $arguments);
        }
    }
}
