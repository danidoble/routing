<?php
/**
 * Created by (c)danidoble 2021.
 * @website https://github.com/danidoble
 * @website https://danidoble.com
 */

namespace Danidoble\Routing;

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Testing extends Controller
{
    public function index()
    {
        print_r("Created by danidoble");
        //dump($this->getParent());
        //dump($this->param('demo'));
        //dd($this->getParams());
    }
}