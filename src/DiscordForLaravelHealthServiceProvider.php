<?php

namespace DonKooijman\DiscordForLaravelHealth;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DiscordForLaravelHealthServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('discord-for-laravel-health')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        Notification::resolved(function (ChannelManager $manager) {
            $manager->extend('discord', fn ($app) => $app->make(DiscordWebhookChannel::class));
        });
    }
}
