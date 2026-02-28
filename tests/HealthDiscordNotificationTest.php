<?php

use DonKooijman\DiscordForLaravelHealth\DiscordWebhookChannel;
use DonKooijman\DiscordForLaravelHealth\Notifications\HealthCheckFailedNotification;
use DonKooijman\DiscordForLaravelHealth\Notifications\HealthNotifiable;
use Illuminate\Support\Facades\Http;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Result;

it('formats failed check results as discord embeds', function () {
    $result = Result::make()
        ->check(DebugModeCheck::new()->label('Debug Mode'))
        ->failed('Debug mode is enabled');

    $notification = new HealthCheckFailedNotification([$result]);
    $message = $notification->toDiscord(new HealthNotifiable);

    expect($message)
        ->toHaveKeys(['content', 'embeds'])
        ->and($message['embeds'])->toHaveCount(1)
        ->and($message['embeds'][0])
        ->toMatchArray([
            'title' => 'Debug Mode',
            'description' => 'Debug mode is enabled',
            'color' => 0xE01E5A,
        ]);
});

it('uses correct colors for each status', function (string $statusMethod, int $expectedColor) {
    $result = Result::make()
        ->check(DebugModeCheck::new())
        ->{$statusMethod}('Test message');

    $notification = new HealthCheckFailedNotification([$result]);
    $message = $notification->toDiscord(new HealthNotifiable);

    expect($message['embeds'][0]['color'])->toBe($expectedColor);
})->with([
    'ok' => ['ok', 0x2EB67D],
    'warning' => ['warning', 0xECB22E],
    'failed' => ['failed', 0xE01E5A],
]);

it('includes multiple check results as separate embeds', function () {
    $results = [
        Result::make()->check(DebugModeCheck::new()->label('Check A'))->failed('A failed'),
        Result::make()->check(DebugModeCheck::new()->label('Check B'))->warning('B warning'),
    ];

    $notification = new HealthCheckFailedNotification($results);
    $message = $notification->toDiscord(new HealthNotifiable);

    expect($message['embeds'])->toHaveCount(2)
        ->and($message['embeds'][0]['title'])->toBe('Check A')
        ->and($message['embeds'][1]['title'])->toBe('Check B');
});

it('posts to the configured discord webhook url', function () {
    Http::fake(['https://discord.com/api/webhooks/*' => Http::response([], 204)]);

    config(['discord-for-laravel-health.webhook_url' => 'https://discord.com/api/webhooks/test/token']);

    $result = Result::make()
        ->check(DebugModeCheck::new())
        ->failed('Something broke');

    $notification = new HealthCheckFailedNotification([$result]);
    $notifiable = new HealthNotifiable;

    $channel = app(DiscordWebhookChannel::class);
    $channel->send($notifiable, $notification);

    Http::assertSent(fn ($request) => $request->url() === 'https://discord.com/api/webhooks/test/token'
        && isset($request->data()['content'])
        && isset($request->data()['embeds'])
    );
});

it('does not send when webhook url is empty', function () {
    Http::fake();

    config(['discord-for-laravel-health.webhook_url' => '']);

    $result = Result::make()
        ->check(DebugModeCheck::new())
        ->failed('Something broke');

    $notification = new HealthCheckFailedNotification([$result]);
    $channel = app(DiscordWebhookChannel::class);
    $response = $channel->send(new HealthNotifiable, $notification);

    expect($response)->toBeNull();
    Http::assertNothingSent();
});

it('routes discord notifications to the configured webhook url', function () {
    config(['discord-for-laravel-health.webhook_url' => 'https://discord.com/api/webhooks/123/abc']);

    $notifiable = new HealthNotifiable;

    expect($notifiable->routeNotificationFor('discord'))->toBe('https://discord.com/api/webhooks/123/abc');
});
