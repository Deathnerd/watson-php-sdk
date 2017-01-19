<?php
/**
 * Created by PhpStorm.
 * User: wes
 * Date: 1/18/17
 * Time: 1:48 PM
 */

namespace Core\Service;


use Core\Utils\CredentialUtils;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class WatsonService
{
    const URL = "url";
    const PATH_AUTORIZATION_V1_TOKEN = "/v1/token";
    const AUTHORIZATION = "authorization";
    const MESSAGE_ERROR = "error";
    const MESSAGE_ERROR_2 = "error_message";
    const MESSAGE_ERROR_3 = "message";
    const BASIC = "Basic";
    const MESSAGE_CODE = "code";
    const VERSION = "version";
    /**
     * @var string
     */
    private $api_key;
    /**
     * @var string
     */
    private $end_point;
    /**
     * @var string
     */
    private $name;
    /**
     * @var Client
     */
    private $client;
    /**
     * @var array
     */
    protected $default_headers;
    /**
     * @var bool
     */
    protected $skip_authentication = false;

    /**
     * Construct a new Watson service
     *
     * @param string $name the name of the service
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->api_key = CredentialUtils::getAPIKey($name);
        $this->client = $this->configureClient();
        $url = CredentialUtils::getAPIUrl($name);
        if ($url) {
            $this->setEndPoint($url);
        }
    }

    /**
     * Configures the client
     *
     * @param array $options Options to pass to the Guzzle Client to override see {@see the guzzle docs
     *                       http://docs.guzzlephp.org/en/latest/request-options.html} for more information
     *
     * @see http://docs.guzzlephp.org/en/latest/request-options.html
     *
     * @return Client The client
     */
    private function configureClient(array $options = [])
    {
        return new Client(array_merge([
            "timeout" => 60
        ], $options));
    }

    /**
     * Method to update the underlying client with new options. Mostly used for testing.
     *
     * @param array $options Options to pass to the Guzzle Client to override see {@see the guzzle docs
     *                       http://docs.guzzlephp.org/en/latest/request-options.html} for more information
     *
     * @see http://docs.guzzlephp.org/en/latest/request-options.html
     */
    public function updateClient(array $options = [])
    {
        $this->client = $this->configureClient($options);
    }

    /**
     * Sets the end point.
     *
     * @param string $end_point The new end point
     */
    private function setEndPoint($end_point)
    {
        if ($end_point) {
            $this->end_point = rtrim($end_point, "/");
        } else {
            $this->end_point = $end_point;
        }
    }

    /**
     * Sets the username and password
     *
     * @param string $username The username
     * @param string $password The password
     */
    public function setUsernameAndPassword($username, $password)
    {
        $this->api_key = CredentialUtils::basic($username, $password);
    }

    public function setDefaultHeaders(array $headers)
    {
        if (Arr::has($headers, "headers")) {
            $headers = $headers["headers"];
        }

        $this->default_headers = $headers;
    }

}