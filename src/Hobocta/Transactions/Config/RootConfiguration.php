<?php

namespace Hobocta\Transactions\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class RootConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('root')
            ->children()
                ->arrayNode('database')->isRequired()
                    ->children()
                        ->scalarNode('dbname')->isRequired()->end()
                        ->scalarNode('host')->isRequired()->end()
                        ->integerNode('port')->isRequired()->end()
                        ->scalarNode('username')->isRequired()->end()
                        ->scalarNode('password')->isRequired()->end()
                    ->end()
                ->end()
                ->arrayNode('balance')->isRequired()
                    ->children()
                        ->integerNode('decimals')->isRequired()->min(2)->max(18)->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
