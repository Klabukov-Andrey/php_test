<?php

declare(strict_types=1);

namespace App\Validator;

use App\Validator\CouponTypeContraint;
use App\Validator\CouponTypeEnum;
use BackedEnum;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use UnitEnum;

class CouponTypeValidator extends ConstraintValidator
{
    /**
     * @param $value
     * @param Constraint $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!($constraint instanceof CouponTypeContraint)) {
            throw new UnexpectedTypeException($constraint, CouponTypeContraint::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        $enumType = $constraint->enumType;
        if (!is_a($enumType, UnitEnum::class, true)) {
            throw new UnexpectedTypeException($enumType, UnitEnum::class);
        }

        if (!is_a($value, $enumType, true)) {
            throw new UnexpectedValueException(gettype($value), $enumType);
        }

        $property = is_a($enumType, BackedEnum::class, true) ? 'value' : 'name';
        $availableValues = array_column(CouponTypeEnum::cases(), $property);

        if (!in_array($value->{$property}, $availableValues, true)) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
