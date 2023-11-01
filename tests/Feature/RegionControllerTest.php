<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\Country;
use App\Models\Region;
use Tests\TestCase;

class RegionControllerTest extends TestCase
{

    use RefreshDatabase;

    protected $_end_point = '/api/regions';

    /**
     * @test
     */
    public function whenIndexThenReturnSuccess(): void
    {
        $country = Country::factory()->create();

        $number_items = random_int(2, 5);

        Region::factory($number_items)->create([
            'country_id' => $country->id
        ]);

        $iso_code = strtolower($country->iso_code);

        $this->getJson("{$this->_end_point}/{$iso_code}")
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', $number_items, fn ($json) =>
                    $json->hasAll(['id', 'name', 'region_code', 'country_id'])
                )
            );
    }
}
