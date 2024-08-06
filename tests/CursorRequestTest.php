<?php

namespace Saritasa\LaravelTools\Tests;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase;
use Saritasa\DingoApi\Paging\CursorRequest;
use Saritasa\Exceptions\PagingException;

/**
 * Check cursor request.
 */
class CursorRequestTest extends TestCase
{
    const DEFAULT_PAGE_SIZE_VALUE = 29;
    const DEFAULT_CURRENT_VALUE = 0;

    protected function setUp(): void
    {
        parent::setUp();

        // Bind dependency for config repository
        $configRepository = new Repository(['api.defaultLimit' => static::DEFAULT_PAGE_SIZE_VALUE]);
        Container::getInstance()->bind('config', function () use ($configRepository) {
            return $configRepository;
        });
    }

    /**
     * Test that cursor successfully works with empty config.
     *
     * @return void
     */
    public function testCursorRequestConstructorWithDefaults()
    {
        $cursorRequest = new CursorRequest([]);

        $this->assertEquals(self::DEFAULT_CURRENT_VALUE, $cursorRequest->current);
        $this->assertEquals(self::DEFAULT_PAGE_SIZE_VALUE, $cursorRequest->pageSize);
    }

    /**
     * Test that cursor successfully works with partially config.
     *
     * @return void
     */
    public function testCursorRequestConstructorWithHalfConfig()
    {
        $current = 7;

        $cursorRequest = new CursorRequest([CursorRequest::CURRENT => $current]);

        $this->assertEquals($current, $cursorRequest->current);
        $this->assertEquals(self::DEFAULT_PAGE_SIZE_VALUE, $cursorRequest->pageSize);

        $current = '7';

        $cursorRequest = new CursorRequest([CursorRequest::CURRENT => $current]);

        $this->assertEquals($current, $cursorRequest->current);
        $this->assertEquals(self::DEFAULT_PAGE_SIZE_VALUE, $cursorRequest->pageSize);

        $notDefaultPageSize = 17;

        $cursorRequest = new CursorRequest([CursorRequest::PAGE_SIZE => $notDefaultPageSize]);

        $this->assertEquals(self::DEFAULT_CURRENT_VALUE, $cursorRequest->current);
        $this->assertEquals($notDefaultPageSize, $cursorRequest->pageSize);
    }

    /**
     * Test that cursor successfully works with full config values.
     *
     * @return void
     */
    public function testCursorRequestConstructorWithFullConfig()
    {
        $current = 7;
        $notDefaultPageSize = 17;

        $cursorRequest = new CursorRequest([
            CursorRequest::CURRENT => $current,
            CursorRequest::PAGE_SIZE => $notDefaultPageSize,
        ]);

        $this->assertEquals($notDefaultPageSize, $cursorRequest->pageSize);
        $this->assertEquals($current, $cursorRequest->current);
    }

    /**
     * Test toArray implementation.
     *
     * @return void
     */
    public function testToArray()
    {
        $current = 7;
        $notDefaultPageSize = 17;

        $cursorRequest = new CursorRequest([
            CursorRequest::CURRENT => $current,
            CursorRequest::PAGE_SIZE => $notDefaultPageSize,
        ]);

        $arrayValues = $cursorRequest->toArray();
        $expected = [
            CursorRequest::PAGE_SIZE => $notDefaultPageSize,
            CursorRequest::CURRENT => $current,
        ];

        $this->assertEquals($expected, $arrayValues);
    }

    /**
     * Test that only declares getters works.
     *
     * @return void
     */
    public function testInvalidGetter()
    {
        $this->expectException(PagingException::class);

        $cursor = new CursorRequest([]);
        $cursor->items;
    }
}
