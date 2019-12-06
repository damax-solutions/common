<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Domain\Email;

use Damax\Common\Domain\Email\Template;
use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_template(): void
    {
        $template = new Template('Subject', 'Text body', 'HTML body');

        $this->assertEquals('Subject', $template->subject());
        $this->assertEquals('Text body', $template->text());
        $this->assertEquals('HTML body', $template->html());
    }
}
