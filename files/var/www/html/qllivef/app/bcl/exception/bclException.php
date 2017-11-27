<?php
namespace bcl\exception;

class bclException extends \Exception
{
    
    public static function thrown($mess)
    {
        throw new \bclException($mess);
    }
    
    
}