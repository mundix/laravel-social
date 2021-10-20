<?php


namespace App\Helpers;


use App\Interfaces\Causes;

class Cause implements Causes
{
    /**
     * Get cause location type
     * @param  $slug
     *
     * @return
    */
    public static function getCauses( $slug = 'local')
    {
        return self::OPTIONS[$slug];
    }

    public static function getOptions()
    {
        return self::OPTIONS;
    }
}