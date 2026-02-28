<?php

namespace DonKooijman\DiscordForLaravelHealth\Notifications;

use Spatie\Health\Notifications\Notifiable;

class HealthNotifiable extends Notifiable
{
    public function routeNotificationForDiscord(): string
    {
        return config('discord-for-laravel-health.webhook_url');
    }
}
