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

    protected $_response_fields = ['id', 'name', 'region_code', 'country_id'];

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

        $this->getJson("{$this->_end_point}?country_iso={$iso_code}")
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', $number_items, fn ($json) =>
                    $json->hasAll($this->_response_fields)
                )
            );
    }

    /**
     * @test
     */
    public function whenIndexIsEmptyThenReturnSuccess(): void
    {
        $country = Country::factory()->make();

        $iso_code = strtolower($country->iso_code);

        $this->getJson("{$this->_end_point}?country_iso={$iso_code}")
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', 0)
            );
    }

    /**
     * @test
     */
    public function whenIndexWithoutCountryIsoThenReturnError(): void
    {
        $this->getJson("{$this->_end_point}")
            ->assertStatus(422)
            ->assertInvalid(['country_iso']);
    }

    /**
     * @test
     */
    public function whenCreateThenReturnSuccess(): void
    {
        $country = Country::factory()->create();

        $data_post = [
            'name' => fake()->name(),
            'region_code' => fake()->numberBetween(10,999),
            'country_id' => $country->id
        ];

        $response = $this->postJson($this->_end_point, $data_post)
            ->assertStatus(201);

        $location = route('regions.show', ['region' => $response['id']]);

        $response
            ->assertHeader('location', $location)
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll($this->_response_fields)
            );
    }
}
