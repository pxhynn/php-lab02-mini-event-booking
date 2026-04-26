<?php
namespace App\Controllers;
use App\Support\Response;

class BookingController {
    public function store(array $shows, array $config): void {
        $headers = function_exists('getallheaders') ? getallheaders() : [];
        $contentType = $headers['Content-Type'] ?? $headers['content-type'] ?? ($_SERVER['CONTENT_TYPE'] ?? '');

        if (!str_contains(strtolower($contentType), 'application/json')) {
            Response::json(415, [
                'error' => 'Unsupported Media Type',
                'message' => 'Content-Type must be application/json'
            ]);
        }

        $raw = file_get_contents('php://input');
        $payload = json_decode($raw, true);

        if (!is_array($payload)) {
            Response::json(400, [
                'error' => 'Bad Request',
                'message' => 'Invalid JSON body'
            ]);
        }

        $showId = $payload['show_id'] ?? null;
        $customerName = trim($payload['customer_name'] ?? '');
        $email = trim($payload['email'] ?? '');
        $quantity = (int) ($payload['quantity'] ?? 0);

        if (!$showId || $customerName === '' || $email === '' || $quantity <= 0) {
            Response::json(422, [
                'error' => 'Unprocessable Content',
                'message' => 'show_id, customer_name, email, quantity are required and must be valid'
            ]);
        }

        if ($quantity > $config['app']['max_tickets']) {
            Response::json(422, [
                'error' => 'Unprocessable Content',
                'message' => 'Quantity exceeds allowed limit per request'
            ]);
        }

        $selectedShow = null;
        foreach ($shows as $show) {
            if ($show['id'] === (int) $showId) {
                $selectedShow = $show;
                break;
            }
        }

        if (!$selectedShow) {
            Response::json(422, [
                'error' => 'Unprocessable Content',
                'message' => 'Selected show does not exist'
            ]);
        }

        if ($selectedShow['tickets_available'] < $quantity) {
            Response::json(422, [
                'error' => 'Unprocessable Content',
                'message' => 'Not enough tickets available'
            ]);
        }

        $bookingId = "BKG-" . time();

        $storageDir = dirname(__DIR__, 2) . '/storage';
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0777, true);
        }
        $filePath = $storageDir . '/bookings.json';
        $currentBookings = file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];
        if (!is_array($currentBookings)) {
            $currentBookings = [];
        }
        $currentBookings[] = [
            'booking_id' => $bookingId,
            'customer_name' => $customerName,
            'show_id' => (int) $showId,
            'quantity' => $quantity,
            'booking_time' => date('Y-m-d H:i:s')
        ];
        file_put_contents($filePath, json_encode($currentBookings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        Response::json(201, [
            'message' => 'Booking created successfully',
            'data' => [
                'booking_id' => $bookingId,
                'customer_name' => $customerName,
                'show_id' => (int) $showId,
                'quantity' => $quantity
            ]
        ], ['Location' => '/bookings/' . $bookingId]);
    }

    public function options(): void {
        http_response_code(204);
        header('Allow: POST, OPTIONS');
        exit;
    }
}