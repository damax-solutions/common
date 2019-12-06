<?php

declare(strict_types=1);

namespace Damax\Common\Bridge\Symfony\Bundle\Listener;

use Pagerfanta\Pagerfanta;
use Psr\Link\EvolvableLinkProviderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\WebLink\GenericLinkProvider;
use Symfony\Component\WebLink\Link;

class PaginationListener implements EventSubscriberInterface
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['onKernelView', 8],
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    public function onKernelView(ViewEvent $event)
    {
        $paginator = $event->getControllerResult();

        if (!$event->isMasterRequest() || !$paginator instanceof Pagerfanta) {
            return;
        }

        $attributes = $event->getRequest()->attributes;
        $route = $attributes->get('_route');
        $params = $attributes->get('_route_params', []);

        /** @var EvolvableLinkProviderInterface $linkProvider */
        $linkProvider = $attributes->get('_links', new GenericLinkProvider());

        foreach ($this->getPages($paginator) as $rel => $page) {
            $href = $this->urlGenerator->generate($route, $params + ['page' => $page], UrlGeneratorInterface::ABSOLUTE_URL);

            $linkProvider = $linkProvider->withLink(new Link($rel, $href));
        }

        $attributes->set('_links', $linkProvider);
        $attributes->set('_pager', $paginator);
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $paginator = $event->getRequest()->attributes->get('_pager');

        if (!$paginator instanceof Pagerfanta) {
            return;
        }

        $response = $event->getResponse();

        $total = $paginator->getNbResults();
        $count = $total ? $paginator->getCurrentPageOffsetEnd() - $paginator->getCurrentPageOffsetStart() + 1 : 0;

        $response->headers->set('X-Page', $paginator->getCurrentPage());
        $response->headers->set('X-Per-Page', $paginator->getMaxPerPage());
        $response->headers->set('X-Total-Count', $total);
        $response->headers->set('X-Count', $count);
    }

    private function getPages(Pagerfanta $paginator): array
    {
        $page = (int) $paginator->getCurrentPage();
        $last = (int) $paginator->getNbPages();

        $pages = ['first' => 1, 'last' => $last];

        if ($page > 1) {
            $pages['prev'] = $page - 1;
        }
        if ($page < $last) {
            $pages['next'] = $page + 1;
        }

        return $pages;
    }
}
