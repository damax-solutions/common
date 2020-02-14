<?php

declare(strict_types=1);

namespace Damax\Common\Bridge\Symfony\Bundle\Listener;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Validate;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidateListener implements EventSubscriberInterface
{
    private const CONTENT_TYPE = 'json';

    private $serializer;

    private $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', -17],
        ];
    }

    /**
     * @throws BadRequestHttpException
     */
    public function onKernelController(ControllerEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->attributes->get('_validate')) {
            return;
        }

        /** @var Validate $config */
        $config = $request->attributes->get('_validate');

        $object = $request->attributes->get($config->param());

        // Validate object and send problem response on failure.
        $violations = $this->validator->validate($object, null, $config->groups());
        if ($violations->count()) {
            $controller = function () use ($violations) {
                return JsonResponse::fromJsonString(
                    $this->serializer->serialize($violations, self::CONTENT_TYPE),
                    Response::HTTP_BAD_REQUEST,
                    ['Content-Type' => 'application/problem+json']
                );
            };

            $event->setController($controller);
        }
    }
}
