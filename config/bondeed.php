<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Globally Configs
    |--------------------------------------------------------------------------
    |
    */
    'frontend' => [
        'dashboards' => [
            'limit' => 5,
            'limit-10' => 10,
        ]
    ],
    'quiz_url' => env('QUIZ_URL'),
    'uploads' => [
        'limits' => [
            'size' => 1024*10
        ]
    ]
];