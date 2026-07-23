<?php

return [
    'datetime_format' => 'm/d/Y H:i:s',
    'date_format' => 'm/d/Y',

    'activity_resource' => \Taba\Crm\Filament\Admin\Resources\ActivityResource::class,

    'resources' => [
        'enabled' => true,
        'log_name' => 'Resource',
        'logger' => \Jacobtims\FilamentLogger\Loggers\ResourceLogger::class,
        'color' => 'success',
        'exclude' => [
            // Never log the activity log itself: doing so makes creating an
            // activity create another activity, copying its `properties` each
            // time and doubling the row size until the DB OOMs. See RecentActivities widget.
            \Taba\Crm\Filament\Admin\Resources\ActivityResource::class,
            BezhanSalleh\FilamentExceptions\Resources\ExceptionResource::class,
            Croustibat\FilamentJobsMonitor\Resources\QueueMonitorResource::class,
        ],
    ],

    'access' => [
        'enabled' => true,
        'logger' => \Jacobtims\FilamentLogger\Loggers\AccessLogger::class,
        'color' => 'danger',
        'log_name' => 'Access',
    ],

    'notifications' => [
        'enabled' => true,
        'logger' => \Jacobtims\FilamentLogger\Loggers\NotificationLogger::class,
        'color' => null,
        'log_name' => 'Notification',
    ],

    'models' => [
        'enabled' => true,
        'log_name' => 'Model',
        'color' => 'warning',
        'logger' => \Jacobtims\FilamentLogger\Loggers\ModelLogger::class,
        'register' => [
            \Taba\Crm\Models\User::class,
        ],
    ],

    'custom' => [
        // [
        //     'log_name' => 'Custom',
        //     'color' => 'primary',
        // ]
    ],
];
