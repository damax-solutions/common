<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Bridge\Symfony\Bundle\DependencyInjection;

use Damax\Common\Bridge\Enqueue\Consumption\Extension\EventPublisherExtension;
use Damax\Common\Bridge\RamseyUuid\UuidNormalizer;
use Damax\Common\Bridge\Symfony\Bundle\DependencyInjection\DamaxCommonExtension;
use Damax\Common\Bridge\Symfony\Bundle\Listener\DeserializeListener;
use Damax\Common\Bridge\Symfony\Bundle\Listener\DomainEventListener;
use Damax\Common\Bridge\Symfony\Bundle\Listener\PaginationListener;
use Damax\Common\Bridge\Symfony\Bundle\Listener\SerializeListener;
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
    public function it_registers_services()
    {
        $this->load();

        $this->assertContainerBuilderHasService(UuidNormalizer::class);
        $this->assertContainerBuilderHasServiceDefinitionWithTag(UuidNormalizer::class, 'serializer.normalizer');
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
        $this->assertContainerBuilderHasService(EventPublisherExtension::class);
        $this->assertContainerBuilderHasServiceDefinitionWithTag(EventPublisherExtension::class, 'enqueue.consumption.extension', ['priority' => -512]);
    }

    /**
     * @test
     */
    public function it_registers_domain_events_listener()
    {
        $this->load([
            'listeners' => ['domain_events' => true],
        ]);

        $this->assertContainerBuilderHasService(DomainEventListener::class);
        $this->assertContainerBuilderHasServiceDefinitionWithTag(DomainEventListener::class, 'kernel.event_subscriber');
    }

    /**
     * @test
     */
    public function it_registers_annotation_listeners()
    {
        $this->load([
            'listeners' => ['serialize' => true, 'deserialize' => true],
        ]);

        $this->assertContainerBuilderHasService(SerializeListener::class);
        $this->assertContainerBuilderHasServiceDefinitionWithTag(SerializeListener::class, 'kernel.event_subscriber');

        $this->assertContainerBuilderHasService(DeserializeListener::class);
        $this->assertContainerBuilderHasServiceDefinitionWithTag(DeserializeListener::class, 'kernel.event_subscriber');
    }

    /**
     * @test
     */
    public function it_registers_pagination_listener()
    {
        $this->load([
            'listeners' => ['pagination' => true],
        ]);

        $this->assertContainerBuilderHasService(PaginationListener::class);
        $this->assertContainerBuilderHasServiceDefinitionWithTag(PaginationListener::class, 'kernel.event_subscriber');
    }

    protected function getContainerExtensions(): array
    {
        return [
            new DamaxCommonExtension(),
        ];
    }
}
