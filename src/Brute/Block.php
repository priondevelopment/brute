<?php

namespace Brute;

use Attempt;

class Block implements Brute\Contacts\BruteInterface
{

    public $type = 'brute_block:';


    /**
     * Check if the key is blocked
     *
     * @param $key
     * @return bool
     */
    public function check($key)
    {
        $key = $this->type . $key;
        $key = $this->key($key);
        $key = $this->keyFilter($key);

        $check = $this->cache()->get($key);

        if ($check >= 1)
            return true;

        return false;
    }


    /**
     * Attempt to Block a Key
     *
     * @param $key
     */
    public function set($key, $maxAttempts='')
    {
        $attempts = (new Attempt)->get($key);
        $maxAttempts = $maxAttempts ?: config('prionbrute.block.attempts');

        if ($attempts >= $maxAttempts)
            return $this->block($key);

        return false;
    }


    /**
     * Block a Key
     *
     * @param $key
     * @return bool
     */
    protected function block($key)
    {
        $expire = (int) config('prionbrute.block_ttl');
        $key = $this->filterKey($key);
        $this->cache()->set($key, 1, $expire);

        return true;
    }


    /**
     * Unblock a Key
     *
     * @param $key
     */
    public function remove($key)
    {
        $attempt = $this->key($this->attempt . $key);
        $block = $this->key($this->block . $key);

        $this->cache()->forget($attempt);
        $this->cache()->forget($block);
    }



}