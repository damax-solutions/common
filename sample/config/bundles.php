<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\WebServerBundle\WebServerBundle::class => ['dev' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class => ['all' => true],

    Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => ['dev' => true, 'test' => true],

    SimpleBus\SymfonyBridge\SimpleBusCommandBusBundle::class => ['all' => true],
    SimpleBus\SymfonyBridge\SimpleBusEventBusBundle::class => ['all' => true],
    SimpleBus\AsynchronousBundle\SimpleBusAsynchronousBundle::class => ['all' => true],
    SimpleBus\SymfonyBridge\DoctrineOrmBridgeBundle::class => ['all' => true],

    Enqueue\Bundle\EnqueueBundle::class => ['all' => true],
    Enqueue\SimpleBus\Bridge\Symfony\Bundle\EnqueueSimpleBusBundle::class => ['all' => true],

    Damax\Common\Bridge\Symfony\Bundle\DamaxCommonBundle::class => ['all' => true],

    Nelmio\ApiDocBundle\NelmioApiDocBundle::class => ['all' => true],
    LongRunning\Bundle\LongRunningBundle\LongRunningBundle::class => ['all' => true],
];
