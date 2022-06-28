<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\Form\Type;

use PandawanTechnology\Money\Factory\MoneyFactory;
use PandawanTechnology\Money\Model\Money;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SimpleMoneyFormType extends AbstractType
{
    public function __construct(private MoneyFactory $moneyFactory)
    {
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => Money::class,
                'html5' => true,
                'currency' => null, // If no currency is provided, default currency will be used
                'amount_options' => [],
                'empty_data' => function(FormInterface $form) {
                    return $this->moneyFactory->createMoney(0, $form->getConfig()->getOption('currency'));
                }
            ])
        ;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', NumberType::class, $options['amount_options'] + [
                'html5' => true,
            ])
        ;
    }
}