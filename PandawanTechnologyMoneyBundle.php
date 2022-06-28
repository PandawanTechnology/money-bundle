<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle;

use PandawanTechnology\MoneyBundle\DependencyInjection\CompilerPass\RegisterDoctrineTypePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class PandawanTechnologyMoneyBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterDoctrineTypePass());
    }
}
