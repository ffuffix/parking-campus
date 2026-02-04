<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CarAPI
{
    protected $BASE_URL;
    public function __construct()
    {
        $this->BASE_URL = config('services.car_api.base_url');
    }

    public function get_cars()
    {
        // ttl: 60 minutes
        // and cache the response, using the cache facade!!
        return \Illuminate\Support\Facades\Cache::remember('cars_api_list', 60 * 60, function () {
            $response = Http::get("{$this->BASE_URL}/api/cars");
            return $response->json();
        });
    }
}