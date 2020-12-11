<?php

use Illuminate\Database\Seeder;
use App\Models\Geo\Countries;

abstract class GeoSeeder extends Seeder
{

    abstract function getGeoDataFile(): string;

    abstract function getDataConfig(): array;

    abstract function createGeoData(array $data): void;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $row = 1;
        $columnMap = [];
        $handle = fopen(__DIR__ . $this->getGeoDataFile(), "r");
        $batchArr = [];
        $this->command->line(" - Start processing!");
        while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
            if ($row === 1) {
                $columnMap = $this->mapColumns($data, $this->getDataConfig());
            } else {
                $insertData = [];
                foreach ($data as $i => $value) {
                    if (isset($columnMap[$i])) {
                        $insertData[$columnMap[$i]] = $value ?? null;
                    }
                }
                if (!empty($insertData) && $this->checkData($insertData)) {
                    $batchArr[] = $this->prepareData($insertData);
                    if (count($batchArr) === 500) {
                        $this->createGeoData($batchArr);
                        $batchArr = [];
                    }
                }
            }
            $row++;
        }
        if (!empty($batchArr)) {
            $this->createGeoData($batchArr);
        }
        $this->command->line(" - Finished. Processed {$row} lines");
        fclose($handle);
    }

    private function mapColumns($data, $config)
    {
        $result = [];
        $docColumns = array_flip(array_keys($config));
        foreach ($data as $i => $columnName) {
            if (isset($docColumns[$columnName])) {
                $result[$i] = $config[$columnName];
            }
        }
        return $result;
    }

    protected function prepareData($data)
    {
        return $data;
    }

    protected function checkData($data)
    {
        return true;
    }
}
