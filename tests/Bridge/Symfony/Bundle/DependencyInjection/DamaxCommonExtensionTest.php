<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Bridge\Symfony\Bundle\DependencyInjection;

use Damax\Common\Bridge\Symfony\Bundle\DependencyInjection\DamaxCommonExtension;
use Damax\Common\Bridge\Symfony\Bundle\Listener\DomainEventListener;
use Damax\Common\Domain\DomainEventPublisher;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class DamaxCommonExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @test
     */
    public function it_registers_services()
    {
        $this->load([]);

        $this->assertContainerBuilderHasService(DomainEventPublisher::class);
        $this->assertContainerBuilderHasService(DomainEventListener::class);
    }

    protected function getContainerExtensions(): array
    {
        return [
            new DamaxCommonExtension(),
        ];
    }
}
