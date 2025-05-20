<?php

return [
    /*
    |--------------------------------------------------------------------------
    | GIPHY API Configuration
    |--------------------------------------------------------------------------
    | API key and base URL to call GIPHY endpoints.
    */
    'api_key' => env('GIPHY_API_KEY'),
    'base_url' => env('GIPHY_BASE_URL', 'https://api.giphy.com/v1/gifs'),
];
