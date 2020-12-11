<?php

namespace Domain\Neo4j\Models;


class User extends BaseModel
{
    protected $table = 'User';
    protected $connectionName = 'neo4j';

    protected $fillable = ['name', 'email', 'id', 'oid'];

    public function friends()
    {
        return $this->belongsToMany(User::class, 'FRIEND_OF');
    }
}
