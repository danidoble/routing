<?php
/**
 * Created by (c)danidoble 2022.
 * @website https://github.com/danidoble
 * @website https://danidoble.com
 */

namespace Danidoble\Routing;

class SharedData
{
    /**
     * @param string $name
     * @param $value
     * @return void
     */
    public function __set(string $name, $value): void
    {
        $this->{$name} = $value;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->{$name};
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        if (property_exists($this, $name)) {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJSON();
    }

    /**
     * @return string
     */
    public function toJSON(): string
    {
        return json_encode($this);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $arr = json_decode($this->toJSON(), true);
        if (empty($arr)) {
            return [];
        }
        return $arr;
    }
}