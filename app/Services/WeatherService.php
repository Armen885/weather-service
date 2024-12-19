<?php

namespace App\Services;

use Attogram\Weatherbit\Weatherbit;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    private const FORECAST_DAYS = 2;

    private const HIGH_UVI = 6;

    private const EXTREME_UVI = 11;
    public bool $sendAlert = false;

    public string $precipitationMessage = "";
    public string $uvMessage = "";

    public function __construct(private readonly Weatherbit $weatherApi)
    {
    }


    /**
     * @param string $city
     * @return void
     */
    public function weatherCheck(string $city)
    {
        $weatherInfo = $this->getWeatherByCity($city);
        if (is_array($weatherInfo)) {
            $this->isPrecipitationLevelHigh($weatherInfo);
            $this->isUvHigh($weatherInfo['uv']);
        }
    }

    /**
     * @param string $city
     * @return array|void
     */
    private function getWeatherByCity(string $city)
    {
        try {
            $this->weatherApi->setKey(config('weather.api_key'));
            $this->weatherApi->setLocationByCity($city);
            $twoDaysForecast = $this->weatherApi->getDailyForecast(self::FORECAST_DAYS);
            if (count($twoDaysForecast['data']) > 1) {
                //Tomorrow's weather
                return $twoDaysForecast['data'][1];
            } else {
                Log::error("No data found for tomorrow's weather", [
                    'city' => $city,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error occurred when sending request to the weather api.', [
                'city' => $city,
                'errMsg' => $e->getMessage(),
                'errCode' => $e->getCode(),
                'trace' => $e->getTrace()
            ]);
        }
    }

    /**
     * @param $data
     * @return void
     */
    private function isPrecipitationLevelHigh($data)
    {
        $precipitations = config('weather.precipitations');
        $precipitationCode = $data['weather']['code'];

        if (array_key_exists($precipitationCode, $precipitations)) {
            $this->setSendAlert(true);
            $message = $precipitations[$precipitationCode];
            $this->setPrecipitationMessage($message);
        }
    }

    /**
     * @param int $uvIndex
     * @return void
     */
    private function isUvHigh(int $uvIndex)
    {
        if ($uvIndex >= self::HIGH_UVI) {
            $this->setSendAlert(true);
            $uviMessages = config('weather.uvi');

            if ($uvIndex > self::EXTREME_UVI) {
                $message = $uviMessages[self::EXTREME_UVI]['message'];
            } else {
                $message = $uviMessages[$uvIndex]['message'];
            }

            $this->setUvMessage($message);
        }
    }

    /**
     * @param bool $sendAlert
     * @return $this
     */
    public function setSendAlert(bool $sendAlert): static
    {
        $this->sendAlert = $sendAlert;

        return $this;
    }

    /**
     * @return bool
     */
    public function getSendAlert(): bool
    {
        return $this->sendAlert;
    }

    /**
     * @param string $precipitationMessage
     * @return $this
     */
    public function setPrecipitationMessage(string $precipitationMessage): static
    {
        $this->precipitationMessage = 'There will be ' .  $precipitationMessage;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrecipitationMessage(): string
    {
        return $this->precipitationMessage;
    }

    /**
     * @param string $uvMessage
     * @return $this
     */
    public function setUvMessage(string $uvMessage): static
    {
        $this->uvMessage = $uvMessage;

        return $this;
    }

    /**
     * @return string
     */
    public function getUvMessage(): string
    {
        return $this->uvMessage;
    }
}
