<?php

namespace Dove\Commission\Service;

use Dove\Commission\Model\Currency\EUR;
use Dove\Commission\Model\CurrencyInterface;

class RateConverterService
{
    const EXCHANGE_RATE_API_BASE_PATH = 'http://api.exchangeratesapi.io/v1/latest?';
    const ACCESS_KEY = '5eca382617a612546c80c6674ab11ffa';

    /**
     * Rate Converter
     * @param CurrencyInterface $from
     * @param float $amount
     * @param null $memory
     * @return float
     */

    public static function convertInEuro(CurrencyInterface $from, float $amount, &$memory = null): float
    {
        if ($from instanceof EUR) {
            return $amount;
        }

        $rate = self::processRate($from, $amount, $memory);

        return ($amount / $rate);
    }

    public static function processRate(CurrencyInterface $fromCurrency, $amount, &$memory): float
    {
        $euroCurrency = new EUR();

        $from = $fromCurrency->getCurrencyCode();
        $to = $euroCurrency->getCurrencyCode();
        $rate = null;

        if (isset($memory)) { // Check in Cache First
            $rate = self::getRateFromCache($from, $to, $memory);
        }

        if (!$rate) {
            $apiResponse = file_get_contents(
                self::buildExchangeRateAPI($from, $to, $amount)
            );

            $decodedResponse = json_decode($apiResponse, true);

            if (isset($decodedResponse['success'], $decodedResponse['rates']) && $decodedResponse['success']) {
                $rate = $decodedResponse['rates'][$from];
                self::storeRateInCache($from, $to, $rate, $memory);
                return $rate;
            }

            throw new \RuntimeException("API has no/invalid response, response => " . json_encode($apiResponse));
        }
        return $rate;
    }

    private static function getRateFromCache($from, $to, &$memory)
    {
        if (isset($memory)) {
            return $memory['rates'][$from][$to] ?? null;
        }
        return null;
    }

    public static function buildExchangeRateAPI(string $from, string $to, float $amount): string
    {
        return self::EXCHANGE_RATE_API_BASE_PATH
            . 'access_key=' . self::ACCESS_KEY . '&'
            . 'base=' . $to . '&'
            . 'symbols=' . $from . '&';
    }

    private static function storeRateInCache($from, $to, $rate, &$memory)
    {
        if (isset($memory)) {
            $memory['rates'][$from][$to] = $rate;
        }
    }

    public static function convertInCurrencyFromEuro(CurrencyInterface $to, float $amount, &$memory = null): string
    {
        if ($to instanceof EUR) {
            return $amount;
        }

        $rate = self::processRate($to, $amount, $memory);

        return $amount * $rate;
    }
}