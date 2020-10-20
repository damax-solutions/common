<?php

declare(strict_types=1);

namespace Damax\Common\Bridge\Symfony\Bundle\Listener;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Serialize;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class SerializeListener implements EventSubscriberInterface
{
    private const CONTENT_TYPE = 'json';

    private const METHOD_TO_CODE = [
        'POST' => Response::HTTP_CREATED,
    ];

    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::VIEW => 'onKernelView'];
    }

    public function onKernelView(ViewEvent $event)
    {
        $config = $event->getRequest()->attributes->get('_serialize');
        $method = $event->getRequest()->getMethod();
        $result = $event->getControllerResult();

        if (!$config instanceof Serialize || $event->getResponse()) {
            return;
        }

        $context = $config->groups() ? ['groups' => $config->groups()] : [];

        $event->setResponse($this->createResponse($method, $context, $result));
    }

    private function createResponse(string $method, array $context, $result): Response
    {
        $json = $this->serializer->serialize($result, self::CONTENT_TYPE, $context);

        if ($result instanceof ConstraintViolationListInterface) {
            $code = Response::HTTP_BAD_REQUEST;
            $type = 'application/problem+json';
        } else {
            $code = self::METHOD_TO_CODE[$method] ?? Response::HTTP_OK;
            $type = 'application/json';
        }

        $response = JsonResponse::fromJsonString($json, $code, ['content-type' => $type]);

        return $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
