<?php
namespace App\Helpers;

use Illuminate\Contracts\Support\Arrayable;

class StdObject implements Arrayable {

    public function __construct(array $arguments = [])
    {
        if (!empty($arguments)) {
            foreach ($arguments as $property => $argument) {
                $this->{$property} = $argument;
            }
        }
    }

    public function __call($method, $arguments)
    {
        if (isset($this->{$method}) && is_callable($this->{$method})) {
            return call_user_func_array($this->{$method}, $arguments);
        } else {
            throw new \Exception("Fatal error: Call to undefined method StdObject::{$method}()");
        }
    }

    /**
     * Get the instance as an array.
     *
     * @return array<TKey, TValue>
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}
