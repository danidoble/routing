<?php
/**
 * Created by (c)danidoble 2021.
 * @website https://github.com/danidoble
 * @website https://danidoble.com
 */

namespace Danidoble\Routing\Interfaces;

use Danidoble\Routing\Route;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

interface IController
{
    /**
     * @param RequestContext $_context
     * @param RouteCollection $_routes
     * @param UrlMatcher $_matcher
     * @param Route $_parent
     */
    public function __construct(RequestContext $_context, RouteCollection $_routes,UrlMatcher $_matcher,Route $_parent);

    /**
     * @return array
     */
    public function getParams(): array;

    /**
     * @return Route
     */
    public function getParent(): Route;

    /**
     * @param $name
     * @return mixed|null
     */
    public function param($name);
}