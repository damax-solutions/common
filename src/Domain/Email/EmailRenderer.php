<?php

declare(strict_types=1);

namespace Damax\Common\Domain\Email;

interface EmailRenderer
{
    public function renderTemplate(string $templatePath, $context = []): Template;
}
