<?php
/**
 * Created by (c)danidoble 2021.
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
    public function add(string $path, array $execute, array $defaults = [], array $requirements = [], array $options = [], ?string $host = '', $schemes = [], $methods = [], ?string $condition = ''): symfonyRoute
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
        return $route;
    }

    /**
     * @return void
     */
    public function dispatch()
    {
        try {
            $this->setConfig();
            $route_params = $this->matcher->match($this->context->getPathInfo());
            $_class = "\\" . $route_params['_controller_'];
            $route_to_invoke = new $_class($this->context,$this->routes,$this->matcher,$this);
            if($route_params['_function_'] !== null) {
                $route_to_invoke->{$route_params['_function_']}();
            }
        } catch (ResourceNotFoundException $e) {
            $this->showError(404);
        }
        catch (MethodNotAllowedException $e){
            $this->showError(405);
        }
    }

    /**
     * @param $no
     * @param $view
     * @throws FileNotFoundException
     */
    public function error($no,$view){
        switch ($no){
            case 404:
                $this->setViewError404($view);
                break;
            case 405:
                $this->setViewError405($view);
                break;
            default:
                throw new ViewErrorCodeNotFoundException("Error code not found",$no);
        }
    }

    /**
     * @param $no
     * @return string|null
     */
    public function getError($no): ?string
    {
        switch ($no){
            case 404:
                return $this->getViewError404();
            case 405:
                return $this->getViewError405();
        }
        return null;
    }
}