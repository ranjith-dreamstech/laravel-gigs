<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Modules\GeneralSetting\Models\Currency;
use Modules\GeneralSetting\Models\GeneralSetting;

class CurrencyService
{
    private const CACHE_TTL = 3600; // 1 hour

    /**
     * Get default currency symbol
     *
     * @return string
     */
    public function getDefaultCurrencySymbol(): string
    {
        return Cache::remember('default_currency_symbol', self::CACHE_TTL, function () {
            $defaultCurrency = GeneralSetting::where('key', 'currency_symbol')->first();
            $currencyId = $defaultCurrency->value ?? '';
            
            if ($currencyId) {
                $currency = Currency::find((string) $currencyId);
                if ($currency instanceof Currency) {
                    return $currency->symbol ?? '$';
                }
            }
            
            return '$';
        });
    }

    /**
     * Get default currency details
     *
     * @return array
     */
    public function getDefaultCurrency(): array
    {
        return Cache::remember('default_currency_details', self::CACHE_TTL, function () {
            $defaultCurrency = GeneralSetting::where('key', 'currency_symbol')->first();
            $currencyId = $defaultCurrency->value ?? '';
            
            if ($currencyId) {
                $currency = Currency::find((string) $currencyId);
                if ($currency instanceof Currency) {
                    return [
                        'id' => $currency->id,
                        'name' => $currency->name,
                        'code' => $currency->code,
                        'symbol' => $currency->symbol,
                        'rate' => $currency->rate ?? 1,
                    ];
                }
            }
            
            return [
                'id' => null,
                'name' => 'US Dollar',
                'code' => 'USD',
                'symbol' => '$',
                'rate' => 1,
            ];
        });
    }

    /**
     * Format price with currency symbol
     *
     * @param float|int $price
     * @param bool $withSymbol
     * @param bool $rawPrice
     * @param string|null $currencyCode
     * @return string|float|int
     */
    public function formatPrice(
        float|int $price, 
        bool $withSymbol = true, 
        bool $rawPrice = false,
        ?string $currencyCode = null
    ): string|float|int {
        if ($rawPrice) {
            return $price;
        }

        $formattedPrice = number_format($price, 2);
        
        if (!$withSymbol) {
            return $formattedPrice;
        }

        $symbol = $currencyCode ? 
            $this->getCurrencySymbol($currencyCode) : 
            $this->getDefaultCurrencySymbol();

        return $symbol . $formattedPrice;
    }

    /**
     * Get currency symbol by currency code
     *
     * @param string $currencyCode
     * @return string
     */
    public function getCurrencySymbol(string $currencyCode): string
    {
        $cacheKey = "currency_symbol_{$currencyCode}";
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($currencyCode) {
            $currency = Currency::where('code', $currencyCode)->first();
            return $currency ? $currency->symbol : '$';
        });
    }

    /**
     * Convert amount from one currency to another
     *
     * @param float $amount
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return float
     */
    public function convertCurrency(float $amount, string $fromCurrency, string $toCurrency): float
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        $fromRate = $this->getCurrencyRate($fromCurrency);
        $toRate = $this->getCurrencyRate($toCurrency);

        // Convert to base currency first, then to target currency
        $baseAmount = $amount / $fromRate;
        return $baseAmount * $toRate;
    }

    /**
     * Get currency exchange rate
     *
     * @param string $currencyCode
     * @return float
     */
    public function getCurrencyRate(string $currencyCode): float
    {
        $cacheKey = "currency_rate_{$currencyCode}";
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($currencyCode) {
            $currency = Currency::where('code', $currencyCode)->first();
            return $currency ? (float) $currency->rate : 1.0;
        });
    }

    /**
     * Get all active currencies
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCurrencies()
    {
        return Cache::remember('all_currencies', self::CACHE_TTL, function () {
            return Currency::where('status', 1)
                ->orderBy('name')
                ->get(['id', 'name', 'code', 'symbol', 'rate']);
        });
    }

    /**
     * Update currency rate
     *
     * @param string $currencyCode
     * @param float $rate
     * @return bool
     */
    public function updateCurrencyRate(string $currencyCode, float $rate): bool
    {
        $currency = Currency::where('code', $currencyCode)->first();
        
        if ($currency) {
            $currency->rate = $rate;
            $currency->updated_at = now();
            $success = $currency->save();
            
            if ($success) {
                $this->clearCurrencyCache($currencyCode);
            }
            
            return $success;
        }
        
        return false;
    }

    /**
     * Clear currency cache
     *
     * @param string|null $currencyCode
     * @return void
     */
    public function clearCurrencyCache(?string $currencyCode = null): void
    {
        if ($currencyCode) {
            Cache::forget("currency_symbol_{$currencyCode}");
            Cache::forget("currency_rate_{$currencyCode}");
        } else {
            Cache::forget('default_currency_symbol');
            Cache::forget('default_currency_details');
            Cache::forget('all_currencies');
            
            // Clear all currency-specific caches
            $currencies = Currency::pluck('code');
            foreach ($currencies as $code) {
                Cache::forget("currency_symbol_{$code}");
                Cache::forget("currency_rate_{$code}");
            }
        }
    }

    /**
     * Format currency for display in different locales
     *
     * @param float $amount
     * @param string $currencyCode
     * @param string $locale
     * @return string
     */
    public function formatCurrencyByLocale(float $amount, string $currencyCode = 'USD', string $locale = 'en_US'): string
    {
        if (class_exists('NumberFormatter')) {
            $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
            return $formatter->formatCurrency($amount, $currencyCode);
        }
        
        // Fallback if NumberFormatter is not available
        return $this->formatPrice($amount, true, false, $currencyCode);
    }

    /**
     * Get currency position (before or after amount)
     *
     * @param string|null $currencyCode
     * @return string
     */
    public function getCurrencyPosition(?string $currencyCode = null): string
    {
        // You can extend this to support different currency positions
        // For now, returning 'before' as default
        return 'before';
    }

    /**
     * Validate currency code
     *
     * @param string $currencyCode
     * @return bool
     */
    public function isValidCurrency(string $currencyCode): bool
    {
        return Currency::where('code', $currencyCode)
            ->where('status', 1)
            ->exists();
    }

    /**
     * Get user's preferred currency
     *
     * @param int|null $userId
     * @return array
     */
    public function getUserCurrency(?int $userId = null): array
    {
        // You can implement user-specific currency preferences here
        // For now, returning default currency
        return $this->getDefaultCurrency();
    }
}