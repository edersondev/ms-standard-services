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

    protected $_end_point = '/api/countries';

    protected $_response_fields = ['id', 'name', 'iso_code', 'iso_code3', 'number_code', 'dial'];

    /**
     * @test
     */
    public function whenIndexThenReturnSuccess(): void
    {
        $number_items = random_int(2, 5);

        Country::factory($number_items)->create();

        $this->get($this->_end_point)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['data', 'links', 'meta'])
                ->has('data', $number_items, fn (AssertableJson $json) =>
                    $json->hasAll($this->_response_fields)
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

        $this->get("{$this->_end_point}?per_page=200")
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll(['data', 'links', 'meta'])
                    ->has('data', $number_items)
                    ->has('meta', fn (AssertableJson $json) =>
                        $json->where('per_page', 100)->etc()
                    )
            );
    }

    /**
     * @test
     * @dataProvider indexFiltersProvider
     */
    public function whenIndexSearchByFieldThenReturnSuccess($field): void
    {
        $countries = $this->generateDummyData();
        $random_country = $countries->random();

        $param_search = $random_country->{$field};

        if ($field === 'name') {
            $param_search = trim(strtolower(substr($random_country->{$field}, 0, 5)));
        }

        $this->get("{$this->_end_point}?{$field}={$param_search}")
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll(['data', 'links', 'meta'])
                    ->has('data', fn (AssertableJson $json) =>
                        $json->first(fn ($json) =>
                            $json->hasAll($this->_response_fields)
                        )
                    )
            );
    }

    public function indexFiltersProvider(): array
    {
        return [
            'when search by name' => ['name'],
            'when search by iso code' => ['iso_code'],
            'when search by iso code3' => ['iso_code3'],
            'when search by number code' => ['number_code']
        ];
    }

    public function generateDummyData(): Collection
    {
        $number_items = random_int(5, 10);
        return Country::factory($number_items)->create();
    }
}
