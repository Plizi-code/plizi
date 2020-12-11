<?php


namespace App\Traits;


use App\Models\User;
use Domain\Neo4j\Service\FavoriteService;
use Illuminate\Support\Facades\Auth;

trait Neo4jFavorite
{
    /**
     * @return string
     */
    abstract public function getNeo4jNodeName();

    /**
     * @return string
     */
    abstract public function getNeo4jRelationName();

    /**
     * @var FavoriteService
     */
    private $service;

    private function getService()
    {
        if (!$this->service) {
            $this->service = new FavoriteService($this->getNeo4jNodeName(), $this->getNeo4jRelationName());
        }

        return $this->service;
    }

    /**
     * @param User|null $user
     * @return bool
     */
    public function isMember($user = null)
    {
        $user = $user ?: Auth::user();

        return $this->getService()->isMemberOf($user->id, $this->id);
    }

    /**
     * @param User|null $user
     * @return bool
     */
    public function addToFavotite($user = null)
    {
        $user = $user ?: Auth::user();

        /**
         * TODO временно или постоянно убрали, может в будущем захотим вернуть
         */
//        if (!$this->isMember($user)) {
//            return false;
//        }

        return $this->getService()->subscribeAsMember($user->id, $this->id);
    }

    /**
     * @param User|null $user
     * @return bool
     */
    public function deleteFromFavotite($user = null)
    {
        $user = $user ?: Auth::user();

//        if (!$this->isMember($user)) {
//            return false;
//        }

        return $this->getService()->unsubscribeFromMember($user->id, $this->id);
    }

    /**
     * @param User|null $user
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getFavariteIdList($user = null, $limit = 20, $offset = 0)
    {
        $user = $user ?: Auth::user();

        return $this->getService()->getIdList($user->id, $limit, $offset);
    }
}
