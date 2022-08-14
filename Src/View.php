<?php
/**
 * Created by (c)danidoble 2022.
 * @website https://github.com/danidoble
 * @website https://danidoble.com
 */

namespace Danidoble\Routing;

use Closure;
use Illuminate\Support\Collection;

class View
{
    protected Collection $collection;
    protected SharedData $shared_data;
    public static string $BASE_VIEW_PATH = '';

    public function __construct()
    {
        $this->shared_data = new SharedData();
    }

    /**
     * @param string $view
     * @return void
     */
    public function render(string $view): void
    {
        require $view;
    }

    /**
     * @param $values
     * @return void
     */
    public function with($values): void
    {
        $this->collection = new Collection($values);
        foreach ($this->collection->toArray() as $key => $value) {
            $this->shared_data->{$key} = $value;
        }
    }

    /**
     * @param string|null $name
     * @return Closure|SharedData|mixed|null
     */
    public function getData(?string $name = null): mixed
    {
        if (!blank($name)) {
            return $this->get($name);
        }

        return $this->shared_data;
    }

    /**
     * @param string $name
     * @return Closure|mixed|null
     */
    public function get(string $name): mixed
    {
        return $this->collection->get($name);
    }

    /**
     * @deprecated this method is deprecated use getData
     * @return Closure|SharedData|mixed|null
     */
    public function sharedData(): mixed
    {
        return $this->getData();
    }
}