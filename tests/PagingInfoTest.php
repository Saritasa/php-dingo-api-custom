<?php

namespace Saritasa\LaravelTools\Tests;

use PHPUnit\Framework\TestCase;
use Saritasa\DingoApi\Paging\PagingInfo;
use Saritasa\Exceptions\PagingException;

class PagingInfoTest extends TestCase
{
    public function testExceptionWillThrownIfPageLessOne(): void
    {
        $this->expectException(PagingException::class);
        new PagingInfo([
            PagingInfo::PAGE => 0,
            PagingInfo::TOTAL_COUNT => random_int(0, 100),
            PagingInfo::PAGE_SIZE => random_int(0, 100),
        ]);
    }

    public function testExceptionWillThrownIfTotalCountLessZero(): void
    {
        $this->expectException(PagingException::class);
        new PagingInfo([
            PagingInfo::PAGE => random_int(1, 100),
            PagingInfo::TOTAL_COUNT => -1,
            PagingInfo::PAGE_SIZE => random_int(0, 100),
        ]);
    }

    public function testExceptionWillThrownIfPagesSizeLessZero(): void
    {
        $this->expectException(PagingException::class);
        new PagingInfo([
            PagingInfo::PAGE => random_int(1, 100),
            PagingInfo::TOTAL_COUNT => random_int(0, 100),
            PagingInfo::PAGE_SIZE => -1,
        ]);
    }

    public function testResultVariables(): void
    {
        $pageSize = random_int(1, 100);
        $totalCount = random_int(0, 100);
        $page = random_int(1, 100);

        $expectedResult = (int)ceil($totalCount / $pageSize);

        $pagingInfo = new PagingInfo([
            PagingInfo::PAGE => $page,
            PagingInfo::TOTAL_COUNT => $totalCount,
            PagingInfo::PAGE_SIZE => $pageSize,
        ]);
        $this->assertEquals($page, $pagingInfo->page);
        $this->assertEquals($totalCount, $pagingInfo->totalCount);
        $this->assertEquals($pageSize, $pagingInfo->pageSize);
        $this->assertEquals($expectedResult, $pagingInfo->totalPages);
    }

    public function testGetOffset(): void
    {
        $pageSize = random_int(1, 100);
        $totalCount = random_int(0, 100);
        $page = random_int(1, 100);

        $offset = $pageSize *  min($page - 1, 0);

        $pagingInfo = new PagingInfo([
            PagingInfo::PAGE => $page,
            PagingInfo::TOTAL_COUNT => $totalCount,
            PagingInfo::PAGE_SIZE => $pageSize,
        ]);
        $this->assertEquals($offset, $pagingInfo->getOffset());
    }
}
