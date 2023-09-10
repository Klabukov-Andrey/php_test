<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;
use App\Entity\Coupon;
use App\Entity\Tax;
use App\Validator\CouponTypeEnum;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = (new Product())->setName('Iphone')->setPrice(100);
        $manager->persist($product);
        $product = (new Product())->setName('Наушники')->setPrice(20);
        $manager->persist($product);
        $product = (new Product())->setName('Чехол')->setPrice(10);
        $manager->persist($product);

        $manager->flush();

        $coupon = (new Coupon())->setNumber('D15')->setDiscount('6')->setType(CouponTypeEnum::Percent);
        $manager->persist($coupon);
        $coupon = (new Coupon())->setNumber('P5')->setDiscount('5')->setType(CouponTypeEnum::Fixed);
        $manager->persist($coupon);

        $manager->flush();

        $tax = (new Tax())->setCountry('Германия')->setPrefix('DE')->setTax(19);
        $manager->persist($tax);
        $tax = (new Tax())->setCountry('Италия')->setPrefix('IT')->setTax(22);
        $manager->persist($tax);
        $tax = (new Tax())->setCountry('Франция')->setPrefix('FR')->setTax(20);
        $manager->persist($tax);
        $tax = (new Tax())->setCountry('Греция')->setPrefix('GR')->setTax(24);
        $manager->persist($tax);

        $manager->flush();
    }
}
