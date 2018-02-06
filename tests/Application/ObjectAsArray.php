<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Application;

use ArrayAccess;
use Damax\Common\Application\AsArrayTrait;

class ObjectAsArray implements ArrayAccess
{
    use AsArrayTrait;

    /**
     * @var string
     */
    public $fooField;

    /**
     * @var int
     */
    public $barField;

    /**
     * @var ObjectAsArray
     */
    public $bazField;
}
