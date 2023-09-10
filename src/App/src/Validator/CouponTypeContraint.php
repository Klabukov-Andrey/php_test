<?php

declare(strict_types=1);

namespace App\Validator;

use App\Validator\CouponTypeValidator;
use Symfony\Component\Validator\Constraint;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class CouponTypeContraint extends Constraint
{
    // error message
    public string $message = 'Error';

    /**
     * @param string $enumType
     * @param null $options
     * @param array|null $groups
     * @param null $payload
     * @param string|null $message
     */
    public function __construct(
        public string $enumType,
        $options = null,
        array $groups = null,
        $payload = null,
        ?string $message = null,
    ) {
        parent::__construct($options, $groups, $payload);

        $this->message = $message ?? $this->message;
    }

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return CouponTypeValidator::class;
    }
}