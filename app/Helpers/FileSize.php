<?php


namespace App\Helpers;


class FileSize
{
    public static function mbToBytes($units)
    {
        return $units * pow(1024, 2);
    }
}
