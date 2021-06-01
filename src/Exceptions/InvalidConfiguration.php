<?php

namespace Spatie\Analytics\Exceptions;

use Exception;

class InvalidConfiguration extends Exception
{
    public static function viewIdNotSpecified(): static
    {
        return new static('There was no view ID specified. You must provide a valid view ID to execute queries on Google Analytics.');
    }

    public static function credentialsJsonDoesNotExist(string $path): static
    {
        return new static("Could not find a credentials file at `{$path}`.");
    }

    public static function oauthTypeNotSupported(): static
    {
        $supportedOauthTypes = array_keys(config('google-analytics.connections'));

        return new static('Oauth type is not supported. Supported Oauth types are: ' . implode(', ', $supportedOauthTypes));
    }
}
