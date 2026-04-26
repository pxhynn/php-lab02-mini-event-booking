<?php
use App\Support\Env;

return [
    'app' => [
        'name' => Env::get('APP_NAME', 'Mini Event Booking'),
        'env' => Env::get('APP_ENV', 'prod'),
        'debug' => Env::bool('APP_DEBUG', false),
        'url' => Env::get('APP_URL', 'http://localhost:8000'),
        'organizer' => Env::get('ORGANIZER_NAME', 'Entertainment Center'),
        'max_tickets' => Env::int('MAX_TICKETS_PER_BOOKING', 1),
    ]
];