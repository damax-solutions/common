<?php

declare(strict_types=1);

namespace Damax\Common\Bridge\Symfony\Bundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class DamaxCommonExtension extends Extension
{
    public function load(array $config, ContainerBuilder $container)
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
    }
}
