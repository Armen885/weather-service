<?php

namespace Tests\Feature;

use App\Services\WeatherService;
use Attogram\Weatherbit\Weatherbit;
use Mockery;
use Tests\TestCase;

class WeatherServiceTest extends TestCase
{
    private Weatherbit $weatherbit;

    private const CITY = 'Yerevan';

    public function setUp(): void
    {
        parent::setUp();
        $this->weatherService = Mockery::mock(WeatherService::class);
        $this->weatherbit = Mockery::mock(Weatherbit::class);
        $this->weatherbit->shouldReceive('setKey')->once()->with(config('weather.api_key'));
        $this->weatherbit->shouldReceive('setLocationByCity')->once()->with(self::CITY);
    }

    public function test_it_sends_alert_when_precipitation_is_high()
    {
        $this->weatherbit->shouldReceive('getDailyForecast')->once()->andReturn([
            'data' => [
                [
                    'weather' => [
                        'code' => 500,
                    ],
                    'uv' => 5,
                ],
                [
                    'weather' => [
                        'code' => 202,
                        'description' => 'Thunderstorm with heavy rain'
                    ],
                    'uv' => 3,
                ],
            ]
        ]);

        $weatherService = new WeatherService($this->weatherbit);

        $weatherService->weatherCheck(self::CITY);

        $this->assertTrue($weatherService->getSendAlert());
        $this->assertEquals('There will be Thunderstorm with heavy rain', $weatherService->getPrecipitationMessage());
    }

    public function testWeatherCheckWithHighUvIndex()
    {
        $this->weatherbit->shouldReceive('getDailyForecast')->once()->andReturn([
            'data' => [
                [
                    'weather' => [
                        'code' => 3000,
                    ],
                    'uv' => 7,
                ],
                [
                    'weather' => [
                        'code' => 9000,
                    ],
                    'uv' => 8,
                ],
            ]
        ]);

        $weatherService = new WeatherService($this->weatherbit);

        $weatherService->weatherCheck(self::CITY);

        $this->assertTrue($weatherService->getSendAlert());
        $this->assertEquals('Wear SPF 30+, protective clothing, and sunglasses; seek shade frequently.', $weatherService->getUvMessage());
    }
}
