<?php

declare(strict_types=1);

namespace Damax\Common\Domain\EventPublisher;

interface EventPublisher
{
    public function publish(): void;

    public function discard(): void;
}
