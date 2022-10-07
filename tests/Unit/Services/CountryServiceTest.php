<?php

namespace Tests\Unit\Services;

use App\Http\Services\CountryService;
use App\Repositories\CountryRepository;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class CountryServiceTest extends TestCase
{
    /**
     * @var CountryRepository&MockObject
     */
    protected $_repository;

    /**
     * @var CountryService
     */
    protected $_service;

    /**
     * @var Builder&MockObject
     */
    protected $_builder;

    /**
     * This method is called before each test.
     */
    public function setUp(): void
    {
        $this->_repository = $this->createMock(CountryRepository::class);

        $this->_service = new CountryService($this->_repository);

        $this->_builder = $this->createMock(Builder::class);

        parent::setUp();
    }

    /**
     * @test
     */
    public function whenIndexThenReturnEmpty(): void
    {
        $this->_builder
            ->expects($this->once())
            ->method('paginate')
            ->willReturn(new LengthAwarePaginator(collect(), 0, 10));

        $this->_repository->method('index')->willReturn($this->_builder);

        $result = $this->_service->index(new Request());

        $this->assertNotNull($result);
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount(0, $result->items());
    }

    /**
     * @test
     */
    public function whenIndexThenReturnCollection(): void
    {
        $number_items = random_int(2, 5);

        $countries = Country::factory($number_items)->make();

        $this->_builder
            ->expects($this->once())
            ->method('paginate')
            ->willReturn(new LengthAwarePaginator($countries, $number_items, 10));

        $this->_repository
            ->expects($this->once())
            ->method('index')
            ->willReturn($this->_builder);

        $result = $this->_service->index(new Request());

        $this->assertNotNull($result);
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(10, $result->perPage());
        $this->assertCount($number_items, $result->items());
        $this->assertContainsOnlyInstancesOf(Country::class, $result->items());
    }
}
