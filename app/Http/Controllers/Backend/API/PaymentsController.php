<?php

namespace App\Http\Controllers\Backend\API;

use App\Http\Controllers\Controller;
use App\PaymentGateway;
use App\Services\Payments\Exceptions\InvalidUserException;
use App\Services\Payments\PaymentIntitialisationService;
use App\Services\Payments\StripeService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\Exception\ApiErrorException;

/**
 * Class PaymentsController
 * @package App\Http\Controllers\Backend\API
 */
class PaymentsController extends Controller
{
    /**
     * @param Request $request
     * @param PaymentIntitialisationService $paymentIntitialisationService
     * @return \Illuminate\Http\JsonResponse
     */
    public function initialiseStripe(Request $request, PaymentIntitialisationService $paymentIntitialisationService)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 400);
        }

        $data = [
            'amount' => $request->get('amount'),
            'currency' => 'aud',
            'setup_future_usage' => 'off_session',
            'user_id' => auth()->user()->id
        ];

        try {
            $id = $paymentIntitialisationService
                ->initialisePayment(PaymentGateway::STRIPE_PAYMENT_GATEWAY, $data);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Something went wrong. Please contact administrator.'], 500);
        }

        return response()->json(['client_secret' => $id, 'publishable_key' => \Config::get('payment.STRIPE_KEY')], 201);
    }

    /**
     * @param Request $request
     * @param StripeService $stripeService
     * @return \Illuminate\Http\JsonResponse
     */
    public function createStripeSession(Request $request, StripeService $stripeService)
    {
        $validator = Validator::make($request->all(), [
            'success_url' => 'string|required',
            'cancel_url' => 'string|required'
        ]);

        if ($validator->fails()) {
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 400);
        }

        try {
            $sessionId = $stripeService
                ->createSession($request->get('success_url'), $request->get('cancel_url'), auth()->user()->id);

            return response()->json([
                'session_id' => $sessionId, 'publishable_key' => \Config::get('payment.STRIPE_KEY')
            ], 200);
        } catch (ApiErrorException $exception) {
            $message = $exception->getMessage();
        } catch (\Exception $exception) {
            $message = 'Something went wrong. Please contact administrator.';
        }

        return response()->json(['message' => $message], 500);
    }

    /**
     * @param Request $request
     * @param StripeService $stripeService
     * @return \Illuminate\Http\JsonResponse
     */
    public function createStripePaymentMethodIntent(Request $request, StripeService $stripeService)
    {
        try {
            $id = $stripeService->createPaymentMethodSetupIntent(auth()->user());
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Something went wrong. Please contact Administrator.'], 500);
        }

        return response()->json([
            'client_secret' => $id, 'publishable_key' => \Config::get('payment.STRIPE_KEY')
        ], 200);
    }

    /**
     * @param StripeService $stripeService
     * @return \Illuminate\Http\JsonResponse
     * @throws ApiErrorException
     */
    public function retrieveStripeCard(StripeService $stripeService)
    {
        $data = $stripeService->retrieveStoredPaymentMethod(auth()->user()->id);

        if (!$data) {
            return response()->json([], 404);
        }

        return response()
            ->json(
                [
                    'exp_month' => $data['card']['exp_month'],
                    'exp_year' => $data['card']['exp_year'],
                    'last4' => $data['card']['last4']
                ],
                200
            );
    }

    /**
     * @param Request $request
     * @param StripeService $stripeService
     * @return \Illuminate\Http\JsonResponse
     */
    public function addStripeCard(Request $request, StripeService $stripeService)
    {
        if (!$request->has('payment_method_id')) {
            return response()->json(['message' => 'Invalid parameters received'], 400);
        }

        try {
            $associated = $stripeService->associatePaymentMethod($request->get('payment_method_id'), auth()->user());
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Something went wrong while adding card. Please contact administrator'], 500);
        }

        if ($associated) {
            return response()->json(['message' => 'Card successfully added'], 201);
        }

        return response()->json(['message' => 'Something went wrong while adding card. Please contact administrator'], 500);
    }

    /**
     * @param Request $request
     * @param StripeService $stripeService
     * @return \Illuminate\Http\JsonResponse
     */
    public function createStripeAccountLink(Request $request, StripeService $stripeService)
    {
        $validator = Validator::make($request->all(), [
            'return_url' => 'required',
            'refresh_url' => 'required'
        ]);

        if ($validator->fails()) {
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 400);
        }

        try {
            $accountLink = $stripeService
                ->createAccountLink(auth()->user(), $request->get('return_url'), $request->get('refresh_url'));
        } catch (InvalidUserException $exception) {
            return response()->json(['message' => 'User is unauthorized to perform this action'], 403);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Something went wrong. Please contact administrator'], 500);
        }

        return response()
            ->json($accountLink, 201);
    }

    public function verifyStripeAccount(Request $request, StripeService $stripeService, User $user)
    {
        /** @var User $loggedinUser */
        $loggedinUser = auth()->user();
        if ($user->getId() != $loggedinUser->getId() && !$loggedinUser->isAdmin()) {
            return response()->json(['message' => 'User is unauthorized to perform this action'], 403);
        }

        try {
            $verified = $stripeService->verifyStripeConnectedAccount($user);
        } catch (InvalidUserException $exception) {
            return response()->json(['message' => 'User does not have a stripe account'], 404);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Something went wrong. Please contact administrator'], 500);
        }

        return response()->json(['verified' => $verified], 200);
    }

    /**
     * @param Request $request
     * @param StripeService $stripeService
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAccountLoginLink(Request $request, StripeService $stripeService, User $user)
    {
        $validator = Validator::make($request->all(), [
            'redirect_url' => 'required'
        ]);

        if ($validator->fails()) {
            $message = $validator->messages()->all();
            return response()->json(['message' => $message], 400);
        }
        /** @var User $loggedinUser */
        $loggedinUser = auth()->user();
        if ($user->getId() != $loggedinUser->getId() && !$loggedinUser->isAdmin()) {
            return response()->json(['message' => 'User is unauthorized to perform this action'], 403);
        }

        try {
            $url = $stripeService->createAccountLoginLink($user, $request->get('redirect_url'));
        } catch (InvalidUserException $exception) {
            return response()->json(['message' => 'User does not have a stripe account'], 404);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Something went wrong. Please contact administrator'], 500);
        }

        return response()->json(['url' => $url], 201);
    }

    /**
     * @param Request $request
     * @param StripeService $stripeService
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws ApiErrorException
     * @throws InvalidUserException
     */
    public function getStripeAccountBalance(Request $request, StripeService $stripeService, User $user)
    {
        /** @var User $loggedinUser */
        $loggedinUser = auth()->user();
        if ($user->getId() != $loggedinUser->getId() && !$loggedinUser->isAdmin()) {
            return response()->json(['message' => 'User is unauthorized to perform this action'], 403);
        }

        try {
            $balance = $stripeService->getAccountBalance($user);
            return response()->json($balance, 200);
        } catch (InvalidUserException $exception) {
            return response()->json(['message' => 'User does not have a stripe account'], 404);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Something went wrong. Please contact administrator'], 500);
        }
    }
}