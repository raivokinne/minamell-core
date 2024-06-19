<?php

namespace Minamell\Minamell;

class ValidationException extends \Exception
{
    public readonly array $errors;
    public readonly array $old;
    /**
     * @return void
     * @param mixed $errors
     * @param mixed $old
     */
    public static function throw($errors, $old)
    {
       $instance = new static('The form failed to validate.');

       $instance->errors = $errors;
       $instance->old = $old;

       throw $instance;
    }
}
