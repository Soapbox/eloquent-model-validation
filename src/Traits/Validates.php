<?php

namespace Jaspaul\EloquentModelValidation\Traits;

use Illuminate\Validation\Factory;
use Illuminate\Container\Container;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Support\MessageProvider;

trait Validates
{
    /**
     * Returns a list of rules to validate our object properties against.
     *
     * @return array
     */
    abstract protected function getRules() : array;

    /**
     * Returns the data to validate.
     *
     * @return array
     */
    abstract protected function getData() : array;

    /**
     * Returns a list of validation message overrides.
     *
     * @return array
     */
    protected function getMessages() : array
    {
        return [];
    }

    /**
     * Returns an instance of the validator from our container.
     *
     * @return \Illuminate\Validation\Factory
     */
    private function getValidationFactory() : Factory
    {
        $container = Container::getInstance();
        return $container->make('validator');
    }

    /**
     * Returns a validator pre-populated with our attributes, rules, and custom messages.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function getValidator() : Validator
    {
        return $this->getValidationFactory()->make(
            $this->getData(),
            $this->getRules(),
            $this->getMessages()
        );
    }

    /**
     * Used to determine if the object is valid.
     *
     * @return bool
     *         true if the object is valid, false otherwise.
     */
    public function isValid() : bool
    {
        return ! $this->isInvalid();
    }

    /**
     * Used to determine if the object is invalid.
     *
     * @return bool
     *         true if the object is invalid, false otherwise.
     */
    public function isInvalid() : bool
    {
        return $this->getValidator()->fails();
    }

    /**
     * Returns a Message Provider containing the errors for the model validation.
     *
     * @return \Illuminate\Contracts\Support\MessageProvider
     */
    public function getErrors() : MessageProvider
    {
        return $this->getValidator()->getMessageBag();
    }

    /**
     * Save the model to the database.
     *
     * @throws \Illuminate\Validation\ValidationException
     *         Thrown with errors if the model is invalid.
     *
     * @param array $options
     *        Any additional actions you may want to perform after the save.
     *
     * @return bool
     */
    public function save(array $options = [])
    {
        if ($this->isInvalid()) {
            throw new ValidationException($this->getErrors());
        }

        return parent::save($options);
    }
}
