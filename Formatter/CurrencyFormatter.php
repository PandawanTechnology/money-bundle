<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\Formatter;

use Symfony\Component\Intl\Currencies;
use Symfony\Component\Intl\Exception\MissingResourceException;

class CurrencyFormatter
{
    public const DEFAULT_DECIMAL_SCALE = 5;

    public function __construct(private string $defaultLocale)
    {
    }

    public function getSymbol(string $currencyCode, ?string $locale): string
    {
        try {
            return Currencies::getSymbol($currencyCode, $locale ?: $this->defaultLocale);
        } catch (MissingResourceException $e) {
            return $currencyCode;
        }
    }
}
