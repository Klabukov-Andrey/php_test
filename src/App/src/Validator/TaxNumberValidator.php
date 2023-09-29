<?php

declare(strict_types=1);

namespace App\Validator;

use App\Validator\TaxNumberConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

class TaxNumberValidator extends ConstraintValidator
{
    private function getPattern($value): string
    {
        $countyPrefix = substr($value, 0, 2);
        
        switch ($countyPrefix) {
            case 'GR':
            case 'DE':
                return '/^[0-9]{9}$/';
            case 'IT':
                return '/^[0-9]{11}$/';
            case 'FR':
                return '/^[A-Z]{2}[0-9]{9}$/';
            default:
                throw new InvalidArgumentException(sprintf('Invalid country: %s', $countyPrefix));
        }
    }

    /**
     * @param [type] $value
     * @param Constraint $constraint
     * 
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    { 
        if (!($constraint instanceof TaxNumberConstraint)) {
            throw new UnexpectedTypeException($constraint, TaxNumberConstraint::class);
        }

        if (null === $value || '' === $value) {
            throw new InvalidArgumentException(sprintf('The tax number "%s" is not valid. ', $value));
        }

        $pattern = $this->getPattern($value);
        $str = substr($value, 2);

        if (!preg_match($pattern, $str)) {
            throw new InvalidArgumentException(sprintf('The tax number "%s" is not valid. ', $value));
        }
    }
}
