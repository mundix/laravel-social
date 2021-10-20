<?php


namespace App\Interfaces;


interface Causes
{
    public const OPTIONS = [
        'local' => 'Local',
        'regional' => 'Regional',
        'national' => 'National',
        'global' => 'Global',
    ];

    public static function getCauses();
}