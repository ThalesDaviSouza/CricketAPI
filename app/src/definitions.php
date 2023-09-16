<?php

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/db.php';

use function DI\create;

return [
    'config' => create(Config::class),
    'db' => create(DB::class)
];
