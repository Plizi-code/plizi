<?php

namespace Domain\Neo4j\Models;


class Community extends BaseModel
{
    protected $table = 'Community';
    protected $connectionName = 'neo4j';

    protected $fillable = ['name', 'oid'];

    public function members()
    {
        return $this->belongsToMany(User::class, 'MEMBER_OF');
    }
}
