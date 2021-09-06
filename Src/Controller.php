<?php
/**
 * Created by (c)danidoble 2021.
 * @website https://github.com/danidoble
 * @website https://danidoble.com
 */

namespace Danidoble\Routing;
use Danidoble\Routing\Interfaces\IController;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RouteCollection;

class Controller implements IController
{
    /**
     * @var RequestContext
     */
    protected $_context;
    /**
     * @var Route
     */
    protected $_parent;
    /**
     * @var RouteCollection
     */
    protected $_routes;
    /**
     * @var UrlMatcher
     */
    protected $_matcher;
    /**
     * @var
     */
    protected $params;

    /**
     * @param RequestContext $_context
     * @param RouteCollection $_routes
     * @param UrlMatcher $_matcher
     * @param Route $_parent
     */
    public function __construct(RequestContext $_context, RouteCollection $_routes,UrlMatcher $_matcher,Route $_parent)
    {
        $this->_context=$_context;
        $this->_routes=$_routes;
        $this->_matcher=$_matcher;
        $this->_parent=$_parent;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->_context->getParameters();
    }

    /**
     * @return Route
     */
    public function getParent(): Route
    {
        return $this->_parent;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function param($name){
        $method = mb_convert_case($this->_context->getMethod(),MB_CASE_LOWER,"UTF-8");
        if($method === "get"){
            $global = $this->_context->getParameter('get');
        }
        elseif($method === "post"){
            $global = $this->_context->getParameter('post');
        }
        else{
            $global = $this->_context->getParameter('raw');
        }
        if(!empty($global) && isset($global[$name])){
            return $global[$name];
        }
        return null;
    }
}