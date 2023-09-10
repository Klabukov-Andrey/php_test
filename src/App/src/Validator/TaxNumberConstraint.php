<?php

declare(strict_types=1);

namespace App\Validator;

use App\Validator\TaxNumberValidator;
use Symfony\Component\Validator\Constraint;

class TaxNumberConstraint extends Constraint
{
    // error message
    public $message = '';

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return TaxNumberValidator::class;
    }
}