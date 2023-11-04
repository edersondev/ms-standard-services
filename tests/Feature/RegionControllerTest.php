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
            ->assertOk()
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
            ->assertOk()
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
            ->assertUnprocessable()
            ->assertInvalid(['country_iso']);
    }

    /**
     * @test
     */
    public function whenCreateThenReturnSuccess(): void
    {
        $country = Country::factory()->create();

        $data_post = $data_post = $this->getDataPost($country->id);

        $response = $this->postJson($this->_end_point, $data_post)
            ->assertCreated();

        $location = route('regions.show', ['region' => $response['id']]);

        $response
            ->assertLocation($location)
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll($this->_response_fields)
            );
    }

    /**
     * @test
     * @dataProvider createMissingRequiredFieldProvider
     */
    public function whenCreateValidateFields($field, $status_code): void
    {
        $country_id = fake()->numberBetween(10,999);

        if ($field !== 'invalid_country_id') {
            $country = Country::factory()->create();
            $country_id = $country->id;
        }

        $data_post = $this->getDataPost($country_id);

        unset($data_post[$field]);

        $response = $this->postJson($this->_end_point, $data_post)
            ->assertStatus($status_code);
        
        if ($status_code !== 201 && $field !== 'invalid_country_id') {
            $response->assertInvalid([$field]);
        }
    }

    public function createMissingRequiredFieldProvider(): array
    {
        return [
            'when missing field name' => ['name', 422],
            'when missing field region_code' => ['region_code', 201],
            'when missing field country_id' => ['country_id', 422],
            'when country_id doesn\'t exist in the database' => ['invalid_country_id', 422]
        ];
    }

    /**
     * @test
     */
    public function whenShowThenReturnSuccess(): void
    {
        $region = Region::factory()->create();

        $this->getJson("{$this->_end_point}/{$region->id}")
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', fn ($json) =>
                    $json->hasAll($this->_response_fields)
                )
            );
    }

    /**
     * @test
     */
    public function whenShowDoesntExistThenReturnError(): void
    {
        $id = fake()->numberBetween(1,999);

        $this->getJson("{$this->_end_point}/{$id}")
            ->assertNotFound();
    }

    /**
     * @test
     */
    public function whenShowWithParamIdAsStringThenReturnError(): void
    {
        $string = fake()->word();

        $response = $this->getJson("{$this->_end_point}/{$string}")
            ->assertStatus(500);

        $this->assertEquals('TypeError', $response['exception']);
    }

    public function getDataPost($country_id): array
    {
        return [
            'name' => fake()->name(),
            'region_code' => fake()->numberBetween(100,999),
            'country_id' => $country_id
        ];
    }
}
