<?php

namespace Akamon\Bundle\OAuth2ServerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('akamon_oauth2_server');

        $rootNode
            ->children()

                ->arrayNode('repositories')
                    ->children()
                        ->scalarNode('client')->isRequired()->end()
                        ->scalarNode('access_token')->isRequired()->end()
                        ->scalarNode('refresh_token')->isRequired()->end()
                    ->end()
                ->end()

                ->integerNode('token_lifetime')->isRequired()->end()
                ->scalarNode('user_id_obtainer')->isRequired()->end()

                ->arrayNode('grant_types')
                    ->children()
                        ->arrayNode('password')
                            ->children()
                                ->scalarNode('user_credentials_checker')->isRequired()->end()
                                ->scalarNode('user_id_obtainer')->isRequired()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

            ->end()
        ->end();

        return $treeBuilder;
    }

}
