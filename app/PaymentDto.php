<?php

namespace App;

/**
 * Class PaymentDto
 * @package App
 */
class PaymentDto
{
    const PAYMENT_METHOD_TYPE_CARD = 'card';
    const PAYMENT_CURRENCY_AUD = 'aud';

    /**
     * @var float
     */
    private $amount;

    /**
     * @var string
     */
    private $paymentMethodType;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var float
     */
    private $transferFeePercentage;

    /**
     * @var User
     */
    private $payer;

    /**
     * @var User
     */
    private $payee;

    /**
     * @var string
     */
    private $paymentDescription = '';

    /**
     * @var array
     */
    private $metadata = [];

    /**
     * PaymentDto constructor.
     */
    public function __construct()
    {
        $this->paymentMethodType = self::PAYMENT_METHOD_TYPE_CARD;
        $this->currency = self::PAYMENT_CURRENCY_AUD;
    }

    /**
     * @param float $amount
     * @return $this
     */
    public function setAmount(float $amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * @param string $paymentMethodType
     * @return $this
     */
    public function setPaymentMethodType(string $paymentMethodType)
    {
        $this->paymentMethodType = $paymentMethodType;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentMethodType(): ?string
    {
        return $this->paymentMethodType;
    }

    /**
     * @param string $currency
     * @return $this
     */
    public function setCurrency(string $currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param float $transferFeePercentage
     * @return $this
     */
    public function setTransferFeePercentage(float $transferFeePercentage)
    {
        $this->transferFeePercentage = $transferFeePercentage;
        return $this;
    }

    /**
     * @return float
     */
    public function getTransferFeePercentage(): ?float
    {
        return $this->transferFeePercentage;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setPayer(User $user)
    {
        $this->payer = $user;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getPayer(): ?User
    {
        return $this->payer;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setPayee(User $user)
    {
        $this->payee = $user;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getPayee(): ?User
    {
        return $this->payee;
    }

    /**
     * @return string
     */
    public function getPaymentDescription(): string
    {
        return $this->paymentDescription;
    }

    /**
     * @param string $paymentDescription
     * @return $this
     */
    public function setPaymentDescription(string $paymentDescription)
    {
        $this->paymentDescription = $paymentDescription;
        return $this;
    }

    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * @param array $metadata
     * @return $this
     */
    public function setMetadata(array $metadata)
    {
        $this->metadata = $metadata;
        return $this;
    }
}
