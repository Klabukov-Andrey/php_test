<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\Tax;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Repository\TaxRepository;
use App\Validator\CouponTypeEnum;
use Exception;

class CalculatePriceService implements ICalculatePriceService
{
    /**
     * @param TaxRepository $taxRepository
     * @param CouponRepository $couponRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(
        protected TaxRepository $taxRepository,
        protected CouponRepository $couponRepository,
        protected ProductRepository $productRepository
    ) {
    }

    /**
     * @param string $couponCode
     * @return Coupon|null
     */
    private function findCoupon(string $couponCode): ?Coupon
    {
        if ("" === $couponCode) {
            return null;
        }
        /** @var Cupon $coupon */
        $coupon = $this->couponRepository->findOneBy(['number' => $couponCode]);

        if (null === $coupon) {
            throw new Exception(sprintf('The coupon code "%s" is not found. ', $couponCode));
        }

        return $coupon;
    }
    /**
     * @param string $taxNumber
     * @return Tax|null
     */
    private function findTax(string $taxNumber): ?Tax
    {
        /** @var Tax $tax */
        $tax = $this->taxRepository->findOneBy(['prefix' => substr($taxNumber, 0, 2)]);
        if (null === $tax) {
            throw new Exception(sprintf('The tax number "%s" is not found. ', $taxNumber));
        }

        return $tax;
    }

    /**
     * @inheritDoc
     */
    public function calculate(int $productId, string $taxNumber, string $couponCode): float
    {
        /** @var Product $product */
        $product = $this->productRepository->find($productId);
        if (null === $product) {
            throw new Exception(sprintf('The product number "%s" is not found. ', $taxNumber));
        }

        /** @var Coupon $coupon */
        $coupon = $this->findCoupon($couponCode);

        $productPrice = $product->getPrice();
        if (null !== $coupon) {
            switch ($coupon->getType()) {
                case CouponTypeEnum::Fixed:
                    $productPrice = max($productPrice - $coupon->getDiscount(), 0);
                    break;
                case CouponTypeEnum::Percent:
                    $productPrice = $productPrice - $productPrice * $coupon->getDiscount() / 100;
                    break;
            }
        }

        $tax = $this->findTax($taxNumber);
        return $productPrice + $productPrice * $tax->getTax() / 100;
    }
}
