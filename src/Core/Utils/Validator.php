<?php
/**
 * Created by PhpStorm.
 * User: wes
 * Date: 1/18/17
 * Time: 7:41 PM
 */

namespace Core\Utils;

use Core\Utils\Exception\IllegalArgumentException;

/**
 * This class assists in validating arguments
 * @package Core\Utils
 */
final class Validator
{
    /** No instantiation of the Validator, please. Thank you! */
    private function __construct()
    {
    }

    /**
     * Validates that the item is not empty; otherwise throws an error with the specified message.
     *
     * @param mixed  $item    The item to check
     * @param string $message The exception message if invalid
     *
     * @throws IllegalArgumentException If the item is empty
     */
    public static function notEmpty($item, $message)
    {
        if (!$item || empty($item)) {
            throw new IllegalArgumentException($message);
        }
    }

    /**
     * Validates that the specified argument is not `null`; otherwise throws an error with the specified message
     *
     * @param mixed  $item    The item to check
     * @param string $message The exception message if invalid
     *
     * @throws IllegalArgumentException If the item is null
     */
    public static function notNull($item, $message)
    {
        if (is_null($item)) {
            throw new IllegalArgumentException($message);
        }
    }

    /**
     * Validates that the specified argument is `null`; otherwise throws an error with the specified message.
     *
     * @param mixed  $item    The item to check
     * @param string $message The exception message if invalid
     *
     * @throws IllegalArgumentException If the item is not null
     */
    public static function isNull($item, $message)
    {
        if (!is_null($item)) {
            throw new IllegalArgumentException($message);
        }
    }

}