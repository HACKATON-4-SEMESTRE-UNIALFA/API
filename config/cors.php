<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Configure os caminhos, origens, métodos e cabeçalhos permitidos.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'], // Permite todos os métodos (GET, POST, etc.)

    'allowed_origins' => ['*'], // Permite qualquer origem (ou especifique a origem)

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Permite todos os cabeçalhos

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
