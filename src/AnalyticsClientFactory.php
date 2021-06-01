<?php

namespace Spatie\Analytics;

use Google_Client;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;
use Spatie\Analytics\Exceptions\InvalidConfiguration;
use Symfony\Component\Cache\Adapter\Psr16Adapter;

class AnalyticsClientFactory
{
    public static function createForConfig(array $analyticsConfig): AnalyticsClient
    {
        $authenticatedClient = self::createAuthenticatedGoogleClient($analyticsConfig);

        return self::createAnalyticsClient($analyticsConfig, $authenticatedClient);
    }

    public static function createAuthenticatedGoogleClient(array $config): Google_Client
    {
        $client = new Google_Client();
        $client->setAccessType('offline');

        self::configureAuthentication($client, $config);
        self::configureCache($client, $config['cache']);

        return $client;
    }

    protected static function configureCache(Google_Client $client, $config): void
    {
        $config = collect($config);

        $store = Cache::store($config->get('store'));

        $cache = new Psr16Adapter($store);

        $client->setCache($cache);

        $client->setCacheConfig(
            $config->except('store')->toArray(),
        );
    }

    protected static function createAnalyticsClient(array $analyticsConfig, Google_Client $googleClient): AnalyticsClient
    {
        $client = new AnalyticsClient($googleClient, app(Repository::class));

        $client->setCacheLifeTimeInMinutes($analyticsConfig['cache_lifetime_in_minutes']);

        return $client;
    }

    private static function configureAuthentication(Google_Client $client, $config)
    {
        switch ($config['auth_type']):
            case 'oauth':
                $client->setClientId($config['connections']['oauth']['client_id']);
                $client->setClientSecret($config['connections']['oauth']['client_secret']);
                break;
            case 'oauth_json':
                $client->setAuthConfig($config['connections']['oauth_json']['auth_config']);
                break;
            case 'service_account':
                $client->useApplicationDefaultCredentials($config['connections']['service_account']['application_credentials']);
                break;
            default:
                throw InvalidConfiguration::oauthTypeNotSupported();
        endswitch;
    }
}
