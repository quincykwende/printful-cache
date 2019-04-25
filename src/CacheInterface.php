<?php
//Reference: https://gist.github.com/briedis/d14c4fd416bab8b8b8b873a8d677a0a6

//Defining the namespace
namespace PrintfulCache;

interface CacheInterface
{
    /**
     * Store a mixed type value in cache for a certain amount of seconds.
     * Allowed values are primitives and arrays.
     *
     * @param string $key
     * @param mixed $value
     * @param int $duration Duration in seconds
     * @return mixed
     */
    public function set(string $key, $value, int $duration);

    /**
     * Retrieve stored item.
     * Returns the same type as it was stored in.
     * Returns null if entry has expired.
     *
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key);
}