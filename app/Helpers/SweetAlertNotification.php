<?php


namespace App\Helpers;

use App\Contracts\UiNotificationSystem;

class SweetAlertNotification implements UiNotificationSystem
{
    public $context;
    private $notificationOptions = [
        'type'  => 'success',
        'title' => 'Success!!',
        'icon' => 'success',
        'confirmButtonText' => 'Delete',
        'text'  => "",
    ];

    public function __construct($context)
    {
        $this->context = $context;
    }

    public function success(array $options = [], $type = 'alert')
    {
       $this->context->emit("swal:$type", array_merge($this->notificationOptions, $options));
    }

    public function warning(array $options = [], $type = 'alert')
    {
        $this->notificationOptions['type'] = 'warning';
        $this->notificationOptions['icon'] = 'warning';
        $this->context->emit($type, array_merge($this->notificationOptions, $options));
    }

    public function error(array $options = [], $type = 'alert')
    {
        $this->notificationOptions['type'] = 'error';
        $this->notificationOptions['icon'] = 'error';
        $this->context->emit("swal:$type", array_merge($this->notificationOptions, $options));
    }

    public function info(array $options = [], $type = 'alert')
    {
        $this->notificationOptions['type'] = 'info';
        $this->notificationOptions['icon'] = 'info';
        $this->context->emit("swal:$type", array_merge($this->notificationOptions, $options));
    }

    public function confirm(array $options = [], $type = 'confirm')
    {
        $this->notificationOptions['type'] = 'warning';
        $this->notificationOptions['icon'] = 'warning';
        $this->context->emit("swal:$type", array_merge($this->notificationOptions, $options));
    }
}
