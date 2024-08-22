<?php

return [
    'channels' => [
        'hotification' => [
            'driver' => 'single',
            'path' => storage_path('logs/hotification.log'),
            'level' => 'info',
            'days' => 7,
        ],
    ],
];
