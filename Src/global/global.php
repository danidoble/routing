<?php
/**
 * Created by (c)danidoble 2022.
 * @website https://github.com/danidoble
 * @website https://danidoble.com
 */

use Danidoble\Routing\Exceptions\ViewNotFoundException;
use Danidoble\Routing\View;

if (!function_exists('dView')) {
    /**
     * @param string $name
     * @param array $values
     * @return void
     */
    function dView(string $name, array $values = []): void
    {
        $path_dir = rtrim(View::$BASE_VIEW_PATH, '/') . DIRECTORY_SEPARATOR;
        $name = str_replace('.php', '', $name);
        $name = str_replace('.html', '', $name);
        $name = str_replace('.', '/', $name);
        $name = trim($name, '/');
        $view_base = $path_dir . $name;
        if (file_exists(($view_base . '.html')) && !is_dir(($view_base . '.html'))) {
            $view_base .= '.html';
        } else {
            $view_base .= '.php';
        }

        if (!file_exists($view_base) || is_dir($view_base)) {
            throw new ViewNotFoundException("The view %s was not founded", $view_base);
        }

        $view = new View();
        if (!empty($values)) {
            $view->with($values);
        }
        $view->render($view_base);
    }
}