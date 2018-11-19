<?php

namespace Brute;

/**
 * This file is part of Setting,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @company Prion Development
 * @package Setting
 */

class BruteBase
{

    public $type = '_brute_attempt_';

    private $block = 'brute_block:';
    private $attempt = 'brute_attempt:';

    public $cache;

    public $prefix = '';
    public $item = '';

    protected function cache()
    {
        $tag = config('prionbrute.cache.tag');
        $this->cache = app()->make('cache')
            ->tags($tag);

        return $this->cache;
    }


    /**
     * Check if a Key Exists
     *
     * @param $key
     * @return mixed
     */
    protected function exists($key)
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
        $find = [
            $this->block,
            $this->attempt
        ];

        $key = str_replace($find, $this->type, $key);
        $key = str_replace("::::", "::", $key);
        $key = $this->type . $key;
        $key = $this->item . $key;
        $key = $this->prefix . $key;

        return $key;
    }


    /**
     * Set the Prefix
     *
     * @param $prefix
     * @return mixed
     */
    public function prefix($prefix)
    {
        $this->prefix = $prefix . "::";
        $this->prefix = str_replace("::::", "::", $this->prefix);
        return $this;
    }


    /**
     * Set the Item
     *
     * @param $app
     * @return mixed
     */
    public function item($item)
    {
        $this->item = $item . "::";
        $this->item = str_replace("::::", "::", $this->item);
        return $this;
    }


    /**
     * Reset the Brute Prefix and Item
     *
     * @param $prefix
     * @param $item
     * @return $this
     */
    public function reset($prefix, $item)
    {
        $prefix = $this->prefix($prefix);
        $item = $this->item($item);

        return $this;
    }


    public function key($key)
    {
        $key = $this->filterKey($key);
        return $key;
    }

}