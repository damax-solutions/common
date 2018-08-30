<?php

declare(strict_types=1);

namespace Sample\Application\Dto;

use Damax\Common\Application\AsArrayTrait;

final class BookCreationDto
{
    use AsArrayTrait;

    /**
     * @var string
     */
    public $title;
}
