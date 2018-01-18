<?php

namespace Saritasa\DingoApi\Paging;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;
use Saritasa\Database\Eloquent\Utils\Query;

class CursorQueryBuilder
{
    protected $cursorRequest;
    protected $model;

    /**
     * Query builder, that returns full set of data to be paginated
     *
     * @var EloquentBuilder|QueryBuilder $query
     */
    protected $originalQuery;
    protected $idKey = CursorResultAuto::ROW_NUM_COLUMN;

    /**
     * Wrap the query to support cursor pagination with custom sort.
     *
     * @param CursorRequest $cursorRequest Requested cursor parameters
     * @param EloquentBuilder|QueryBuilder $query Query builder, that returns full set of data to be paginated
     */
    public function __construct(CursorRequest $cursorRequest, $query)
    {
        $this->cursorRequest = $cursorRequest;
        $this->model = $query instanceof EloquentBuilder
            ? $query->getModel()
            : new class extends Model {
                protected $table = 'fake_model_table';
                protected $primaryKey = CursorResultAuto::ROW_NUM_COLUMN;
            };
        $this->originalQuery = Query::getBaseQuery($query);
    }

    /**
     * Return
     *
     * @return CursorResult
     */
    public function getCursor(): CursorResult
    {
        $query = $this->buildQuery();
        $items = $query->get();
        return new CursorResultAuto($this->cursorRequest, $items);
    }

    /**
     * Build a query, which will return only one page from original data set, matching cursor parameters
     *
     * @return EloquentBuilder|QueryBuilder
     */
    public function buildQuery()
    {
        $wrappedQuery = $this->wrapWithRowCounter($this->originalQuery);
        $query = $this->getFakeModelQuery($wrappedQuery);

        $query->where($this->idKey, '>', $this->cursorRequest->current)
            ->take($this->cursorRequest->pageSize);
        return $query;
    }

    /**
     * Add statement, which will include number of rows in original data set into query, which returns one page
     *
     * @param EloquentBuilder|QueryBuilder $originalQuery
     * @return QueryBuilder
     */
    protected function wrapWithRowCounter($originalQuery)
    {
        $tmpQuery = $originalQuery->cloneWithoutBindings(['where'])
            ->crossJoin(DB::raw('(SELECT @row := 0) as row_id_fake_table'));

        return DB::table(
            DB::raw("(SELECT *, (@row := @row+1) as {$this->idKey} FROM (" . $tmpQuery->toSql() . ") as t1) as t2")
        )->mergeBindings($originalQuery);
    }

    /**
     * Buld query around model clone without scopes, that may affect query result.
     *
     * @param QueryBuilder $wrappedQuery Query to actually perform
     * @return EloquentBuilder|QueryBuilder
     */
    protected function getFakeModelQuery($wrappedQuery)
    {
        /**
         * Clone of original Eloquent model, which we can modify to apply some hacks to build specific query
         *
         * @var Model $fakeModel
         */
        $fakeModel = new $this->model;
        $fakeModel->setTable(DB::raw("(" . $wrappedQuery->toSql() . ") AS " . $this->model->getTable()));

        /**
         * Query builder
         *
         * @var EloquentBuilder $modelQuery
         */
        $modelQuery = $fakeModel->newQueryWithoutScopes();
        $builder = Query::getBaseQuery($modelQuery);
        $builder->columns = null; // We always select everything from subquery
        $modelQuery->setQuery($builder->cloneWithoutBindings(['select', 'where'])); // remove default bindings
        $modelQuery->mergeBindings($wrappedQuery);
        return $modelQuery;
    }
}
