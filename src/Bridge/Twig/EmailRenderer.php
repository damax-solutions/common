<?php

declare(strict_types=1);

namespace Damax\Common\Bridge\Twig;

use Damax\Common\Domain\Email\EmailRenderer as EmailRendererInterface;
use Damax\Common\Domain\Email\Template;
use Twig_Environment;

final class EmailRenderer implements EmailRendererInterface
{
    private $twig;

    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function renderTemplate(string $templatePath, $context = []): Template
    {
        $template = $this->twig->load($templatePath);

        $subj = $template->renderBlock('subject', $context);
        $text = $template->renderBlock('text', $context);
        $html = null;

        if ($template->hasBlock('html', $context)) {
            $html = $template->renderBlock('html', $context);
        }

        return new Template($subj, $text, $html);
    }
}
