<?php

declare(strict_types=1);

namespace Damax\Common\Bridge\Symfony\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('damax_common');
        $rootNode
            ->children()
                ->append($this->listenersNode('listeners'))
            ->end()
        ;

        return $treeBuilder;
    }

    private function listenersNode(string $name): ArrayNodeDefinition
    {
        return (new ArrayNodeDefinition($name))
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('serialize')->defaultFalse()->end()
                ->booleanNode('deserialize')->defaultFalse()->end()
                ->booleanNode('pagination')->defaultFalse()->end()
                ->booleanNode('domain_events')->defaultFalse()->end()
            ->end()
        ;
    }
}
