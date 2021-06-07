<?php

namespace Tests;

use Mockery;
use Illuminate\Validation\Factory;
use Illuminate\Container\Container;
use Illuminate\Translation\Translator;
use PHPUnit\Framework\TestCase as Base;
use Illuminate\Translation\ArrayLoader;

abstract class TestCase extends Base
{
    /**
     * @before
     */
    protected function setUpMockery()
    {
        Mockery::getConfiguration()->allowMockingNonExistentMethods(false);
    }

    /**
     * @before
     */
    protected function setUpContainer()
    {
        $container = Container::getInstance();

        $container->singleton('translator', function ($container) {
            return new Translator(new ArrayLoader(), 'en');
        });

        $container->singleton('validator', function ($container) {
            return new Factory($container['translator'], $container);
        });
    }

    /**
     * A simple helper so our test case expectations are a little more clear.
     *
     * @param mixed $value
     *        The value that we are asserting is void.
     */
    protected function assertVoid($value)
    {
        $this->assertNull($value);
    }
}
