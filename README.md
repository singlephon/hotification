# Hotification
[![Latest Version on Packagist](https://img.shields.io/packagist/v/singlephon/hotification.svg?style=flat-square)](https://packagist.org/packages/singlephon/hotification)
[![Total Downloads](https://img.shields.io/packagist/dt/singlephon/hotification.svg?style=flat-square)](https://packagist.org/packages/singlephon/hotification)

`Hotification` is a powerful and flexible package for managing notifications in Laravel applications. It provides a convenient API for sending notifications, adding observers, and handling events.

### Key Features
- Easy configuration through a single file: `config/hotification.php`.
- Extensibility with custom sender and observer classes.
- Instant sending of notifications to the database.
- Dynamic addition of observers for models.
- Execute any logic upon model changes.

## Installation

1. Install via Composer:

```bash
composer require singlephon/hotification
```

```bash
php artisan hotification:install
```

2. Register the service provider, alias:

Add the provider, alias to the `config/app.php`:

```php
'providers' => [
    # ...
    Singlephon\Hotification\HotificationServiceProvider::class,
],
'aliases' => [
    # ...
    'Hotification' => \Singlephon\Hotification\Hotification::class,
]
```

## Quick Start

### Sending Notifications

Use the `HotificationSender` class for instant sending of notifications to the database:

```php
use App\Models\User;

(new Hotification())
    ->notify(User::find(1))
    ->icon('exclamation-triangle')
    ->title('Important notification...')
    ->description('Some description...')
    ->url('/app/friends/invitations')
    ->actionable()
    ->send();
```

This will add a record to the `notifications` table.

### Automating Notification Sending

In `Hotification`, you can use model observers to automatically perform actions on specific events, such as creating, updating, or deleting a model instance. This allows for easy integration of notifications and callbacks into standard model workflows, providing a convenient way to manage application behavior.

Key settings in `config/hotification.php`:

```php
'models' => [
    # Models for which observers need to be registered
],
'scheduled_notifications' => [
    # Settings for scheduled notifications
]

```

### Using Observers for Models

The example below shows how to configure observers for the `Post` and `User` models using hooks like `onCreated`, `onUpdated`, and `onDeleted`:

```php
'models' => [

    \App\Models\Post::class => [
        # Send a notification when a new post is created
        'onCreated' => [
            \App\Notifications\PostCreatedNotification::class, # Should extend Singlephon\Hotification\Notifications\AbstractHotification
            function (Post $post) {
                # Callback to execute custom logic when a post is created
                # For example, sending a Push notification, logging, or modifying other properties
            },
        ],
        # Send a notification when a post is updated
        'onUpdated' => [
            \App\Notifications\PostUpdatedNotification::class,
        ]
    ],

    \App\Models\User::class => [
        'onCreated' => [
            function (User $user) {
                # Callback for executing logic when a new user is created
                # For example, sending a welcome message or logging activity
                (new Hotification())
                    ->notify($user)
                    ->icon('check-badge')
                    ->title('Welcome ' . $user->name)
                    ->description('Please continue registration form...')
                    ->url('/app/registration/continue')
                    ->actionable()
                    ->send();
            },
        ],
        # Send a notification when a user is deleted
        'onDeleted' => [
            \App\Notifications\ByeByeNotification::class,
            function (User $user) {
                # Callback for executing logic when a user is deleted
                # For example, sending a notification to the administrator
            }
        ]
    ]

]

```

⚠️ Note: Notification classes must extend the `Singlephon\Hotification\Notifications\AbstractHotification` class.

### Advantages of Using Observers

- **Centralized Logic**: Allows all logic related to model events to be concentrated in one place.
- **Easy Management**: Easily add or change behavior on events by simply updating observer configuration.
- **Extensibility**: Use both built-in notifications and custom callbacks, providing flexibility in event handling.

### Recommendations

- **Use hooks only when necessary** to avoid unnecessary complexity in logic.
- **Avoid overloading a single hook** with too many actions to maintain performance and ease of debugging.
- For complex logic, consider moving it into separate services or classes to maintain clean and readable code.

### Scheduled Notifications

The `scheduled_notifications` section in `config/hotification.php` allows you to define tasks that should be performed on a schedule. These tasks can include sending notifications and executing custom logic using callbacks. This functionality provides flexibility and convenience in managing scheduled tasks, simplifying the automation of routine operations.

```php
'scheduled_notifications' => [
    'weekly_report' => function (Schedule $schedule) {
        $schedule->call(function () {
            (new Hotification())
                ->notify(User::all())
                ->icon('check-badge')
                ->title('Welcome ' . $user->name)
                ->description('Please continue registration form...')
                ->url('/app/registration/continue')
                ->actionable()
                ->send();
        })->everyWeek(); # Runs every week
    },
],
'daily_report' => [
    'events' => [
        \App\Notifications\DailyReportNotification::class, # Sending a notification
        function () {
            # Logic for executing a custom action
            # For example, generating a report and sending it via email
        },
    ],
    'schedule' => '0 0 * * *', # Every day at midnight
],

```

⚠️ Note: Notification classes must extend the `Singlephon\Hotification\Notifications\AbstractHotification` class.

### Advantages of Using `scheduled_notifications`

- **Flexibility**: Combine notifications and custom callbacks in a single task.
- **Convenience**: Easily add and modify tasks using configuration files.
- **Automation**: Automatically perform routine tasks like sending reports or performing regular updates.

### Recommendations

- **Test your tasks**: Ensure schedules are set correctly and tasks run at the intended times.
- **Avoid overly frequent tasks**: Schedule tasks at reasonable intervals to avoid overloading the system.
- **Use callbacks for complex logic**: If a task requires complex processing, move that logic into callbacks or separate services.

### Dynamic Addition of Observers at Runtime

One of the key aspects of using Hotification is the ability to dynamically add observers (handlers) to models directly at runtime, not only through configuration files. This provides high flexibility and allows for programmatic management of notification behavior depending on context or business logic.

### Example Code

Consider the following example, which dynamically adds an observer for the `User` model:

```php
(new Hotification())
    ->manager()
    ->add(User::class,
        onUpdated: [function (User $user) {}],
        onCreated: [function (User $user) {}],
        onDeleted: [function (User $user) {}]
    );

```

The documentation also available in [russian language](README_RU.md)

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email singlephon@gmail.com instead of using the issue tracker.

## Credits

-   [Rakhat Bakytzhanov](https://github.com/singlephon)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
