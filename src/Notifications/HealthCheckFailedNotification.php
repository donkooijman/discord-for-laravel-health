<?php

namespace DonKooijman\DiscordForLaravelHealth\Notifications;

use Spatie\Health\Enums\Status;
use Spatie\Health\Notifications\CheckFailedNotification;

class HealthCheckFailedNotification extends CheckFailedNotification
{
    /**
     * @return array{content: string, embeds: array<int, array<string, mixed>>}
     */
    public function toDiscord(mixed $notifiable): array
    {
        $embeds = [];

        foreach ($this->results as $result) {
            $embeds[] = [
                'title' => $result->check->getLabel(),
                'description' => $result->getNotificationMessage(),
                'color' => $this->discordColor(Status::from($result->status)),
            ];
        }

        return [
            'content' => trans('health::notifications.check_failed_slack_message', $this->transParameters()),
            'embeds' => $embeds,
        ];
    }

    private function discordColor(Status $status): int
    {
        return match ($status) {
            Status::ok() => 0x2EB67D,
            Status::warning() => 0xECB22E,
            Status::failed(),
            Status::crashed() => 0xE01E5A,
            default => 0x95A5A6,
        };
    }
}
