<?php
/**
 * Created by (c)danidoble 2022.
 * @website https://github.com/danidoble
 * @website https://danidoble.com
 */

namespace Danidoble\Routing;

class Testing extends \Danidoble\Routing\Controller
{
    public function index()
    {
        print_r("your page");
//        dump($this->getParent());
//        dump($this->param('demo'));
    }

    public function help()
    {
        print_r("help page");
    }

    public function danidoble()
    {
        print_r("Created by danidoble");
    }
}