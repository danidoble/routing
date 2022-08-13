<?php
/**
 * Created by (c)danidoble 2022.
 * @website https://github.com/danidoble
 * @website https://danidoble.com
 */

namespace Danidoble\Routing\Interfaces;

use Danidoble\Routing\Exceptions\ClassNameNotFoundException;
use Symfony\Component\Routing\Route as symfonyRoute;

interface IRoute
{
    /**
     * @param string $path The path pattern to match
     * @param array $execute An array with important parameters for default parameter values
     * @param array $defaults An array of default parameter values
     * @param array $requirements An array of requirements for parameters (regexes)
     * @param array $options An array of options
     * @param string|null $host The host pattern to match
     * @param string|string[] $schemes A required URI scheme or an array of restricted schemes
     * @param string|string[] $methods A required HTTP method or an array of restricted methods
     * @param string|null $condition A condition that should evaluate to true for the route to match
     * @throws ClassNameNotFoundException
     */
    public function add(string $path, array $execute, array $defaults = [], array $requirements = [], array $options = [], ?string $host = '', array|string $schemes = [], array|string $methods = [], ?string $condition = ''): symfonyRoute;

    /**
     * @return void
     */
    public function dispatch(): void;

    /**
     * @param int $no
     * @param string $view
     * @return void
     * @throws ClassNameNotFoundException
     */
    public function error(int $no, string $view): void;

    /**
     * @param int $no
     * @return string|null
     */
    public function getError(int $no): ?string;
}