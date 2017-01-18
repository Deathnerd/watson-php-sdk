<?php
/**
 * Created by PhpStorm.
 * User: wes
 * Date: 1/18/17
 * Time: 1:34 PM
 */

namespace Core\Service\Model;


abstract class GenericModel
{
    public function equals(GenericModel $o)
    {
        if ($this === $o) {
            return true;
        }

        if ($o === null || get_class($this) !== get_class($o)) {
            return false;
        }

        return $this->__toString() == $o->__toString();
    }

    function __toString()
    {
        // TODO: May need to genericize and implement the JsonSerializable interface
        return json_encode($this, JSON_PRETTY_PRINT);
    }
}