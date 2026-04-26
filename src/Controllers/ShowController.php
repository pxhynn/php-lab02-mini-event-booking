<?php
namespace App\Controllers;
use App\Support\Response;

class ShowController {
    public function index(array $shows): void {
        $keyword = trim($_GET['search'] ?? '');
        
        $filteredShows = $shows;

        if ($keyword !== '') {
            $filteredShows = array_filter($shows, function($show) use ($keyword) {
                return stripos($show['title'], $keyword) !== false || stripos($show['artist'], $keyword) !== false;
            });
            $filteredShows = array_values($filteredShows);
        }

        Response::json(200, [
            'message' => 'Show list retrieved successfully',
            'total_results' => count($filteredShows), 
            'data' => $filteredShows
        ]);
    }

    public function head(): void {
        http_response_code(200);
        header('Content-Type: application/json; charset=UTF-8');
        exit;
    }
}