<?php


namespace App\Contracts;


interface UiNotificationSystem
{
    public function success(array $options = [], $type = '');
    public function warning(array $options = [], $type = '');
    public function error(array $options = [], $type = '');
    public function info(array $options = [], $type = '');
    public function confirm(array $options = [], $type = '');
}
