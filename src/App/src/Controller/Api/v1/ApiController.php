<?php

namespace App\Controller\Api\v1;

use App\Service\ICalculatePriceService;
use App\Validator\TaxNumberConstraint;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

#[Route('api/v1')]
class ApiController extends AbstractFOSRestController
{
    const API_VERSION = 1;

    public function __construct(
        private ICalculatePriceService $calculatePriceService
    ) {
    }

    #[Rest\Post('/calculate-price', name: 'api_calculate_price')]
    #[Rest\View(statusCode: Response::HTTP_OK)]
    #[Rest\RequestParam(name: 'product', requirements: '\d+', default: -1)]
    #[Rest\RequestParam(name: 'taxNumber', requirements: '\w+', default: '')]
    #[Rest\RequestParam(name: 'couponCode', requirements: '\w+', default: '')]
    /**
     * @param ParamFetcher $paramFetcher
     * @param ValidatorInterface $validator
     * @return void
     */
    public function calculatePrice(ParamFetcher $paramFetcher, ValidatorInterface $validator)
    {
        $constraint = new TaxNumberConstraint();
        
        try {
            $validator->validate($paramFetcher->get('taxNumber'), $constraint);

            return $this->calculatePriceService->calculate(
                $paramFetcher->get('product'),
                $paramFetcher->get('taxNumber'),
                $paramFetcher->get('couponCode')
            );
        } catch (Exception $e) {
            return $this->view(
                [
                    'message' => $e->getMessage(),
                    'code' => Response::HTTP_BAD_REQUEST
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    #[Rest\Post('/process-payment', name: 'api_process_payment')]
    #[Rest\View(statusCode: Response::HTTP_OK)]
    #[Rest\RequestParam(name: 'product', requirements: '\d+', default: -1)]
    #[Rest\RequestParam(name: 'taxNumber', requirements: '\w+', default: '')]
    #[Rest\RequestParam(name: 'couponCode', requirements: '\w+', default: '')]
    #[Rest\RequestParam(name: 'paymentProcessor', requirements: '\w+', default: '')]
    /**
     * @param ParamFetcher $paramFetcher
     * @param ValidatorInterface $validator
     * @return void
     */
    public function processPayment(ParamFetcher $paramFetcher, ValidatorInterface $validator)
    {
        $constraint = new TaxNumberConstraint();

        try {
            $validator->validate($paramFetcher->get('taxNumber'), $constraint);

            $price = $this->calculatePriceService->calculate(
                $paramFetcher->get('product'),
                $paramFetcher->get('taxNumber'),
                $paramFetcher->get('couponCode')
            );

            switch ($paramFetcher->get('paymentProcessor')) {
                case 'paypal':
                    (new PaypalPaymentProcessor())->pay($price * 100);
                    break;

                default:
                    throw new Exception('Unknown paymentProcessor');
                    break;
            }
        } catch (Exception $e) {
            return $this->view(
                [
                    'message' => $e->getMessage(),
                    'code' => Response::HTTP_BAD_REQUEST
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    #[Rest\Get('/version', name: 'api_version')]
    #[Rest\View(statusCode: Response::HTTP_OK)]
    public function version()
    {
        return self::API_VERSION;
    }
}
