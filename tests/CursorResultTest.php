<?php

namespace Saritasa\LaravelTools\Tests;

use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Saritasa\DingoApi\Paging\CursorRequest;
use Saritasa\DingoApi\Paging\CursorResult;

class CursorResultTest extends TestCase
{
    public function testItems(): void
    {
        $collection = new Collection([
            str_random(),
            str_random(),
            str_random(),
        ]);

        $cursorResult = new CursorResult(new CursorRequest([]), $collection);
        $this->assertEquals($collection, $cursorResult->getItems());
        $this->assertEquals(['results' => $collection], $cursorResult->toArray());
    }
}
