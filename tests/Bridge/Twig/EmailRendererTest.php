<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Bridge\Twig;

use Damax\Common\Bridge\Twig\EmailRenderer;
use PHPUnit\Framework\TestCase;
use Twig_Environment;
use Twig_Loader_Filesystem;

class EmailRendererTest extends TestCase
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var EmailRenderer
     */
    private $renderer;

    protected function setUp()
    {
        $this->twig = new Twig_Environment(new Twig_Loader_Filesystem([__DIR__]));
        $this->renderer = new EmailRenderer($this->twig);
    }

    /**
     * @test
     */
    public function it_renders_template()
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
