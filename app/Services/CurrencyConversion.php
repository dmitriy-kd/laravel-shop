<?php

namespace App\Services;

use App\Currency;
use Illuminate\Support\Carbon;

class CurrencyConversion
{
    const DEFAULT_CURRENCY_CODE = 'RUB';
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

    public static function convert($sum, $originCurrencyCode = self::DEFAULT_CURRENCY_CODE, $targetCurrencyCode = null)
    {
        self::loadContainer();
//        $originCurrency = Currency::byCode($originCurrencyCode)->firstOrFail(); для сокращения кол-ва запросов к бд
        // на получения текущего кода валюты и тд, мы сразу создаем "контейнер" где будем хранить уже готовые значения
        $originCurrency = self::$container[$originCurrencyCode];

        if ($originCurrency->code != self::DEFAULT_CURRENCY_CODE)
        {
            if ($originCurrency->rate == 0 || $originCurrency->updated_at->startOfDay()->toString() !== Carbon::now()->startOfDay()->toString())
            {
                CurrencyRates::getRates();
                self::loadContainer();
                $originCurrency = self::$container[$originCurrencyCode];
            }
        }

        if (is_null($targetCurrencyCode)) {
            $targetCurrencyCode = session('currency', self::DEFAULT_CURRENCY_CODE);
        }

//        $targetCurrency = Currency::byCode($targetCurrencyCode)->firstOrFail();
        $targetCurrency = self::$container[$targetCurrencyCode];

        if ($targetCurrency->code != self::DEFAULT_CURRENCY_CODE)
        {
            if ($targetCurrency->rate == 0 || $targetCurrency->updated_at->startOfDay()->toString() !== Carbon::now()->startOfDay()->toString())
            {
                CurrencyRates::getRates();
                self::loadContainer();
                $targetCurrency = self::$container[$targetCurrencyCode];
            }
        }

        return $sum / $originCurrency->rate * $targetCurrency->rate;
    }

    public static function getCurrencySymbol()
    {
//        return Currency::byCode(session('currency', 'RUB'))->firstOrFail()->symbol;
        self::loadContainer();
        $currencyFromSession = self::getCurrencyFromSession();
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

    public static function getCurrencyFromSession()
    {
        return session('currency', self::DEFAULT_CURRENCY_CODE);
    }

    public static function getCurrentCurrencyFromSession()
    {
        self::loadContainer();
        $currencyCode = self::getCurrencyFromSession();
        foreach (self::$container as $currency)
        {
            if ($currency->code === $currencyCode)
            {
                return $currency;
            }
        }
    }
}
