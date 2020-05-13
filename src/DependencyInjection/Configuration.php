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
                ->booleanNode('disable_dal_caching')->defaultTrue()->end()
            ->end();

        return $treeBuilder;
    }
}
