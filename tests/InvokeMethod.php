<?php

namespace App\Tests;

use App\Class\Generate;
use ReflectionClass;

trait InvokeMethod
{
    public function invokeMethod(&$obj, $methodName, array $parameters = array())
    {
        $reflection = new ReflectionClass(get_class($obj));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($obj, $parameters);
    }
}