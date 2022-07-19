<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\Form\Type;

use PandawanTechnology\Money\Factory\MoneyFactory;
use PandawanTechnology\Money\Manager\CurrencyManager;
use PandawanTechnology\Money\Model\Money;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SimpleMoneyFormType extends AbstractType
{
    public function __construct(
        private CurrencyManager $currencyManager,
        private string $defaultCurrencyCode,
        private MoneyFactory $moneyFactory,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => Money::class,
                'currency' => null,
                'amount_options' => [],
            ])
            ->setAllowedTypes('currency', ['null', 'string'])
            ->setAllowedValues('currency', array_merge([null], $this->currencyManager->getAllowedCurrencyCodes()))
            ->setNormalizer('currency', function (OptionsResolver $optionsResolver, ?string $currency = null) {
                return \is_null($currency) ? $this->defaultCurrencyCode : $currency;
            })
            ->setAllowedTypes('amount_options', ['array'])
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', AmountFormType::class, $options['amount_options'])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (PreSetDataEvent $preSetDataEvent) {
                $formConfig = $preSetDataEvent->getForm()->getConfig();

                if (
                    $preSetDataEvent->getData() ||
                    !$formConfig->getOption('required')
                ) {
                    return;
                }

                $preSetDataEvent->setData($this->moneyFactory->createMoney(0, $formConfig->getOption('currency')));
            })
        ;
    }
}
