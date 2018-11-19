<?php

namespace Brute;

use \Brute\Block;

class Attempt extends BruteBase
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
        $attempts = $this->cache()->get($key) ?: 0;
        $attempts++;

        $max_attempts = $maxAttempts ?: config('prionbrute.attempts');
        if ($attempts > $max_attempts) {
            $attempts = $max_attempts;
        }

        $expire = (int) config('prionbrute.block_ttl');
        $this->cache()->set($key, $attempts, $expire);

        return $attempts;
    }

}