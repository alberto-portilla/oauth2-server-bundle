<?php

namespace Akamon\Bundle\OAuth2ServerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class AkamonOAuth2ServerExtension extends Extension
{
    public function getAlias()
    {
        return 'akamon_oauth_server';
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $container->setAlias('akamon.oauth2.server.client_repository', $config['repositories']['client']);
        $container->setAlias('akamon.oauth2.server.access_token_repository', $config['repositories']['access_token']);
        $container->setAlias('akamon.oauth2.server.refresh_token_repository', $config['repositories']['refresh_token']);

        $builderDef = $container->getDefinition('akamon.oauth2.server.server_builder');
        $builderDef->addArgument(['lifetime' => $config['token_lifetime']]);
        //$builderDef->addArgument(new Reference($config['user_id_obtainer']));

        if (isset($config['grant_types'])) {
            $this->registerGrantTypesConfiguration($config['grant_types'], $container);
        }
    }

    private function registerGrantTypesConfiguration(array $config, ContainerBuilder $container)
    {
        $serverBuilder = $container->getDefinition('akamon.oauth2.server.server_builder');

        $passwordConfigure = function (array $config) use ($serverBuilder, $container) {
            $userCredentialsChecker = new Reference($config['user_credentials_checker']);
            $userIdObtainer = new Reference($config['user_id_obtainer']);

            $serverBuilder->addMethodCall('addResourceOwnerPasswordCredentialsGrant', [$userCredentialsChecker, $userIdObtainer]);
        };

        foreach (['password' => $passwordConfigure] as $key => $configure) {
            if (isset($config[$key])) {
                $configure($config[$key]);
            }
        }
    }
}
