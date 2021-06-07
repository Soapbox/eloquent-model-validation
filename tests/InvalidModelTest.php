<?php

namespace Tests;

use Tests\Doubles\InvalidModel;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Jaspaul\EloquentModelValidation\Contracts\Validatable;

class InvalidModelTest extends TestCase
{
    public function testItCanBeConstructed()
    {
        $model = new InvalidModel();
        $this->assertInstanceOf(EloquentModel::class, $model);
        $this->assertInstanceOf(Validatable::class, $model);
    }

    public function testTheInvalidModelIsInvalid()
    {
        $this->assertTrue((new InvalidModel())->isInvalid());
    }

    public function testGetErrorsReturnsAMessageBagWithTheEmailKey()
    {
        $errors = (new InvalidModel())->getErrors();

        $this->assertFalse($errors->isEmpty());
        $this->assertTrue($errors->has('email'));
    }

    public function testSaveThrowsAValidationException()
    {
        $this->expectException(ValidationException::class);

        (new InvalidModel())->save();
    }

    public function testValidateThrowsAValidationException()
    {
        $this->expectException(ValidationException::class);

        (new InvalidModel())->validate();
    }

    public function testGetValidationFailureReasonsReturnsTheReasonsWhyTheValidationFailed()
    {
        $reasons = (new InvalidModel())->getValidationFailureReasons();

        $this->assertTrue(array_key_exists('email', $reasons));
        $this->assertTrue(array_key_exists('Required', $reasons['email']));
    }
}
