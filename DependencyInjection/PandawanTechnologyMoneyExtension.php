<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\DependencyInjection;

use PandawanTechnology\Money\ConfigurationLoader\CurrencyConfigurationsLoaderInterface;
use PandawanTechnology\MoneyBundle\ConfigurationLoader\FilteredCurrencyConfigurationsLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class PandawanTechnologyMoneyExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('pandawan_technology.money.default_currency', $config['default_currency']);
        $container->setParameter('pandawan_technology.money.default_locale', $config['default_locale']);
        $container->setParameter('pandawan_technology.money.enabled_currencies', $config['enabled_currencies']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $container->setAlias(CurrencyConfigurationsLoaderInterface::class, FilteredCurrencyConfigurationsLoader::class);
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        if (!$container->hasExtension('doctrine')) {
            return;
        }

        $container->prependExtensionConfig('doctrine', [
            'orm' => [
                'mappings' => [
                    'PandawanTechnologyMoneyBundle' => [
                        'prefix' => 'PandawanTechnology\Money\Model',
                    ],
                ],
            ],
        ]);
    }
}
