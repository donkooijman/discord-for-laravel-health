# Discord Notifications for Laravel Health

[![Latest Version on Packagist](https://img.shields.io/packagist/v/donkooijman/discord-for-laravel-health.svg?style=flat-square)](https://packagist.org/packages/donkooijman/discord-for-laravel-health)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/donkooijman/discord-for-laravel-health/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/donkooijman/discord-for-laravel-health/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/donkooijman/discord-for-laravel-health.svg?style=flat-square)](https://packagist.org/packages/donkooijman/discord-for-laravel-health)

Send [spatie/laravel-health](https://github.com/spatie/laravel-health) check failure notifications to a Discord channel via webhooks. Each failed check is formatted as a color-coded embed (green for OK, yellow for warning, red for failed/crashed).

## Requirements

This package requires [spatie/laravel-health](https://github.com/spatie/laravel-health) to be installed and configured in your application. If you haven't set it up yet, follow their [installation instructions](https://spatie.be/docs/laravel-health) first.

## Installation

Install the package via Composer:

```bash
composer require donkooijman/discord-for-laravel-health
```

## Configuration

You'll need a Discord webhook URL. You can learn how to create one in the [Discord API docs](https://support.discord.com/hc/en-us/articles/228383668-Intro-to-Webhooks).

Add your Discord webhook URL to your `.env` file:

```env
HEALTH_DISCORD_WEBHOOK_URL=https://discord.com/api/webhooks/your/webhook-url
```

Optionally, publish the config file:

```bash
php artisan vendor:publish --tag="discord-for-laravel-health-config"
```

Then configure `spatie/laravel-health` to use the package's notifiable and notification classes in `config/health.php`:

```php
'notifications' => [
    // ...

    'notifiable' => \DonKooijman\DiscordForLaravelHealth\Notifications\HealthNotifiable::class,

    'notifications' => [
        \DonKooijman\DiscordForLaravelHealth\Notifications\HealthCheckFailedNotification::class => ['discord'],
    ],
],
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
