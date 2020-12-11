<?php

namespace App\Console\Commands;

use App\Models\Community;
use App\Models\Video;
use Domain\Neo4j\Repositories\CommunityNeo4jRepository;
use Exception;
use Illuminate\Console\Command;

class CommunityVideoCountSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'community:videoCountSync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update video count for community';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle()
    {
        (new CommunityNeo4jRepository())->clearAllRelations();

        $communities = Community::all();
        /** @var Community $community */
        foreach ($communities as $community) {
            $count = Video::specialForCommunity($community->id)
                ->count();
            $this->info("Community {$community['name']} has {$count} videos");
            $community->video_count = $count;
            $community->save();
        }
    }
}
