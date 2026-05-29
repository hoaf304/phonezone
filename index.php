<?php
define('ROOT_PATH', __DIR__);
define('APP_START', microtime(true));

require_once ROOT_PATH . '/core/Env.php';
Env::load(ROOT_PATH . '/.env');

require_once ROOT_PATH . '/config/app.php';
require_once ROOT_PATH . '/config/database.php';

spl_autoload_register(function ($class) {
    $paths = [
        ROOT_PATH . '/core/',
        ROOT_PATH . '/app/controllers/',
        ROOT_PATH . '/app/controllers/Admin/',
        ROOT_PATH . '/app/models/',
    ];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) { require_once $file; return; }
    }
});

// Load helpers
require_once ROOT_PATH . '/core/Helpers.php';

Session::start();

$router = new Router();
require_once ROOT_PATH . '/routes.php';
$router->dispatch();


//http://localhost/phonezone