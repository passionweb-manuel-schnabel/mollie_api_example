<?php

namespace Passionweb\MollieApi\Service;

use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\MollieApiClient;
use Psr\Log\LoggerInterface;

class MollieService
{
    public function __construct(
        protected MollieApiClient $mollieApiClient,
        protected LoggerInterface $logger
    ) {
    }

    public function createPayment(string $baseUri, float $price, string $name, string $orderId): string
    {
        try {
            $payment = $this->mollieApiClient->payments->create([
                'amount' => [
                    'currency' => 'EUR',
                    'value' => number_format($price, 2)
                ],
                'description' => 'Example payment from ' . $name,
                'redirectUrl' => $baseUri . '/payment-succeeded/' . $orderId,
                'cancelUrl' => $baseUri . '/payment-failed/' . $orderId,
                // you can additionally add a webhook to fetch status changes of the given payment
                //"webhookUrl" => $baseUri . '/payment-webhook',
                "metadata" => [
                    "order_id" => $orderId,
                ],
            ]);

            // save the ID ($payment->id) and additional data (e.g. the order_id) to identify and handle the payment later

            return $payment->getCheckoutUrl();
        } catch(ApiException|\Exception $e) {
            $this->logger->error($e->getMessage());
            return '';
        }
    }
}
