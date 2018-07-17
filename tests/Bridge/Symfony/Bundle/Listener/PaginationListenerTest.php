<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Bridge\Symfony\Bundle\Listener;

use Damax\Common\Bridge\Symfony\Bundle\Listener\PaginationListener;
use Fig\Link\Link;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Link\EvolvableLinkProviderInterface;
use Psr\Link\LinkInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaginationListenerTest extends TestCase
{
    /**
     * @var UrlGeneratorInterface|MockObject
     */
    private $urlGenerator;

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    protected function setUp()
    {
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber(new PaginationListener($this->urlGenerator));
    }

    /**
     * @test
     */
    public function it_skips_response_modification_when_pager_is_not_set()
    {
        $event = new FilterResponseEvent($this->createHttpKernel(), new Request(), HttpKernelInterface::MASTER_REQUEST, $response = new Response());

        $this->dispatcher->dispatch(KernelEvents::RESPONSE, $event);

        $this->assertNull($response->headers->get('X-Page'));
        $this->assertNull($response->headers->get('X-Per-Page'));
        $this->assertNull($response->headers->get('X-Total-Count'));
        $this->assertNull($response->headers->get('X-Count'));
    }

    /**
     * @test
     */
    public function it_adds_pagination_headers()
    {
        $request = new Request([], [], ['_pager' => $this->createPager()]);

        $event = new FilterResponseEvent($this->createHttpKernel(), $request, HttpKernelInterface::MASTER_REQUEST, $response = new Response());

        $this->dispatcher->dispatch(KernelEvents::RESPONSE, $event);

        $this->assertEquals(3, $response->headers->get('X-Page'));
        $this->assertEquals(5, $response->headers->get('X-Per-Page'));
        $this->assertEquals(30, $response->headers->get('X-Total-Count'));
        $this->assertEquals(5, $response->headers->get('X-Count'));
    }

    /**
     * @test
     */
    public function it_skips_request_attributes_setup_for_sub_request()
    {
        $event = new GetResponseForControllerResultEvent($this->createHttpKernel(), $request = new Request(), HttpKernelInterface::SUB_REQUEST, null);

        $this->dispatcher->dispatch(KernelEvents::VIEW, $event);

        $this->assertNull($request->attributes->get('_pager'));
        $this->assertNull($request->attributes->get('_links'));
    }

    /**
     * @test
     */
    public function it_skips_request_attribute_setup_when_pager_is_not_set()
    {
        $event = new GetResponseForControllerResultEvent($this->createHttpKernel(), $request = new Request(), HttpKernelInterface::MASTER_REQUEST, null);

        $this->dispatcher->dispatch(KernelEvents::VIEW, $event);

        $this->assertNull($request->attributes->get('_pager'));
        $this->assertNull($request->attributes->get('_links'));
    }

    /**
     * @test
     */
    public function it_sets_request_attributes()
    {
        $request = new Request();
        $request->attributes->set('_route', 'page');
        $request->attributes->set('_route_params', ['foo' => 'bar']);

        $pager = $this->createPager();
        $event = new GetResponseForControllerResultEvent($this->createHttpKernel(), $request, HttpKernelInterface::MASTER_REQUEST, $pager);

        $this->urlGenerator
            ->expects($this->exactly(4))
            ->method('generate')
            ->withConsecutive(
                ['page', ['page' => 1, 'foo' => 'bar'], UrlGeneratorInterface::ABSOLUTE_URL],
                ['page', ['page' => 6, 'foo' => 'bar'], UrlGeneratorInterface::ABSOLUTE_URL],
                ['page', ['page' => 2, 'foo' => 'bar'], UrlGeneratorInterface::ABSOLUTE_URL],
                ['page', ['page' => 4, 'foo' => 'bar'], UrlGeneratorInterface::ABSOLUTE_URL]
            )
            ->willReturnOnConsecutiveCalls('page-first', 'page-last', 'page-prev', 'page-next')
        ;

        $this->dispatcher->dispatch(KernelEvents::VIEW, $event);

        $this->assertSame($pager, $request->attributes->get('_pager'));

        /** @var EvolvableLinkProviderInterface $provider */
        $provider = $request->attributes->get('_links');
        $this->assertInstanceOf(EvolvableLinkProviderInterface::class, $provider);

        /** @var Link[] $links */
        $links = array_values($provider->getLinks());
        $this->assertCount(4, $links);

        $this->assertLink('first', 'page-first', $links[0]);
        $this->assertLink('last', 'page-last', $links[1]);
        $this->assertLink('prev', 'page-prev', $links[2]);
        $this->assertLink('next', 'page-next', $links[3]);
    }

    private function assertLink(string $rel, string $href, LinkInterface $link)
    {
        $this->assertEquals([$rel], $link->getRels());
        $this->assertEquals($href, $link->getHref());
    }

    private function createHttpKernel(): HttpKernelInterface
    {
        return $this->createMock(HttpKernelInterface::class);
    }

    private function createPager(): Pagerfanta
    {
        return (new Pagerfanta(new ArrayAdapter(array_fill(0, 30, 1))))
            ->setMaxPerPage(5)
            ->setCurrentPage(3)
        ;
    }
}
