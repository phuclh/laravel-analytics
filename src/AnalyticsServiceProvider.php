<?php

namespace Spatie\Analytics;

use Spatie\Analytics\Exceptions\InvalidConfiguration;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class AnalyticsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-analytics')
            ->hasConfigFile('google-analytics');
    }

    public function registeringPackage(): void
    {
        $this->app->bind(AnalyticsClient::class, function () {
            $analyticsConfig = config('google-analytics');

            return AnalyticsClientFactory::createForConfig($analyticsConfig);
        });

        $this->app->bind(Analytics::class, function () {
            $analyticsConfig = config('google-analytics');

            $this->guardAgainstInvalidConfiguration($analyticsConfig);

            $client = app(AnalyticsClient::class);

            return new Analytics($client, $analyticsConfig['view_id']);
        });

        $this->app->alias(Analytics::class, 'laravel-analytics');
    }

    protected function guardAgainstInvalidConfiguration(array $analyticsConfig = null): void
    {
        if ($analyticsConfig['auth_type'] == 'service_account' && !file_exists($analyticsConfig['connections']['service_account']['application_credentials'])) {
            throw InvalidConfiguration::credentialsJsonDoesNotExist($analyticsConfig['connections']['service_account']['application_credentials']);
        }

        if ($analyticsConfig['auth_type'] == 'oauth_json' && !file_exists($analyticsConfig['connections']['oauth_json']['auth_config'])) {
            throw InvalidConfiguration::credentialsJsonDoesNotExist($analyticsConfig['connections']['oauth_json']['auth_config']);
        }
    }
}
