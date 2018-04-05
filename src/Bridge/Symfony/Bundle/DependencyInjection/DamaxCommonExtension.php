<?php

declare(strict_types=1);

namespace Damax\Common\Bridge\Symfony\Bundle\DependencyInjection;

use Damax\Common\Bridge\Symfony\Bundle\Listener\DomainEventListener;
use Damax\Common\Domain\DomainEventPublisher;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class DamaxCommonExtension extends Extension
{
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['DoctrineBundle'])) {
            $loader->load('doctrine.xml');
        }

        if (isset($bundles['SimpleBusEventBusBundle'])) {
            $container
                ->register(DomainEventPublisher::class)
                ->addArgument(new Reference('simple_bus.event_bus.aggregates_recorded_messages'))
                ->addArgument(new Reference('event_bus'))
            ;
        } else {
            $container->removeDefinition(DomainEventListener::class);
        }
    }
}
