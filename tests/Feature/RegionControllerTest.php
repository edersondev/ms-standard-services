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
        $data_post = $data_post = $this->getDataPost();

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
    public function whenCreateUseInvalidFieldsThenReturnError($field, $value): void
    {
        $data_post = $this->getDataPost();

        $data_post[$field] = $value;

        $this->postJson($this->_end_point, $data_post)
            ->assertUnprocessable()
            ->assertInvalid([$field]);
    }

    public function createMissingRequiredFieldProvider(): array
    {
        return [
            'when field name is null' => ['name', null],
            'when field name is not string' => ['name', fake()->numberBetween(1,999)],
            'when field name has more than 80 characters' => ['name', fake()->realTextBetween(90)],
            'when field region_code has more than 3 characters' => ['region_code', fake()->numberBetween(1000,9999)],
            'when field country_id is null' => ['country_id', null],
            'when field country_id does not exist in the database' => ['country_id', fake()->numberBetween(1,999)]
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
    public function whenShowDoesNotExistThenReturnError(): void
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

    /**
     * @test
     * @dataProvider updateEachFieldProvider
     */
    public function whenUpdateThenReturnSuccess($field, $value): void
    {
        $region = Region::factory()->create();

        $data_patch = [$field => $value];

        if ($field === 'country_id') {
            $country = Country::factory()->create();
            $data_patch[$field] = $value = $country->id;
        }

        $this->patchJson("{$this->_end_point}/{$region->id}", $data_patch)
            ->assertNoContent();
        
        $this->getJson("{$this->_end_point}/{$region->id}")
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', fn ($json) =>
                    $json->where($field, $value)->etc()
                )
            );
    }

    public function updateEachFieldProvider(): array
    {
        return [
            'when update field name' => ['name', fake()->name()],
            'when update field region code' => ['region_code', fake()->numberBetween(100,999)],
            'when update field country id' => ['country_id', null]
        ];
    }

    /**
     * @test
     */
    public function whenDestroyThenReturnSuccess(): void
    {
        $region = Region::factory()->create();

        $this->deleteJson("{$this->_end_point}/{$region->id}")
            ->assertNoContent();

        $this->getJson("{$this->_end_point}/{$region->id}")
            ->assertNotFound();
    }

    public function getDataPost($country_id = null): array
    {
        return [
            'name' => fake()->name(),
            'region_code' => fake()->numberBetween(100,999),
            'country_id' => $country_id ?? Country::factory()->create()->id
        ];
    }
}
