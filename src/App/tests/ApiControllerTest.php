<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testCalculatePrice(): void
    {
        $client = static::createClient();

        $client->jsonRequest('POST', '/api/v1/calculate-price', ['product' => 1, 'taxNumber' => 'GR123456789', 'couponCode' => 'D15']);
        $this->assertResponseIsSuccessful();
        $this->assertEquals(116.56, $client->getResponse()->getContent());

        $client->jsonRequest('POST', '/api/v1/calculate-price', ['product' => 1, 'taxNumber' => 'GR123456789', 'couponCode' => 'P5']);
        $this->assertResponseIsSuccessful();
        $this->assertEquals(117.8, $client->getResponse()->getContent());     

        $client->jsonRequest('POST', '/api/v1/calculate-price', ['product' => 1, 'taxNumber' => 'GR123456789']);
        $this->assertResponseIsSuccessful();
        $this->assertEquals(124, $client->getResponse()->getContent());     
    }

    public function testPayment(): void
    {
        $client = static::createClient();
        $client->jsonRequest('POST', '/api/v1/process-payment', ['product' => 1, 'taxNumber' => 'DE123456789', 'couponCode' => 'D15', "paymentProcessor" => "paypal"]);

        $this->assertResponseIsSuccessful();
    }  
}
