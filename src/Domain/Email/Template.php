<?php

declare(strict_types=1);

namespace Damax\Common\Domain\Email;

final class Template
{
    private $subject;
    private $text;
    private $html;

    public function __construct(string $subject, string $text, string $html = null)
    {
        $this->subject = $subject;
        $this->text = $text;
        $this->html = $html;
    }

    public function subject(): string
    {
        return $this->subject;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function html(): ?string
    {
        return $this->html;
    }
}
