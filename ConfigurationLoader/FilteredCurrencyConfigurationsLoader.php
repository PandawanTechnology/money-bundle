<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\ConfigurationLoader;

use PandawanTechnology\Money\ConfigurationLoader\CurrencyConfigurationsLoader;
use PandawanTechnology\Money\ConfigurationLoader\CurrencyConfigurationsLoaderInterface;

class FilteredCurrencyConfigurationsLoader implements CurrencyConfigurationsLoaderInterface
{
    public function __construct(
        private CurrencyConfigurationsLoader $currencyConfigurationsLoader,
        private array $enabledCurrencies = []
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getCurrenciesConfigurations(): array
    {
        $rawList = $this->currencyConfigurationsLoader->getCurrenciesConfigurations();

        if (!\count($this->enabledCurrencies)) {
            return $rawList;
        }

        return array_filter(
            $rawList,
            function (string $currencyCode) {
                return \in_array($currencyCode, $this->enabledCurrencies, true);
            },
            ARRAY_FILTER_USE_KEY
        );
    }
}
