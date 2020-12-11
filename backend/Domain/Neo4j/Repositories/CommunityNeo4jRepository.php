<?php

namespace Domain\Neo4j\Repositories;

use DB;
use Domain\Neo4j\Models\Community;
use Exception;
use GraphAware\Common\Result\Record;
use Illuminate\Database\ConnectionInterface;
use Domain\Neo4j\Models\User;

class CommunityNeo4jRepository extends BaseRepository
{

    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * CommunityNeo4jRepository constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->connection = DB::connection('neo4j');
    }

    /**
     * @param $community_oid
     * @param $member_oid
     * @return bool
     */
    public function beMember($community_oid, $member_oid) {
        /** @var Community $community */
        $community = Community::where('oid', $community_oid)->first();
        $member = User::where('oid', $member_oid)->first();
        return (bool) $community->members()->attach($member);
    }

    public function clearAllRelations()
    {
        $this->_clearAllRelations('Community');
    }

    /**
     * @param string $oid
     * @param int $limit
     * @param int $offset
     * @return array|Record[]
     */
    public function recommended($oid, $limit = 5, $offset = 0) {
        $query = "MATCH (u:User {oid:'{$oid}'})-[rf:FRIEND_OF]-(fr:User)
                  WITH fr, u
                  MATCH (fr)-[rc:MEMBER_OF]-(c:Community)
                  WHERE NOT(c)-[:MEMBER_OF]-(u)
                  RETURN c.oid AS oid, COUNT(rc) AS r_count
                  ORDER BY r_count DESC, oid
                  SKIP {$offset}
                  LIMIT {$limit}";

        try {
            $list = $this->client->run($query)->records();
        } catch (Exception $e) {
            return [];
        }

        return $list;
    }
}
