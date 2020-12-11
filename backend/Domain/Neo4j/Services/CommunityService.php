<?php

namespace Domain\Neo4j\Service;

use Domain\Neo4j\Repositories\CommunityNeo4jRepository;
use Illuminate\Support\Collection;

class CommunityService
{
    /**
     * @var CommunityNeo4jRepository
     */
    public $communityNeo4jRepository;

    /**
     * UserService constructor.
     */
    public function __construct()
    {
        $this->communityNeo4jRepository = new CommunityNeo4jRepository();
    }

    /**
     * @param $oid
     * @param int $limit
     * @param int $offset
     * @return Collection
     */
    public function recommended($oid, $limit = 5, $offset = 0)
    {
        $communities = $this->communityNeo4jRepository->recommended($oid, $limit, $offset);
        $result = [];
        foreach ($communities as $community) {
            $result[] = [
                'oid' => $community->get('oid'),
                'r_count' => $community->get('r_count'),
            ];
        }
        return new Collection($result);
    }
}
