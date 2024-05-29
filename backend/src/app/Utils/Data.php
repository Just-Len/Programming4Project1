<?php

namespace App\Utils;

class Data
{
    const HASHING_ALGO = 'SHA256';
    public static function hash(string $data)
    {
        return hash(self::HASHING_ALGO, $data);
    }
}