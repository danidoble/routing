<?php
/**
 * Created by (c)danidoble 2021.
 * @website https://github.com/danidoble
 * @website https://danidoble.com
 */

use Danidoble\Routing\Route;
use Danidoble\Routing\Testing;

$routes = new Route();
$routes->add('/', [Testing::class, 'index'])->setMethods(['GET','POST']); //only get and post allowed
$routes->add('/help', [Testing::class, 'index'])->setMethods('GET'); //only get allowed
$routes->add('/danidoble', [Testing::class, 'index', 'a']); // all methods allowed
$routes->dispatch();
