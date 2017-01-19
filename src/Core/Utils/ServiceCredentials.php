<?php
/**
 * Created by PhpStorm.
 * User: wes
 * Date: 1/18/17
 * Time: 6:41 PM
 */

namespace Core\Utils;

/**
 * Easily store service credentials
 * @package Core\Utils
 */
class ServiceCredentials
{
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $username;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
}