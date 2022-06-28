<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\Form\Type;

use PandawanTechnology\Money\Manager\CurrencyManager;
use PandawanTechnology\Money\Model\Money;
use PandawanTechnology\MoneyBundle\Formatter\CurrencyFormatter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SimpleMoneyFormType extends AbstractType
{
    public function __construct(private string $defaultCurrencyCode, private CurrencyManager $currencyManager)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => Money::class,
                'html5' => true,
                'currency' => null,
                'amount_options' => [],
                'scale' => null, // will be overridden in normalizer based on selected currency
            ])
            // Only enable allowed currencies. Will be set to default currency if null
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
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', NumberType::class, $options['amount_options'] + [
                'html5' => $options['html5'],
                'attr' => [
                    'step' => 1 / pow(10, $options['scale']),
                ],
            ])
        ;
    }
}
