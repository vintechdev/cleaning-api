<?php

namespace App\Services\Payments;

use App\Services\Payments\Exceptions\InvalidPaymentInitialisationDataException;
use App\Services\Payments\Exceptions\PaymentInitialiserException;
use App\Services\Payments\Interfaces\PaymentInitialiserInterface;
use App\Services\Payments\Interfaces\PaymentProcessorInterface;

/**
 * Class StripePaymentProcessor
 * @package App\Services\Payments
 */
class StripePaymentProcessor implements PaymentProcessorInterface, PaymentInitialiserInterface
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @var int
     */
    private $initialisationId;

    /**
     * @var StripeService
     */
    private $stripeService;

    /**
     * StripePaymentProcessor constructor.
     * @param StripeService $stripeService
     */
    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * @return bool
     * @throws InvalidPaymentInitialisationDataException
     * @throws PaymentInitialiserException
     */
    public function intialisePayment(): bool
    {
        try {
            $id = $this->stripeService->createIntent($this->data);
        } catch (\InvalidArgumentException $exception) {
            throw new InvalidPaymentInitialisationDataException($exception->getMessage());
        } catch (\RuntimeException $exception) {
            throw new PaymentInitialiserException($exception->getMessage());
        }

        $this->initialisationId = $id;
        return  true;
    }

    /**
     * @return int
     */
    public function getInitialisationId(): string
    {
        return $this->initialisationId;
    }

    /**
     * @return bool
     */
    public function process(): bool
    {
        // TODO: Implement process() method.
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setInitialisationData(array $data) : PaymentInitialiserInterface
    {
        $this->data = $data;
        return $this;
    }
}