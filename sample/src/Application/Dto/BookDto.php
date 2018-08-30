<?php

declare(strict_types=1);

namespace App\Application\Dto;

use DateTime;

final class BookDto
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $authorId;

    /**
     * @var string
     */
    public $title;

    /**
     * @var DateTime
     */
    public $createdAt;
}
