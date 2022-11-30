<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\Form\Type;

use PandawanTechnology\Money\Manager\CurrencyManager;
use PandawanTechnology\MoneyBundle\Formatter\CurrencyFormatter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AmountFormType extends AbstractType
{
    public function __construct(private string $defaultCurrencyCode, private CurrencyManager $currencyManager)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getParent(): string
    {
        return NumberType::class;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'html5' => true,
                'currency' => null,
                'scale' => null, // will be overridden in normalizer based on selected currency
            ])
            // Only enable allowed currencies. Will be set to default currency if null
            ->setAllowedTypes('currency', ['null', 'string'])
            ->setAllowedValues('currency', array_merge([null], $this->currencyManager->getAllowedCurrencyCodes()))
            ->setNormalizer('currency', function (OptionsResolver $optionsResolver, ?string $currency = null) {
                return \is_null($currency) ? $this->defaultCurrencyCode : $currency;
            })

            // Make sure that if a scale has been provided, it does not exceed the maximum length
            ->setAllowedTypes('scale', ['int', 'null'])
            ->setAllowedValues('scale', [null] + range(0, CurrencyFormatter::DEFAULT_DECIMAL_SCALE))
            ->setNormalizer('scale', function (OptionsResolver $optionsResolver, ?int $scale = null) {
                if (!\is_null($scale)) {
                    return $scale;
                }

                return $this->currencyManager->getCurrencyPrecision($optionsResolver['currency']);
            })
            ->setNormalizer('attr', function (Options $options, array $currentValue) {
                if (isset($currentValue['step'])) {
                    return $currentValue;
                }

                $currentValue['step'] = 1 / pow(10, $options['scale']);

                return $currentValue;
            })
        ;
    }
}
