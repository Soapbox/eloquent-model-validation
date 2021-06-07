<?php

namespace Tests;

use Tests\Doubles\ValidModel;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Jaspaul\EloquentModelValidation\Contracts\Validatable;

class ValidModelTest extends TestCase
{
    public function testItCanBeConstructed()
    {
        $model = new ValidModel();
        $this->assertInstanceOf(EloquentModel::class, $model);
        $this->assertInstanceOf(Validatable::class, $model);
    }

    public function testTheValidModelIsValid()
    {
        $this->assertTrue((new ValidModel())->isValid());
    }

    public function testGetErrorsReturnsAnEmptyMessageBag()
    {
        $errors = (new ValidModel())->getErrors();
        $this->assertTrue($errors->isEmpty());
    }

    public function testValidateDoesNotThrowAValidationException()
    {
        $this->assertVoid((new ValidModel())->validate());
    }

    public function testGetValidationFailureReasonsReturnsAnEmptyArray()
    {
        $this->assertEmpty((new ValidModel())->getValidationFailureReasons());
    }
}
