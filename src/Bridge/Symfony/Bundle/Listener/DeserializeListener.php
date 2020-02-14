<?php

declare(strict_types=1);

namespace Damax\Common\Bridge\Symfony\Bundle\Listener;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Deserialize;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

class DeserializeListener implements EventSubscriberInterface
{
    private const CONTENT_TYPE = 'json';

    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', -16],
        ];
    }

    /**
     * @throws UnprocessableEntityHttpException
     */
    public function onKernelController(ControllerEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->attributes->get('_deserialize')) {
            return;
        }

        $contentType = $request->getRequestFormat(null) ?? $request->getContentType();
        if (self::CONTENT_TYPE !== $contentType) {
            return;
        }

        /** @var Deserialize $config */
        $config = $request->attributes->get('_deserialize');

        // Deserialize body into object.
        try {
            $object = $this->serializer->deserialize(
                $request->getContent(),
                $config->className(),
                self::CONTENT_TYPE,
                $config->context()
            );
        } catch (ExceptionInterface $e) {
            throw new UnprocessableEntityHttpException('Invalid json.');
        }

        $request->attributes->set($config->param(), $object);
    }
}
