<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Bridge\Symfony\Bundle\DependencyInjection;

use Damax\Common\Bridge\Symfony\Bundle\DependencyInjection\DamaxCommonExtension;
use Damax\Common\Bridge\Symfony\Bundle\Listener\DomainEventListener;
use Damax\Common\Doctrine\Dbal\TransactionManager as DbalTransactionManager;
use Damax\Common\Doctrine\Orm\TransactionManager as OrmTransactionManager;
use Damax\Common\Domain\DomainEventPublisher;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class DamaxCommonExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @test
     */
    public function it_registers_services()
    {
        $this->container->setParameter('kernel.bundles', [
            'DoctrineBundle' => true,
        ]);

        $this->load([]);

        $this->assertContainerBuilderHasService(DomainEventPublisher::class);
        $this->assertContainerBuilderHasService(DomainEventListener::class);
        $this->assertContainerBuilderHasService('damax.common.transaction_manager.dbal', DbalTransactionManager::class);
        $this->assertContainerBuilderHasService('damax.common.transaction_manager.orm', OrmTransactionManager::class);
    }

    protected function getContainerExtensions(): array
    {
        return [
            new DamaxCommonExtension(),
        ];
    }
}
