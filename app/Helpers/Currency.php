<?php


namespace App\Helpers;


class Currency
{
    /**
     * @param $n
     * @return string
     * Use to convert large positive numbers in to short form like 1K+, 100K+, 199K+, 1M+, 10M+, 1B+ etc
     */
    public static function number_format_short( $n ) {

        if($n < 1) {
            return 0;
        }

        if ($n > 0 && $n < 1000) {
            // 1 - 999
            $n_format = ($n);
            $suffix = '';
            return !empty($n_format . $suffix) ?  $n_format  . $suffix : 0;
        } else if ($n >= 1000 && $n < 1000000) {
            // 1k-999k
            $n_format = ($n / 1000);
            $suffix = 'K';
        } else if ($n >= 1000000 && $n < 1000000000) {
            // 1m-999m
            $n_format = ($n / 1000000);
            $suffix = 'M';
        } else if ($n >= 1000000000 && $n < 1000000000000) {
            // 1b-999b
            $n_format = ($n / 1000000000);
            $suffix = 'B';
        } else if ($n >= 1000000000000) {
            // 1t+
            $n_format = ($n / 1000000000000);
            $suffix = 'T';
        }

        return !empty($n_format . $suffix) ?  number_format($n_format, 1)  . $suffix : 0;
    }
}