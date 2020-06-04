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
                ->scalarNode('access_token_ttl')->defaultValue('P1W')->info('Password and token ttl value. Default is PT10M')->end()
                ->booleanNode('enable_dal_caching')->defaultFalse()->info('Disable the DAL entity searcher cache')->end()
                ->arrayNode('shopware')
                    ->children()
                        ->scalarNode('run_wizard')->defaultTrue()->info('Disables the wizard')->end()
                        ->scalarNode('auto_update')->defaultFalse()->info('Disables the administration update popup')->end()
                        ->scalarNode('api_auth_require')->defaultFalse()->info('Disable api auth requirement')->end()
                        ->scalarNode('storefront_csrf')->defaultFalse()->info('Disables the Cross-Site-Request-Forgery security on the storefront')->end()
                    ->end()
                ->end()
                ->arrayNode('twig')
                    ->children()
                        ->scalarNode('debug')->defaultTrue()->info('Enable twig debugging')->end()
                    ->end()
                ->end()
            ->end();
        return $treeBuilder;
    }
}
