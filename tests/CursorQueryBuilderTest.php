<?php

namespace Saritasa\LaravelTools\Tests;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\TestCase;
use Saritasa\DingoApi\Paging\CursorQueryBuilder;
use Saritasa\DingoApi\Paging\CursorRequest;

class CursorQueryBuilderTest extends TestCase
{
    public function testFakeModelQuery(): void
    {
        $dbExpression = \Mockery::mock(Expression::class);

        DB::shouldReceive('raw')
            ->withArgs(['(SELECT @row := 0) as row_id_fake_table'])
            ->andReturn($dbExpression);
        DB::shouldReceive('raw')
            ->withArgs(['(SELECT @row := 0) as row_id_fake_table'])
            ->andReturn($dbExpression);
        $cursorRequestMock = \Mockery::mock(CursorRequest::class);
        $queryMock = \Mockery::mock(Builder::class);

        $tmpQuerySql = str_random();
        $tmpQuery = \Mockery::mock(Builder::class);
        $tmpQuery->shouldReceive('toSql')->withArgs([])->andReturn($tmpQuerySql);

        $queryMock
            ->shouldReceive('cloneWithoutBindings')
            ->withArgs([['where']])
            ->andReturnSelf();
        $queryMock
            ->shouldReceive('crossJoin')
            ->withArgs([$dbExpression])
            ->andReturn($tmpQuery);

        $queryBuilder = new CursorQueryBuilder($cursorRequestMock, $queryMock);

        $query = $queryBuilder->buildQuery();
        dd($query);
    }
}
