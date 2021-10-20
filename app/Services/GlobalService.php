<?php


namespace App\Services;


class GlobalService
{
    public static function generateToken($length = 50)
    {
        return str_replace("/", "", \Hash::make(\Str::random($length)));
    }
}