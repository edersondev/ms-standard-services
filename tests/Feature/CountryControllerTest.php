<?php

namespace Tests\Feature;

use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class CountryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function whenIndexThenReturnSuccess(): void
    {
        $number_items = random_int(2, 5);

        Country::factory($number_items)->create();

        $response = $this->get('/api/countries');

        $response->assertOk();

        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['data', 'links', 'meta'])
                ->has('data', $number_items)
                ->has(
                    'data.0',
                    fn (AssertableJson $json) =>
                    $json->hasAll(['id', 'name', 'iso_code', 'iso_code3', 'number_code', 'dial'])
                )
        );
    }

    /**
     * @test
     */
    public function whenIndexWithPerPageMoreThanOneHundred(): void
    {
        $number_items = random_int(5, 10);

        Country::factory($number_items)->create();

        $response = $this->get('/api/countries?per_page=200');

        $response->assertOk();

        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['data', 'links', 'meta'])
                ->has('data', $number_items)
                ->has('meta', fn (AssertableJson $json) => $json->where('per_page', 100)->etc())
        );
    }

    /**
     * @test
     */
    public function whenIndexSearchByNameThenReturnSuccess(): void
    {
        $countries = $this->generateDummyData();

        $random_country = $countries->random();

        $param_search = trim(strtolower(substr($random_country->name, 0, 5)));

        $response = $this->get("/api/countries?name={$param_search}");

        $response->assertOk();

        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['data', 'links', 'meta'])
                ->has('data.0')
        );
    }

    /**
     * @test
     */
    public function whenIndexSearchByIsoCodeThenReturnSuccess(): void
    {
        $countries = $this->generateDummyData();

        $random_country = $countries->random();

        $param_search = $random_country->iso_code;

        $response = $this->get("/api/countries?iso_code={$param_search}");

        $response->assertOk();

        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['data', 'links', 'meta'])
                ->has('data.0')
        );
    }

    /**
     * @test
     */
    public function whenIndexSearchByIsoCode3ThenReturnSuccess(): void
    {
        $countries = $this->generateDummyData();

        $random_country = $countries->random();

        $param_search = $random_country->iso_code3;

        $response = $this->get("/api/countries?iso_code3={$param_search}");

        $response->assertOk();

        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['data', 'links', 'meta'])
                ->has('data.0')
        );
    }

    /**
     * @test
     */
    public function whenIndexSearchByNumberCodeThenReturnSuccess(): void
    {
        $countries = $this->generateDummyData();

        $random_country = $countries->random();

        $param_search = $random_country->number_code;

        $response = $this->get("/api/countries?number_code={$param_search}");

        $response->assertOk();

        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['data', 'links', 'meta'])
                ->has('data.0')
        );
    }

    public function generateDummyData(): Collection
    {
        $number_items = random_int(5, 10);
        return Country::factory($number_items)->create();
    }
}
