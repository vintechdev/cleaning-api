<?php

namespace App\Services\Bookings\Traits;

use App\Booking;
use App\PaymentDto;
use App\Providermetadatum;
use App\Services\Payments\Exceptions\CreditCardNotSetUpException;
use App\Services\Payments\Exceptions\PaymentAccountNotSetUpException;
use App\Services\Payments\Exceptions\PaymentFailedException;
use App\Services\Payments\Interfaces\PaymentProcessorInterface;
use App\User;

/**
 * Trait BookingPaymentHandlerTrait
 * @package App\Services\Bookings\Traits
 */
trait BookingPaymentHandlerTrait
{
    /**
     * @return PaymentProcessorInterface
     */
    abstract protected function getPaymentProcessor(): PaymentProcessorInterface;

    /**
     * @param Booking $booking
     * @param User $provider
     * @return bool
     * @throws \App\Services\Payments\Exceptions\InvalidPaymentDataException
     * @throws \App\Services\Payments\Exceptions\InvalidUserException
     */
    protected function handlePayment(Booking $booking, User $provider)
    {
        $paymentDto = new PaymentDto();
        $providerMetadata = Providermetadatum::findByProviderId($provider->getId());
        $serviceFeePercentage = !is_null($providerMetadata) ?
            $providerMetadata->getServiceFeePercentage() :
            null;

        $paymentDto
            ->setAmount($booking->getFinalCost())
            ->setMetadata(['booking_id' => $booking->getId()])
            ->setPayer(User::find($booking->getUserId()))
            ->setPayee($provider)
            ->setTransferFeePercentage($serviceFeePercentage)
            // TODO: Update payment description
            ->setPaymentDescription('Booking id:' . $booking->getId());

        try {
            if (!$this
                ->getPaymentProcessor()
                ->setPaymentData($paymentDto)
                ->process()
            ) {
                throw new PaymentFailedException('Payment failed for some reason. Please contact administrator.');
            }
        } catch (PaymentAccountNotSetUpException $exception) {
            throw new PaymentFailedException('Payment failed as account details are not set up');
        } catch (CreditCardNotSetUpException $exception) {
            throw new PaymentFailedException('Customer\'s credit card is not set up or is expired');
        }

        return true;
    }
}
