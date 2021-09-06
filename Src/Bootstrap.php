<?php
/**
 * Created by (c)danidoble 2021.
 * @website https://github.com/danidoble
 * @website https://danidoble.com
 */

namespace Danidoble\Routing;

use Danidoble\Routing\Exceptions\ViewErrorCodeNotFoundException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Bootstrap
{
    /**
     * @var RouteCollection
     */
    protected $routes;
    /**
     * @var RequestContext
     */
    protected $context;
    /**
     * @var UrlMatcher
     */
    protected $matcher;
    /**
     * @var
     */
    public $accept, $uri, $url;
    /**
     * @var string
     */
    protected $error_404 = __DIR__ . "/views/404.html";
    /**
     * @var string
     */
    protected $error_405 = __DIR__ . "/views/405.html";

    /**
     *
     */
    public function __construct()
    {
        $this->routes = new RouteCollection();
        $this->context = new RequestContext();
        $this->matcher = new UrlMatcher($this->routes, $this->context);

        $dir_arr = explode('/', realpath($_SERVER['SCRIPT_FILENAME']));
        $dir = str_replace(DIRECTORY_SEPARATOR . $dir_arr[count($dir_arr) - 1], "", realpath($_SERVER['SCRIPT_FILENAME']));
        $dir = str_replace($_SERVER['DOCUMENT_ROOT'], "", $dir);
        $x_uri = explode("?", $_SERVER['REQUEST_URI']);
        $this->uri = str_replace($dir, '', str_replace($dir, '', $x_uri[0]));
        $this->url = env("APP_URL", "http://localhost");
    }

    /**
     *
     */
    protected function setParams()
    {
        $params = [
            "cookies" => $_COOKIE,
            "files" => $_FILES,
            "get" => $_GET,
            "post" => $_POST,
            "raw" => json_decode(file_get_contents("php://input"), true),
            "server" => $_SERVER,
        ];
        $this->context->setParameters($params);
    }

    /**
     *
     */
    protected function setConfig()
    {
        $this->context->setPathInfo($this->uri);
        $this->context->setBaseUrl($this->url);
        $this->context->setMethod($_SERVER['REQUEST_METHOD']);
        $this->context->setQueryString($_SERVER['QUERY_STRING']);
        $this->accept = mb_convert_case($_SERVER['HTTP_ACCEPT'], MB_CASE_LOWER, 'UTF-8');
        $this->setParams();
    }

    /**
     * @throws FileNotFoundException
     */
    protected function setViewError404($view)
    {
        $this->setViewErrors(404, $view);
    }

    /**
     * @return string
     */
    protected function getViewError404(): string
    {
        return $this->error_404;
    }

    /**
     * @throws FileNotFoundException
     */
    protected function setViewError405($view)
    {
        $this->setViewErrors(405, $view);
    }

    /**
     * @return string
     */
    protected function getViewError405(): string
    {
        return $this->error_405;
    }

    /**
     * @param $no
     * @param $view
     * @throws FileNotFoundException
     * @throws ViewErrorCodeNotFoundException
     * @internal
     */
    private function setViewErrors($no, $view)
    {
        if (!file_exists($view)) {
            throw new FileNotFoundException("View not found", $no);
        }
        switch ($no) {
            case 404:
                $this->error_404 = $view;
                break;
            case 405:
                $this->error_405 = $view;
                break;
            default:
                throw new ViewErrorCodeNotFoundException("Error code not found", $no);
        }
    }

    /**
     * @param $no
     */
    protected function showError($no)
    {
        switch ($no) {
            case 404:
                header("HTTP/1.0 404 Not Found");
                break;
            case 405:
                header("HTTP/1.0 405 Method Not allowed");
                break;
            default:
                throw new ViewErrorCodeNotFoundException("Error code not found", $no);
        }
        $this->showErrorJson($no);
        switch ($no) {
            case 404:
                include_once $this->getViewError404();
                break;
            case 405:
                include_once $this->getViewError405();
                break;
        }
    }

    /**
     * @param $no
     */
    protected function showErrorJson($no)
    {
        $json = false;
        if ($this->accept === "application/json") {
            header("Content-Type: application/json");
            $json = true;
        } elseif ($this->accept === "application/vnd.api+json") {
            header("Content-Type: application/vnd.api+json");
            $json = true;
        }
        if ($json) {
            $message = $no === 404 ? "Page not found" : "Method Not Allowed";
            echo json_encode([
                "error" => true,
                "errors" => [
                    "status" => $message
                ],
                "no" => $no
            ]);
            exit();
        }
    }


}