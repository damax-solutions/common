<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Bridge\Twig;

use Damax\Common\Bridge\Twig\EmailRenderer;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class EmailRendererTest extends TestCase
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var EmailRenderer
     */
    private $renderer;

    protected function setUp(): void
    {
        $this->twig = new Environment(new FilesystemLoader([__DIR__]));
        $this->renderer = new EmailRenderer($this->twig);
    }

    /**
     * @test
     */
    public function it_renders_template(): void
    {
        $template = $this->renderer->renderTemplate('template.twig', [
            'foo' => 'bar',
            'baz' => 'qux',
        ]);

        $this->assertEquals('Subject', $template->subject());
        $this->assertEquals('Text body', $template->text());
        $this->assertEquals('HTML body', $template->html());
    }
}
