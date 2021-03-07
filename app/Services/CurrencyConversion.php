<?php

namespace App\Services;

use App\Currency;
use Illuminate\Support\Carbon;

class CurrencyConversion
{
    protected static $container;

    public static function loadContainer()
    {
        if(is_null(self::$container))
        {
            $currencies = Currency::get();
            foreach ($currencies as $currency)
            {
                self::$container[$currency->code] = $currency;
            }
        }
    }

    public static function getCurrencies()
    {
        self::loadContainer();
        return self::$container;
    }

    public static function convert($sum, $originCurrencyCode = 'RUB', $targetCurrencyCode = null)
    {
        self::loadContainer();
//        $originCurrency = Currency::byCode($originCurrencyCode)->firstOrFail(); для сокращения кол-ва запросов к бд
        // на получения текущего кода валюты и тд, мы сразу создаем "контейнер" где будем хранить уже готовые значения
        $originCurrency = self::$container[$originCurrencyCode];

        if ($originCurrency->updated_at->startOfDay()->toString() !== Carbon::now()->startOfDay()->toString())
        {
            CurrencyRates::getRates();
            self::loadContainer();
            $originCurrency = self::$container[$originCurrencyCode];
        }

        if (is_null($targetCurrencyCode)) {
            $targetCurrencyCode = session('currency', 'RUB');
        }

//        $targetCurrency = Currency::byCode($targetCurrencyCode)->firstOrFail();
        $targetCurrency = self::$container[$targetCurrencyCode];

        if ($targetCurrency->updated_at->startOfDay()->toString() !== Carbon::now()->startOfDay()->toString())
        {
            CurrencyRates::getRates();
            self::loadContainer();
            $targetCurrency = self::$container[$targetCurrencyCode];
        }

        return $sum / $originCurrency->rate * $targetCurrency->rate;
    }

    public static function getCurrencySymbol()
    {
//        return Currency::byCode(session('currency', 'RUB'))->firstOrFail()->symbol;
        self::loadContainer();
        $currencyFromSession = session('currency', 'RUB');
        return self::$container[$currencyFromSession]->symbol;
    }

    public static function getBaseCurrency()
    {
        self::loadContainer();
        foreach (self::$container as $code => $currency)
        {
            if ($currency->isMain())
            {
                return $currency;
            }
        }
    }
}
