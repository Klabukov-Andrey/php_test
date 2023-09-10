<?php

declare(strict_types=1);

namespace App\Service;

interface ICalculatePriceService
{
    /**
     * @param integer $productId
     * @param string $taxNumber
     * @param string $couponCode
     * @return float
     */
    public function calculate(int $productId, string $taxNumber, string $couponCode): float;
}
