<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherAPI
{
    /**
     * Open-Meteo API base URL (free, no API key required)
     */
    protected string $baseUrl = 'https://api.open-meteo.com/v1/forecast';

    /**
     * Default location: Haarlem, Netherlands
     */
    protected float $latitude = 52.381;
    protected float $longitude = 4.636;

    /**
     * Get weather forecast for a specific date.
     * Returns current conditions if date is today, otherwise returns daily forecast.
     *
     * @param string $date Date in Y-m-d format
     * @return array|null
     */
    public function get_forecast(string $date): ?array
    {
        $cacheKey = "weather_forecast_{$date}";

        return Cache::remember($cacheKey, 30 * 60, function () use ($date) {
            try {
                $response = Http::timeout(5)->get($this->baseUrl, [
                    'latitude' => $this->latitude,
                    'longitude' => $this->longitude,
                    'daily' => 'weather_code,temperature_2m_max,temperature_2m_min,precipitation_sum,precipitation_probability_max,wind_speed_10m_max',
                    'hourly' => 'temperature_2m,weather_code,precipitation_probability,wind_speed_10m',
                    'start_date' => $date,
                    'end_date' => $date,
                    'timezone' => 'Europe/Amsterdam',
                ]);

                if (!$response->successful()) {
                    return null;
                }

                $data = $response->json();

                return [
                    'date' => $date,
                    'location' => 'Haarlem',
                    'daily' => $this->format_daily($data['daily'] ?? []),
                    'hourly' => $this->format_hourly($data['hourly'] ?? []),
                ];
            } catch (\Exception $e) {
                return null;
            }
        });
    }

    /**
     * Format daily forecast data.
     */
    protected function format_daily(array $daily): array
    {
        if (empty($daily['time'])) {
            return [];
        }

        return [
            'temp_max' => $daily['temperature_2m_max'][0] ?? null,
            'temp_min' => $daily['temperature_2m_min'][0] ?? null,
            'precipitation_sum' => $daily['precipitation_sum'][0] ?? 0,
            'precipitation_probability' => $daily['precipitation_probability_max'][0] ?? 0,
            'wind_speed_max' => $daily['wind_speed_10m_max'][0] ?? 0,
            'weather_code' => $daily['weather_code'][0] ?? 0,
            'description' => $this->weather_code_to_description($daily['weather_code'][0] ?? 0),
            'icon' => $this->weather_code_to_icon($daily['weather_code'][0] ?? 0),
        ];
    }

    /**
     * Format hourly forecast data.
     */
    protected function format_hourly(array $hourly): array
    {
        if (empty($hourly['time'])) {
            return [];
        }

        $hours = [];
        foreach ($hourly['time'] as $i => $time) {
            $hours[] = [
                'time' => date('H:i', strtotime($time)),
                'temperature' => $hourly['temperature_2m'][$i] ?? null,
                'weather_code' => $hourly['weather_code'][$i] ?? 0,
                'icon' => $this->weather_code_to_icon($hourly['weather_code'][$i] ?? 0),
                'precipitation_probability' => $hourly['precipitation_probability'][$i] ?? 0,
                'wind_speed' => $hourly['wind_speed_10m'][$i] ?? 0,
            ];
        }

        return $hours;
    }

    /**
     * Convert WMO weather code to human-readable description.
     * @see https://open-meteo.com/en/docs#weathervariables
     */
    protected function weather_code_to_description(int $code): string
    {
        return match (true) {
            $code === 0 => 'Clear sky',
            $code <= 3 => 'Partly cloudy',
            $code <= 49 => 'Fog',
            $code <= 59 => 'Drizzle',
            $code <= 69 => 'Rain',
            $code <= 79 => 'Snow',
            $code <= 84 => 'Rain showers',
            $code <= 86 => 'Snow showers',
            $code >= 95 => 'Thunderstorm',
            default => 'Unknown',
        };
    }

    /**
     * Convert WMO weather code to an emoji icon.
     */
    protected function weather_code_to_icon(int $code): string
    {
        return match (true) {
            $code === 0 => '☀️',
            $code <= 3 => '⛅',
            $code <= 49 => '🌫️',
            $code <= 59 => '🌦️',
            $code <= 69 => '🌧️',
            $code <= 79 => '🌨️',
            $code <= 84 => '🌧️',
            $code <= 86 => '🌨️',
            $code >= 95 => '⛈️',
            default => '🌤️',
        };
    }
}
