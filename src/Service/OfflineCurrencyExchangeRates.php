<?php

namespace Dove\Commission\Service;

use Dove\Commission\Utility\Helpers;

class OfflineCurrencyExchangeRates implements RateConverterInterface
{
    use Helpers;

    /**
     * @param $fromCurrency
     * @param $toCurrency
     * @param $amount
     * @return mixed
     */
    public function convert($fromCurrency, $toCurrency, $amount)
    {
        $rates = $this->getRates();
        if ($rates) {
            $this->validateIfCurrenciesPresentInApi($rates, $fromCurrency, $toCurrency);

            $fromCurrencyRate = $rates[$fromCurrency];
            $toCurrencyRate = $rates[$toCurrency];

            $convertedAmount = $amount * ($toCurrencyRate / $fromCurrencyRate);
            return round($convertedAmount, 4);
        }
        throw new \RuntimeException("Online rates are not available right now");
    }

    /**
     * @return array
     * */
    private function getRates(): array
    {
        $rates = self::config("app.offline_exchange_rates");
        return $rates ?? [];
    }

    /**
     * @param $rates
     * @param $fromCurrency
     * @param $toCurrency
     * @return void
     */
    private function validateIfCurrenciesPresentInApi($rates, $fromCurrency, $toCurrency)
    {
        if (!isset($rates[$fromCurrency])) {
            throw new \RuntimeException("Currency {$fromCurrency} is not available in API");
        }
        if (!isset($rates[$toCurrency])) {
            throw new \RuntimeException("Currency {$toCurrency} is not available in API");
        }
    }
}