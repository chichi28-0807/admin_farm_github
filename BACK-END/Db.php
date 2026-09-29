<?php
declare(strict_types=1);

require_once __DIR__ . '/database.php';

function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $config = require __DIR__ . '/Config.php';
        $pdo = Database::connect($config['db']);
    }

    return $pdo;
}
