<?php

return [
    'paths' => ['api/*'], // Ou '*' para todas as rotas
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'], // Ou ['http://localhost:5173'] para segurança
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
