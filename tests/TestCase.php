<?php

namespace DonKooijman\DiscordForLaravelHealth\Tests;

use DonKooijman\DiscordForLaravelHealth\DiscordForLaravelHealthServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Health\HealthServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            HealthServiceProvider::class,
            DiscordForLaravelHealthServiceProvider::class,
        ];
    }
}
