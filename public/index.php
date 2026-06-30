<?php

declare(strict_types=1);

require dirname(__DIR__) . '/src/Database.php';
require dirname(__DIR__) . '/src/ActivityRepository.php';
require dirname(__DIR__) . '/src/Api.php';

use RopaDesk\Api;

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
(new Api())->handle($_SERVER['REQUEST_METHOD'] ?? 'GET', $path);
