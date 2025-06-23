<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    private $worldCities = [
        'Jakarta' => ['country' => 'ID', 'timezone' => 'Asia/Jakarta', 'flag' => '🇮🇩'],
        'New York' => ['country' => 'US', 'timezone' => 'America/New_York', 'flag' => '🇺🇸'],
        'London' => ['country' => 'GB', 'timezone' => 'Europe/London', 'flag' => '🇬🇧'],
        'Tokyo' => ['country' => 'JP', 'timezone' => 'Asia/Tokyo', 'flag' => '🇯🇵'],
        'Sydney' => ['country' => 'AU', 'timezone' => 'Australia/Sydney', 'flag' => '🇦🇺'],
        'Paris' => ['country' => 'FR', 'timezone' => 'Europe/Paris', 'flag' => '🇫🇷'],
        'Singapore' => ['country' => 'SG', 'timezone' => 'Asia/Singapore', 'flag' => '🇸🇬'],
        'Mumbai' => ['country' => 'IN', 'timezone' => 'Asia/Kolkata', 'flag' => '🇮🇳'],
    ];

    public function index(Request $request)
    {
        $weather = session('weather');
        $error = session('error');

        // Get world weather data
        $worldWeather = $this->getWorldWeather();

        return view('weather', compact('weather', 'error', 'worldWeather'));
    }

    public function getWeather(Request $request)
    {
        $request->validate([
            'city' => 'required|string',
        ]);

        $apiKey = env('OPENWEATHER_API_KEY');
        $city = $request->input('city');

        $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric',
            'lang' => 'id',
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return redirect()->route('weather.index')->with('weather', $data);
        } else {
            return redirect()->route('weather.index')->with('error', 'Kota tidak ditemukan!');
        }
    }

    private function getWorldWeather()
    {
        $apiKey = env('OPENWEATHER_API_KEY');
        $worldWeatherData = [];

        foreach ($this->worldCities as $city => $info) {
            // Cache weather data for 10 minutes to avoid too many API calls
            $cacheKey = 'weather_' . str_replace(' ', '_', strtolower($city));

            $weatherData = Cache::remember($cacheKey, 600, function () use ($city, $apiKey) {
                try {
                    $response = Http::timeout(5)->get("https://api.openweathermap.org/data/2.5/weather", [
                        'q' => $city,
                        'appid' => $apiKey,
                        'units' => 'metric',
                        'lang' => 'id',
                    ]);

                    if ($response->successful()) {
                        return $response->json();
                    }
                } catch (\Exception $e) {
                    // Return null if API call fails
                    return null;
                }
                return null;
            });

            if ($weatherData) {
                $worldWeatherData[$city] = [
                    'data' => $weatherData,
                    'info' => $info,
                    'local_time' => $this->getLocalTime($info['timezone'])
                ];
            }
        }

        return $worldWeatherData;
    }

    private function getLocalTime($timezone)
    {
        try {
            $date = new \DateTime('now', new \DateTimeZone($timezone));
            return $date->format('H:i');
        } catch (\Exception $e) {
            return '00:00';
        }
    }

    private function getTimezoneAbbreviation($timezone)
    {
        $abbreviations = [
            'Asia/Jakarta' => 'WIB',
            'America/New_York' => 'EST',
            'Europe/London' => 'GMT',
            'Asia/Tokyo' => 'JST',
            'Australia/Sydney' => 'AEDT',
            'Europe/Paris' => 'CET',
            'Asia/Singapore' => 'SGT',
            'Asia/Kolkata' => 'IST',
        ];

        return $abbreviations[$timezone] ?? 'UTC';
    }

    public function refreshWorldWeather()
    {
        // Clear cache for all world cities
        foreach ($this->worldCities as $city => $info) {
            $cacheKey = 'weather_' . str_replace(' ', '_', strtolower($city));
            Cache::forget($cacheKey);
        }

        return redirect()->route('weather.index')->with('success', 'Data cuaca dunia telah diperbarui!');
    }
}
