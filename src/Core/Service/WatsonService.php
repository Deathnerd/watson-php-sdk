<?php
/**
 * Created by PhpStorm.
 * User: wes
 * Date: 1/18/17
 * Time: 1:48 PM
 */

namespace Core\Service;


class WatsonService
{
    const URL = "url";
    const PATH_AUTORIZATION_V1_TOKEN = "/v1/token";
    const AUTHORIZATION = "authorization";
    const MESSAGE_ERROR_3 = "message";
    const MESSAGE_ERROR_2 = "error_message";
    const BASIC = "Basic";

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
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * Construct a new Watson service
     * @param string $name the name of the service
     */
    public function __construct($name)
    {
        $this->name = $name;

    }


}