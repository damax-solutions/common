<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Bridge\Symfony\Bundle\DependencyInjection;

use Damax\Common\Bridge\Symfony\Bundle\DependencyInjection\DamaxCommonExtension;
use Damax\Common\Bridge\Symfony\Bundle\Listener\DomainEventListener;
use Damax\Common\Bridge\Twig\EmailRenderer as TwigEmailRenderer;
use Damax\Common\Doctrine\Dbal\TransactionManager as DbalTransactionManager;
use Damax\Common\Doctrine\Orm\TransactionManager as OrmTransactionManager;
use Damax\Common\Domain\Email\EmailRenderer;
use Damax\Common\Domain\EventPublisher\EventPublisher;
use Damax\Common\Domain\EventPublisher\SimpleBusEventPublisher;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class DamaxCommonExtensionTest extends AbstractExtensionTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->container->setParameter('kernel.bundles', []);
    }

    /**
     * @test
     */
    public function it_registers_doctrine_services()
    {
        $this->container->setParameter('kernel.bundles', ['DoctrineBundle' => true]);

        $this->load();

        $this->assertContainerBuilderHasService('damax.common.transaction_manager.dbal', DbalTransactionManager::class);
        $this->assertContainerBuilderHasService('damax.common.transaction_manager.orm', OrmTransactionManager::class);
    }

    /**
     * @test
     */
    public function it_registers_twig_services()
    {
        $this->container->setParameter('kernel.bundles', ['TwigBundle' => true]);

        $this->load();

        $this->assertContainerBuilderHasService(EmailRenderer::class, TwigEmailRenderer::class);
    }

    /**
     * @test
     */
    public function it_registers_simple_bus_services()
    {
        $this->container->setParameter('kernel.bundles', ['SimpleBusEventBusBundle' => true]);

        $this->load();

        $this->assertContainerBuilderHasService(EventPublisher::class, SimpleBusEventPublisher::class);
        $this->assertContainerBuilderHasService(DomainEventListener::class);
    }

    protected function getContainerExtensions(): array
    {
        return [
            new DamaxCommonExtension(),
        ];
    }
}
