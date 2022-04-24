<?php

namespace Dove\Commission\Utility;

use DateTime;
use Dove\Commission\Service\OfflineCurrencyExchangeRatesService;
use Dove\Commission\Service\OnlineCurrencyExchangeRatesApiService;

class Utils
{
    use Helpers;

    public static function toBaseCurrency($amount, $currency)
    {
        return self::convertCurrency($amount, $currency);
    }

    private static function convertCurrency($amount, $currency, $toBase = true)
    {
        $baseCurrency = self::config("app.base_currency");
        $cloudCurrencyChecking = self::config("app.cloud_currency_rates");
        $payseraCurrencyConverter = new OnlineCurrencyExchangeRatesApiService();
        $offlineCurrencyConverter = new OfflineCurrencyExchangeRatesService();

        if ($toBase) {
            $fromCurrency = $currency;
            $toCurrency = $baseCurrency;
        } else {
            $fromCurrency = $baseCurrency;
            $toCurrency = $currency;
        }

        if ($baseCurrency) {
            if ($cloudCurrencyChecking) {
                return $payseraCurrencyConverter->convert($fromCurrency, $toCurrency, $amount);
            }

            return $offlineCurrencyConverter->convert($fromCurrency, $toCurrency, $amount);
        }
        throw new \RuntimeException("Please set base currency in config first");
    }

    public static function toOperationCurrency($amount, $currency)
    {
        return self::convertCurrency($amount, $currency, false);
    }

    public static function roundDecimal($amount, $places = 2): string
    {
        $places = $places > 0 ? $places : 0;
        $multiplier = 10 ** $places;

        return number_format(ceil($amount * $multiplier) / $multiplier, $places);
    }


    public static function nextDateOccurrence(DateTime $operationAt, $format): DateTime
    {
        $date = clone($operationAt);
        return $date->setISODate($operationAt->format('o'), $date->format($format) + 1);
    }
}
