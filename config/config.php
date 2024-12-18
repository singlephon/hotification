<?php

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

    'models' => \App\Hotification\Models::class,
    'scheduled_notifications' => \App\Hotification\Schedules::class,
];
