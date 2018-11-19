<?php

namespace Brute;

use \Brute\Attempt;

class Block extends BruteBase
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
        $key = $this->filterKey($key);
        $check = $this->cache()->has($key);

        if (!$check) {
            return true;
        }

        app()->make('error')->code(2050);
    }


    /**
     * Attempt to Block a Key
     *
     * @param $key
     */
    public function attempt($key, $maxAttempts='')
    {
        $attempts = (new Attempt)
            ->reset($this->prefix, $this->item)
            ->add($key);
        $maxAttempts = $maxAttempts ?: config('prionbrute.attempts');

        if ($attempts >= $maxAttempts) {
            return $this->block($key);
        }

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