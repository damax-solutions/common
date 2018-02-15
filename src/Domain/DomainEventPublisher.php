<?php

declare(strict_types=1);

namespace Damax\Common\Domain;

use SimpleBus\Message\Bus\MessageBus;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;

class DomainEventPublisher
{
    private $recorder;
    private $eventBus;

    public function __construct(ContainsRecordedMessages $recorder, MessageBus $eventBus)
    {
        $this->recorder = $recorder;
        $this->eventBus = $eventBus;
    }

    public function publish()
    {
        $eventStream = $this->recorder->recordedMessages();

        if (!count($eventStream)) {
            return;
        }

        $this->recorder->eraseMessages();

        foreach ($eventStream as $event) {
            $this->eventBus->handle($event);
        }
    }

    public function discard()
    {
        $this->recorder->eraseMessages();
    }
}
