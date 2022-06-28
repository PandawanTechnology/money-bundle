<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\DependencyInjection\CompilerPass;

use PandawanTechnology\MoneyBundle\Doctrine\DBAL\Types\AmountType;
use PandawanTechnology\MoneyBundle\Doctrine\DBAL\Types\CurrencyType;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterDoctrineTypePass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('doctrine.dbal.connection_factory.types')) {
            return;
        }

        $typeDefinition = $container->getParameter('doctrine.dbal.connection_factory.types');

        if (!isset($typeDefinition['amount'])) {
            $typeDefinition['amount'] = ['class' => AmountType::class];
        }

        if (!isset($typeDefinition['currency'])) {
            $typeDefinition['currency'] = ['class' => CurrencyType::class];
        }

        $container->setParameter('doctrine.dbal.connection_factory.types', $typeDefinition);
    }
}
