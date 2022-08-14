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
        $data = 'data from php controller';
        dView('index', [
            'data' => $data,
        ]);
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