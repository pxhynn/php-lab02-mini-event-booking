<?php
namespace App\Controllers;

class HomeController {
    public function index(array $config, array $shows): array {
        return [
            'title' => 'Mini Event Booking App',
            'app_name' => $config['app']['name'],
            'organizer' => $config['app']['company'] ?? $config['app']['organizer'],
            'app_env' => $config['app']['env'],
            'app_debug' => $config['app']['debug'] ? 'true' : 'false',
            'shows' => $shows
        ];
    }
}