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
    public function __construct(
        private string $defaultCurrencyCode,
        private CurrencyManager $currencyManager
    ) {
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
                'currency' => $this->defaultCurrencyCode,
                'scale' => CurrencyFormatter::DEFAULT_DECIMAL_SCALE,
            ])
            // Only enable allowed currencies. Will be set to default currency if null
            ->setAllowedTypes('currency', ['string'])
            ->setAllowedValues('currency', $this->currencyManager->getAllowedCurrencyCodes())

            // Make sure that if a scale has been provided, it does not exceed the maximum length
            ->setAllowedTypes('scale', ['int', 'null'])
            ->setNormalizer('attr', static function (Options $options, array $currentValue) {
                if (isset($currentValue['step'])) {
                    return $currentValue;
                }

                $currentValue['step'] = 1 / pow(10, $options['scale']);

                return $currentValue;
            })
        ;
    }
}
