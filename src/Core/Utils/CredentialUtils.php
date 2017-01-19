<?php
/**
 * Created by PhpStorm.
 * User: wes
 * Date: 1/18/17
 * Time: 6:45 PM
 */

namespace Core\Utils;


use Composer\Json\JsonValidationException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

final class CredentialUtils
{
    const ALCHEMY_API = "alchemy_api";
    const APIKEY = "apikey";
    const CREDENTIALS = "credentials";
    const PASSWORD = "password";
    const PLAN = "plan";
    const USERNAME = "username";
    const URL = "url";
    const PLAN_EXPERIMENTAL = "experimental";
    const PLAN_FREE = "free";
    const PLAN_STANDARD = "standard";
    /**
     * @var string
     */
    private static $services;

    /** No instantiation of the CredentialUtils, please. Thank you! */
    private function __construct()
    {
    }

    /**
     * Gets the `VCAP_SERVICES` environment variable and returns it as an array.
     * @return array The decoded `VCAP_SERVICES` environment JSON
     * @throws JsonValidationException If the `VCAP_SERVICES` variable could not be parsed as JSON
     * @throws \RuntimeException If the `VCAP_SERVICES` environment variable isn't in the current context
     */
    private static function getVCAPServices()
    {
        if (static::$services === null) {
            $env_services = getenv("VCAP_SERVICES");
        } else {
            $env_services = static::$services;
        }

        if (!$env_services) {
            throw new \RuntimeException("VCAP_SERVICES is not defined in the current environment context");
        }

        $env_services = json_decode($env_services);

        if ($env_services === null) {
            throw new JsonValidationException("Failed to decode VCAP_SERVICES. Is the JSON string malformed?");
        }

        return $env_services;
    }

    /**
     * Returns the apiKey from `VCAP_SERVICES` or `null` if it does not exist.
     *
     * @param string $service_name The service name
     * @param string $plan         The service plan: standard, free, or experimental
     *
     * @return string|null The API key or null if `VCAP_SERVICES` doesn't exist
     */
    public static function getAPIKey($service_name, $plan = "")
    {
        if (!$service_name) {
            return null;
        }

        try {
            $services = static::getVCAPServices();
        } catch (\RuntimeException $exception) {
            return null;
        }

        $is_alchemy_api = strcasecmp($service_name, static::ALCHEMY_API);

        if ($is_alchemy_api) {
            // Alchemy API key
            $credentials = static::getCredentialsObject($services, $service_name, $plan);
            if ($credentials && array_key_exists(static::APIKEY, $credentials)) {
                return $credentials[static::APIKEY];
            }
        } else {
            // Other API keys
            $credentials = static::getUserNameAndPassword($service_name, $plan);
            if ($credentials) {
                return "Basic " . base64_encode($credentials->getUsername() . ":" . $credentials->getPassword());
            }
        }

        return null;
    }

    /**
     * A helper method to retrieve the appropriate 'credentials' JSON property value from the `VCAP_SERVICES`
     *
     * @param array  $vcap_services Decoded JSON from the `VCAP_SERVICES` variable
     * @param string $service_name  The name of the service whose credentials are sought
     * @param string $plan          The name of the plan for which the credentials are sought, e.g. 'standard', 'beta',
     *                              etc. May be null
     *
     * @return array|null The first set of credentials that match the search criteria, service name, and plan. May
     *                    return null if the credentials do not exist for the `$service`
     */
    private static function getCredentialsObject(array $vcap_services, $service_name, $plan = "")
    {
        foreach ($vcap_services as $service => $value) {
            if (Str::startsWith($service, $service_name)) {
                if (array_key_exists(static::CREDENTIALS, $value)) {
                    $instance_plan = $value[static::PLAN];
                    if ($plan == null || strcasecmp($plan, $instance_plan)) {
                        return $value[static::CREDENTIALS];
                    }
                }
            }
        }

        return null;
    }

    /**
     * Returns the username and password as defined in the `VCAP_SERVICES` environment variable or null if they do not
     * exist or are not accessible. If a plan is provided, then only the credentials for that plan (and service) will
     * be returned. Null will be returned if the plan does not exist.
     *
     * @param string $service_name The name of the service whose credentials are sought
     * @param string $plan         The plan name
     *
     * @return ServiceCredentials|null An object representing the service credentials or null
     */
    private static function getUserNameAndPassword($service_name, $plan = "")
    {
        if (!$service_name) {
            return null;
        }

        try {
            $services = static::getVCAPServices();
        } catch (\RuntimeException $exception) {
            return null;
        }

        $credentials_object = static::getCredentialsObject($services, $service_name, $plan);
        if ($credentials_object) {
            $user_name = Arr::get($credentials_object, static::USERNAME);
            $password = Arr::get($credentials_object, static::PASSWORD);
            if ($user_name !== null || $password !== null) {
                // Both will be null for Alchemy API
                return new ServiceCredentials($user_name, $password);
            }
        }

        return null;
    }

    /**
     * Returns the URL for the given service or `null` if not found.
     *
     * @param string $service_name The service name
     * @param string $plan         THe service plan: standard, free, or experimental
     *
     * @return string|null The API URL or `null` if it wasn't found in the `VCAP_SERVICES`
     */
    public static function getAPIUrl($service_name, $plan = "")
    {
        if (!$service_name) {
            return null;
        }

        $services = self::getVCAPServices();
        if (!$services) {
            return null;
        }

        $credentials = self::getCredentialsObject($services, $service_name, $plan);

        return Arr::get($credentials, static::URL, null);
    }
}