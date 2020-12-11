<?php

use App\Models\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        if (in_array(App::environment(), ['staging', 'local', 'testing'])) {
            $this->doSeed(UsersTableSeeder::class);
            $this->doSeed(ChatSeeder::class);
            $this->doSeed(ProfileRelationshipsSeeder::class);
            $this->doSeed(PostsTableSeeder::class);
            $this->doSeed(CountriesGeoSeeder::class);
            $this->doSeed(RegionsGeoSeeder::class);
            $this->doSeed(CitiesGeoSeeder::class);
            $this->doSeed(FriendshipTableSeeder::class);
            $this->doSeed(BaseRBACSeeder::class);
            $this->doSeed(CommunityThemeSeeder::class);
            $this->doSeed(CommunitiesTableSeeder::class);
        }
        Model::reguard();
    }

    /**
     * @param $seedName
     */
    private function doSeed($seedName)
    {
        $this->command->line("Trying to execute seed " . $seedName);
        $exists = DB::table('seeds')->where('seed_name', $seedName)->exists();
        if (!$exists) {
            try {
                $this->call($seedName);
            } catch (\Exception $e) {
                $this->command->line("Error: " . $e->getMessage());
            }
            DB::table('seeds')->insertOrIgnore(array('seed_name' => $seedName));
        } else {
            $this->command->line("Seed with name '" . $seedName . "' was executed earlier ");
        }
    }
}
