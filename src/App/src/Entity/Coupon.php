<?php

namespace App\Entity;

use App\Repository\CouponRepository;
use App\Validator\CouponTypeContraint;
use App\Validator\CouponTypeEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
class Coupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotNull]
    private ?string $number = null;

    #[ORM\Column(type: 'string', length: 10, enumType: CouponTypeEnum::class)]
    #[Assert\NotNull]
    #[CouponTypeContraint(enumType: CouponTypeEnum::class)]     
    private ?CouponTypeEnum $type;

    #[ORM\Column]
    private ?int $discount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getType(): ?CouponTypeEnum
    {
        return $this->type;
    }

    public function setType(CouponTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(int $discount): static
    {
        $this->discount = $discount;

        return $this;
    }
}
