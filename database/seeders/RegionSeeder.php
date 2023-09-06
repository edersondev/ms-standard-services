<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Country;
use App\Models\Region;
use Symfony\Component\Finder\SplFileInfo;

class RegionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $files = File::allFiles(database_path('seeders/json_files/region/'));
        foreach($files as $file) {
            if ($file->getExtension() !== 'json') {
                continue;
            }
            $this->createRegionFromFile($file);
        }
    }

    /**
     * Create regions from file
     */
    public function createRegionFromFile(SplFileInfo $file): void
    {
        $regions = json_decode(file_get_contents($file), true);
        $iso_code = strtoupper($file->getBasename('.' . $file->getExtension()));
        $country = Country::where('iso_code', $iso_code)->first();

        if (!is_null($country)) {
            foreach ($regions as $region) {
                Region::updateOrCreate(
                    ['region_code' => $region['region_code'], 'country_id' => $country->id],
                    $region
                );
            }
        }
    }
}
