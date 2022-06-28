<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MoneyFormType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function getParent(): string
    {
        return SimpleMoneyFormType::class;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currency', CurrencyChoiceFormType::class)
        ;
    }
}
