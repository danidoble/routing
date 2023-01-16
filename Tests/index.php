<?php
/**
 * Created by (c)danidoble 2022.
 * @website https://github.com/danidoble
 * @website https://danidoble.com
 */

$base_path = __DIR__ . DIRECTORY_SEPARATOR; // base path of app

if(!file_exists(__DIR__.'/.env')){
    die("Copy \".env.example\" file to \".env\" and modify the content");
}

include __DIR__ . "/../vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable($base_path);
$dotenv->load();


//\Symfony\Component\ErrorHandler\Debug::enable(); // show beautiful pages if it has an Exception with symfony

// show beautiful pages if it has an Exception with spatie
\Spatie\Ignition\Ignition::make()
    ->applicationPath($base_path)
    ->shouldDisplayException(env('APP_DEBUG', false))
    ->setTheme('dark')
    ->register();


\Danidoble\Routing\View::$BASE_VIEW_PATH = $base_path . env('APP_VIEW_DIR'); // assign route base of your directory of views
include_once __DIR__ . "/routes/web.php";
