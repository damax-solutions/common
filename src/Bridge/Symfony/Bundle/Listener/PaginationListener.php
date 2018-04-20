<?php

declare(strict_types=1);

namespace Damax\Common\Bridge\Symfony\Bundle\Listener;

use Fig\Link\GenericLinkProvider;
use Fig\Link\Link;
use Pagerfanta\Pagerfanta;
use Psr\Link\EvolvableLinkProviderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
        ];
    }

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $paginator = $event->getControllerResult();

        if (!$event->isMasterRequest() || !$paginator instanceof Pagerfanta) {
            return;
        }

        $attributes = $event->getRequest()->attributes;

        /** @var EvolvableLinkProviderInterface $linkProvider */
        $linkProvider = $attributes->get('_links', new GenericLinkProvider());

        $params = $attributes->get('_route_params', []);

        foreach ($this->getPages($paginator) as $rel => $page) {
            $href = $this->urlGenerator->generate(
                $attributes->get('_route'),
                $params + ['page' => $page],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
            $linkProvider = $linkProvider->withLink(new Link($rel, $href));
        }

        $attributes->set('_links', $linkProvider);
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
