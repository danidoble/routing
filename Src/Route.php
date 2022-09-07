<?php
/**
 * Created by (c)danidoble 2022.
 * @website https://github.com/danidoble
 * @website https://danidoble.com
 */

namespace Danidoble\Routing;

use Danidoble\Routing\Exceptions\ViewErrorCodeNotFoundException;
use Danidoble\Routing\Interfaces\IRoute;
use Danidoble\Routing\Exceptions\ClassNameNotFoundException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Route as symfonyRoute;
use Ramsey\Uuid\Uuid;

class Route extends Bootstrap implements IRoute
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
    public function add(string $path, array $execute, array $defaults = [], array $requirements = [], array $options = [], ?string $host = '', array|string $schemes = [], array|string $methods = [], ?string $condition = ''): symfonyRoute
    {
        if (empty($execute) || !isset($execute[0])) {
            throw new ClassNameNotFoundException("Class name is required", 500);
        }

        $defaults = array_merge($defaults, [
            '_controller_' => $execute[0],
            '_function_' => $execute[1] ?? null,
            '_name_' => $execute[2] ?? (Uuid::uuid4())->toString(),
        ]);

        $route = new symfonyRoute($path, $defaults, $requirements, $options, $host, $schemes, $methods, $condition);
        $this->routes->add($defaults['_name_'], $route);
        $this->routes_names = array_keys($this->routes->all());
        return $route;
    }

    /**
     * Get route by name
     * @param string $name
     * @param bool $string
     * @return string|symfonyRoute
     */
    public function get(string $name, bool $string = true): string|symfonyRoute
    {
        if (!in_array($name, $this->routes_names)) {
            throw new RouteNotFoundException("Route $name not found");
        }
        if ($string) {
            return $this->routes->all()[$name]->getPath();
        }
        return $this->routes->all()[$name];
    }

    /**
     * @return void
     */
    public function dispatch(): void
    {
        try {
            $this->setConfig();
            $route_params = $this->matcher->match($this->context->getPathInfo());
            $_class = "\\" . $route_params['_controller_'];
            $route_to_invoke = new $_class($this->context, $this->routes, $this->matcher, $this);
            if ($route_params['_function_'] !== null) {
                $route_to_invoke->{$route_params['_function_']}();
            }
        } catch (ResourceNotFoundException) {
            $this->showError(404);
        } catch (MethodNotAllowedException) {
            $this->showError(405);
        }
    }

    /**
     * @param int $no
     * @param string $view
     * @return void
     * @throws FileNotFoundException
     */
    public function error(int $no, string $view): void
    {
        switch ($no) {
            case 404:
                $this->setViewError404($view);
                break;
            case 405:
                $this->setViewError405($view);
                break;
            default:
                throw new ViewErrorCodeNotFoundException("Error code not found", $no);
        }
    }

    /**
     * @param int $no
     * @return string|null
     */
    public function getError(int $no): ?string
    {
        return match ($no) {
            404 => $this->getViewError404(),
            405 => $this->getViewError405(),
            default => null,
        };
    }
}