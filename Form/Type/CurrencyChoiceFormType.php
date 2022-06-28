<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\Form\Type;

use PandawanTechnology\Money\Manager\CurrencyManager;
use PandawanTechnology\MoneyBundle\Formatter\CurrencyFormatter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrencyChoiceFormType extends AbstractType
{
    public function __construct(private CurrencyManager $currencyManager, private CurrencyFormatter $currencyFormatter)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $enabledCurrencies = $this->currencyManager->getAllowedCurrencyCodes();

        $resolver
            ->setDefaults([
                'use_symbols' => true, // Will use currency symbol if existing
                'choices' => array_combine($enabledCurrencies, $enabledCurrencies),
                'choice_translation_domain' => false,
            ])
            ->setAllowedTypes('use_symbols', ['bool'])
            ->setNormalizer('choice_label', function (Options $options) {
                if (!$options['use_symbols']) {
                    return null;
                }

                return [$this->currencyFormatter, 'getSymbol'];
            })
        ;
    }
}
