<?php

declare(strict_types=1);

namespace App\Application\Dto;

use ArrayAccess;
use Damax\Common\Application\AsArrayTrait;

final class BookCreationDto implements ArrayAccess
{
    use AsArrayTrait;

    /**
     * @var string
     */
    public $authorId;

    /**
     * @var string
     */
    public $title;
}
