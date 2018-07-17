<?php

declare(strict_types=1);

namespace Damax\Common\Bridge\Symfony\Bundle\DependencyInjection;

use Damax\Common\Bridge\Symfony\Bundle\Listener\DeserializeListener;
use Damax\Common\Bridge\Symfony\Bundle\Listener\DomainEventListener;
use Damax\Common\Bridge\Symfony\Bundle\Listener\PaginationListener;
use Damax\Common\Bridge\Symfony\Bundle\Listener\SerializeListener;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

final class DamaxCommonExtension extends ConfigurableExtension
{
    public function loadInternal(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['DoctrineBundle'])) {
            $loader->load('doctrine.xml');
        }

        if (isset($bundles['TwigBundle'])) {
            $loader->load('twig.xml');
        }

        if (isset($bundles['SimpleBusEventBusBundle'])) {
            $loader->load('simple-bus.xml');
        }

        if (class_exists(Uuid::class)) {
            $loader->load('ramsey_uuid.xml');
        }

        $this->configureListeners($config['listeners'], $container);
    }

    private function configureListeners(array $config, ContainerBuilder $container): self
    {
        $listeners = [
            'serialize' => SerializeListener::class,
            'deserialize' => DeserializeListener::class,
            'pagination' => PaginationListener::class,
            'domain_events' => DomainEventListener::class,
        ];

        foreach ($listeners as $id => $className) {
            if ($config[$id]) {
                $container->autowire($className)->addTag('kernel.event_subscriber');
            }
        }

        return $this;
    }
}
