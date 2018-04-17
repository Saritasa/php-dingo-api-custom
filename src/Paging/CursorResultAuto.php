<?php

namespace Saritasa\DingoApi\Paging;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Cursor result, that tries to automatically detect next, previous cursor values
 */
class CursorResultAuto extends CursorResult
{
    // Row number column name for cursor pagination by query with custom sorting.
    const ROW_NUM_COLUMN = 'query_row_number';

    /**
     * Cursor result, that tries to automatically detect next, previous cursor values
     *
     * @param CursorRequest $cursorRequest Original cursor request
     * @param Collection $items current page items to return
     * @param mixed $prev Cursor value for previous page
     *        (try to detect automatically, based on current cursor value and page size if null)
     * @param mixed $next Cursor value for next page (try to detect automatically as last item ID, if null)
     */
    public function __construct(CursorRequest $cursorRequest, Collection $items, $prev = null, $next = null)
    {
        $current = $cursorRequest->current ?? $this->getKeyOrNull($items->first());
        $count = $items->count();

        if (!$next && $count >= $cursorRequest->pageSize) {
            $next = $this->getKeyOrNull($items->last());
        }

        if ($this->isIntegerKey($current, $items->first())) {
            $current = (int)$cursorRequest->current;
            if ($next) {
                $next = (int)$next;
            }
            $prev = $current ? $current - $cursorRequest->pageSize : null;
            if ($prev < 0) {
                $prev = null;
            }
        }

        parent::__construct($cursorRequest, $items, $prev, $next, $count);
    }

    /**
     * Try to get model row number or key. Return row number column if exists.
     *
     * @param Object|null $model
     *
     * @return string|null
     */
    protected function getKeyOrNull($model): ?string
    {
        if (!$model) {
            return null;
        }
        return $model->{self::ROW_NUM_COLUMN} ?? ($model instanceof Model ? $model->getKey() : null);
    }

    /**
     * Detect, if cursor value is integer or not.
     * Extract key type from model, if possible, otherwise just check cursor value type
     *
     * @param mixed $current Current cursor value
     * @param mixed $model one of items or null
     *
     * @return boolean
     */
    protected function isIntegerKey($current, $model): bool
    {
        if ($model && $model instanceof Model) {
            return !empty($model->{self::ROW_NUM_COLUMN}) || $model->getKeyType() === 'int';
        } elseif ($current !== null && is_numeric($current) || ctype_digit($current)) {
            return true;
        }
        return false;
    }
}
