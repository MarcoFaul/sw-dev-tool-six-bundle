<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sw_dev_tool_six');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('access_token_ttl')->defaultValue('PT10M')->info('Default is PT10M')->end()
                ->booleanNode('enable_dal_caching')->defaultFalse()->info('Disable the DAL entity searcher cache')->end()
            ->arrayNode('shopware')
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('skipFirstRunWizardClient')->defaultTrue()->info('@TODO')->end()
                        ->scalarNode('disableAutoUpdate')->defaultTrue()->info('@TODO')->end()
                        ->scalarNode('disableApiAuthRequire')->defaultTrue()->info('@TODO')->end()
                        ->scalarNode('disableCSRF')->defaultTrue()->info('@TODO')->end()
                    ->end()
                ->end()
            ->end()

            ->end();
        return $treeBuilder;
    }
}
