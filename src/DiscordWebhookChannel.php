<?php

namespace DonKooijman\DiscordForLaravelHealth;

use Illuminate\Http\Client\Response;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class DiscordWebhookChannel
{
    public function send(mixed $notifiable, Notification $notification): ?Response
    {
        /** @var string $webhookUrl */
        $webhookUrl = $notifiable->routeNotificationFor('discord');

        if (blank($webhookUrl)) {
            return null;
        }

        /** @var array{content?: string, embeds?: array<int, mixed>} $message */
        $message = $notification->toDiscord($notifiable); // @phpstan-ignore method.notFound

        return Http::post($webhookUrl, $message);
    }
}
