<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * TODO:
 * - add a currency configuration loader
 * - validate `enabled_currencies` values to make sure it references a defined currency.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('pandawan_technology_money');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode->children()
            ->scalarNode('default_currency')
                ->isRequired()
                ->info('The default currency to use when not specified')
            ->end()
            ->scalarNode('default_locale')
                ->isRequired()
                ->info('The default locale to use when not specified')
            ->end()
            ->arrayNode('enabled_currencies')
                ->info('The currencies list to enable. If not set or set to NULL, all existing currencies will be accepted.')
                ->scalarPrototype()->end()
                ->defaultValue([])
            ->end()
        ->end()

        // Make sure that if a list of currencies has been provided that the default currency is enabled
        ->validate()
            ->ifTrue(static function (array $config) {
                $enabledCurrencies = $config['enabled_currencies'];

                if (!\count($enabledCurrencies)) {
                    return false;
                }

                if (\in_array($config['default_currency'], $enabledCurrencies, true)) {
                    return false;
                }

                return !\in_array($config['default_currency'], $enabledCurrencies, true);
            })
                ->thenInvalid('Default currency is not in the list of enabled_currencies')
        ->end()
        ;

        return $treeBuilder;
    }
}
