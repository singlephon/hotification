<?php

use Singlephon\Hotification\Hotification;

return [
    'extras' => [
        /**
         * ----------------------------------------------------------------------------------
         * Must extend \Illuminate\Notifications\Notification::class
         * Or
         * \Singlephon\Hotification\Notifications\HotNotification::class
         */
        'notification_default' => \Singlephon\Hotification\Notifications\HotNotification::class,

        /**
         * ----------------------------------------------------------------------------------
         * Must implement \Singlephon\Hotification\Interfaces\HotificationSenderInterface
         * Or
         * Extends \Singlephon\Hotification\Extras\HotificationSender::class
         */
        'hotification_sender' => \Singlephon\Hotification\Extras\HotificationSender::class,
    ],

    'models' => [
//        \App\Models\Post::class => [
//            'onCreated' => [
//                \App\Notifications\PostCreatedNotification::class,
//                function ($model) {
//                    // ...
//                },
//            ],
//            'onUpdated' => [\App\Notifications\PostUpdatedNotification::class],
//        ],
//        \App\Models\User::class => [
//            'onCreated' => [
//                function ($model) {
//                    // Callback
//                },
//            ],
//        ],
    ],
    'scheduled_notifications' => [
//        'weekly_report' => function (Schedule $schedule) {
//            // Настроим расписание для еженедельного отчета
//            $schedule->call(function () {
//                // Логика выполнения
//            })->weekly();
//        },
//        'daily_report' => [
//            'events' => [
//                \App\Notifications\DailyReportNotification::class,
//                function () {
//                    // Логика выполнения
//                },
//            ],
//            'schedule' => '0 0 * * *', // Каждый день в полночь
//        ],
    ],
];
