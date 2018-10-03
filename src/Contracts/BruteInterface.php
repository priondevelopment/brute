<?php

namespace Brute\Contracts;

/**
 * This file is part of Setting,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @company Prion Development
 * @package Setting
 */

interface Brute
{

    public $type = '_brute_attempt_';

    private $block = 'brute_block:';
    private $attempt = 'brute_attempt:';

    public $cache;

    public function __construct()
    {

    }

    protected function cache()
    {
        $tag = config('prionbrute.cache.tag');
        $this->cache = app()->make('cache')
            ->tags($tag);
    }


    /**
     *  Filter a Key
     *
     */
    protected function key ($key) {

        $key = str_replace("__", "_", $key);
        return $key;

    }


    /**
     * Check if a Key Exists
     *
     * @param $key
     * @return mixed
     */
    protected function exists ($key)
    {

        if ($this->cache->has($key))
            return true;

        return false;

    }


    /**
     * Make sure we use the correct key
     *
     * @param $key
     * @return mixed
     */
    protected function filterKey($key)
    {
        $key = str_replace([$this->block, $this->attempt], $this->type, $key);
        return $key;
    }


}