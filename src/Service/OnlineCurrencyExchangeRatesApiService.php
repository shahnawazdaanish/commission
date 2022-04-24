<?php

namespace Dove\Commission\Service;

use Dove\Commission\Utility\Helpers;

class OnlineCurrencyExchangeRatesApiService implements RateConverterInterface
{
    use Helpers;

    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = self::config("app.online_exchange_api_url");
        if (!$this->apiUrl) {
            throw new \RuntimeException("Online Api URL for exchange rates is missing in config");
        }
    }

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
        $rates = json_decode(@file_get_contents($this->apiUrl), true);
        if ($rates) {
            return $rates['rates'] ?? [];
        }
        return [];
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