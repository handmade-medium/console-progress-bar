<?php
namespace HandmadeMedium\ProgressBar\TestLib;

class ReflectionUtil
{
    public static function callProtectedMethod($obj, $methodName, array $args) {
        $method = new \ReflectionMethod($obj, $methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($obj, $args);
    }
    
    public static function setProtectedProperty($obj, $propertyName, $value) {
        $property = new \ReflectionProperty($obj, $propertyName);
        $property->setAccessible(true);
        $property->setValue($obj, $value);
    }
}