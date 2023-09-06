<?php

namespace Database\Seeders;

use App\Exceptions\AppException;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = $this->getListOfCountries();

        foreach ($countries as $country) {
            $data = $country;
            DB::transaction(function () use ($data) {
                Country::updateOrCreate(
                    ['iso_code' => $data['iso_code']],
                    $data
                );
            });
        }
    }

    /**
     * Retrive the list of countries from json file
     */
    public function getListOfCountries(): array
    {
        $path = 'seeders/json_files/countries.json';
        $countries_json_file = database_path($path);
        if (!file_exists($countries_json_file)) {
            throw new AppException("File doesn't exist [{$path}]");
        }
        return json_decode(file_get_contents($countries_json_file), true);
    }
}
