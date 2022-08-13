<?php
/**
 * Created by (c)danidoble 2022.
 * @website https://github.com/danidoble
 * @website https://danidoble.com
 */

use Symfony\Component\ErrorHandler\Debug;

include __DIR__ . "/../vendor/autoload.php";

Debug::enable();

include_once __DIR__."/config/app.php";
include_once __DIR__."/routes/web.php";
