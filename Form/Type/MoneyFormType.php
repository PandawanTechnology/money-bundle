<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\Form\Type;

use PandawanTechnology\Money\Factory\MoneyFactory;
use PandawanTechnology\Money\Model\Money;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoneyFormType extends AbstractType
{
    public function __construct(private MoneyFactory $moneyFactory)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'currency_options' => [],
                'amount_options' => [],
                'data_class' => Money::class,
            ]);
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currency', CurrencyChoiceFormType::class, $options['currency_options'])
            ->add('amount', AmountFormType::class, $options['amount_options'])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (PreSubmitEvent $preSubmitEvent) {
                $submittedData = $preSubmitEvent->getData();

                if (!isset($submittedData['amount'], $submittedData['currency'])) {
                    return null;
                }

                $form = $preSubmitEvent->getForm();
                $money = $this->moneyFactory->createMoney(
                    $submittedData['amount'],
                    $submittedData['currency']
                );

                $form->setData($money);
            })
        ;
    }
}
