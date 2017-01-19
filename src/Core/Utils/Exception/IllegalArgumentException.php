<?php
/**
 * Created by PhpStorm.
 * User: wes
 * Date: 1/18/17
 * Time: 7:58 PM
 */

namespace Core\Utils\Exception;


use Exception;

/**
 * To be thrown when an illegal argument is encountered
 * @package Core\Utils\Exception
 */
class IllegalArgumentException extends \Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}