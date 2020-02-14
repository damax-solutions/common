<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Bridge\Symfony\Bundle\DependencyInjection;

use Damax\Common\Bridge\Symfony\Bundle\DependencyInjection\Configuration;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * @test
     */
    public function it_processes_empty_config(): void
    {
        $config = [];

        $this->assertProcessedConfigurationEquals([$config], [
            'listeners' => [
                'serialize' => false,
                'deserialize' => false,
                'pagination' => false,
                'domain_events' => false,
                'validate' => false,
            ],
        ]);
    }

    /**
     * @test
     */
    public function it_configures_listeners(): void
    {
        $config = [
            'listeners' => [
                'serialize' => true,
                'deserialize' => true,
                'pagination' => true,
                'domain_events' => true,
                'validate' => true,
            ],
        ];

        $this->assertProcessedConfigurationEquals([$config], [
            'listeners' => [
                'serialize' => true,
                'deserialize' => true,
                'pagination' => true,
                'domain_events' => true,
                'validate' => true,
            ],
        ]);
    }

    protected function getConfiguration(): ConfigurationInterface
    {
        return new Configuration();
    }
}
