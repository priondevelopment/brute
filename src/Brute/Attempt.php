<?php

namespace Brute;

use Block;

class Attempt implements Brute\Contacts\BruteInterface
{

    public $type = 'brute_attempt:';


    /**
     * Remove an Attempt
     *
     * @param $key
     */
    public function remove($key)
    {
        $key = $this->filterKey($key);
        $value = $this->cache()->get($key);

        $value--;
        $expire = (int) config('prionbrute.block_ttl');
        $this->cache()->set($key, $value, $expire);
    }


    /**
     * Remove All Attempts
     *
     * @param $key
     */
    public function removeAll($key)
    {
        $key = $this->filterKey($key);
        $this->cache()->forget($key);
    }


    /**
     * Add An Attempt
     *
     * @param $key
     * @return Response if Key is Blocked
     */
    public function add($key, $maxAttempts='')
    {
        $key = $this->filterKey($key);
        $value = $this->cache()->get($key) ?: 0;

        $value++;

        $max_attempts = $maxAttempts ?: config('prionbrute.block.attempts');
        if ($value > $max_attempts)
            $value = $max_attempts;

        $expire = (int) config('prionbrute.block_ttl');
        $this->cache()->set($key, $value, $expire);
        
        return (new Block)->set($key, $maxAttempts);
    }

}