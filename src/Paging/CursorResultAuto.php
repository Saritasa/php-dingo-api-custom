<?php

namespace Saritasa\DingoApi\Paging;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Cursor result, that tries to automatically detect next, previous cursor values
 */
class CursorResultAuto extends CursorResult
{
    /**
     * Cursor result, that tries to automatically detect next, previous cursor values
     *
     * @param CursorRequest $cursorRequest Original cursor request
     * @param Collection $items current page items to return
     * @param mixed $prev Cursor value for previous page
     *        (try to detect automatically, based on current cursor value and page size if null)
     * @param mixed $next Cursor value for next page (try to detect automatically as last item ID, if null)
     */
    function __construct(CursorRequest $cursorRequest, Collection $items, $prev = null, $next = null)
    {
        $current = $cursorRequest->current ?: $this->getKeyOrNull($items->first());
        $count = $items->count();

        if ($next == null && $count >= $cursorRequest->pageSize) {
            $next = $this->getKeyOrNull($items->last());
        }

        if ($this->isIntegerKey($current, $items->first())) {
            $current = (int)$cursorRequest->current;
            if ($next != null) {
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
     * Try to get model key
     */
    protected function getKeyOrNull($model)
    {
        if ($model != null && $model instanceof Model) {
            return $model->getKey();
        }
        return null;
    }

    /**
     * Detect, if cursor value is integer or not.
     * Extract key type from model, if possible, otherwise just check cursor value type
     *
     * @param mixed $current Current curor value
     * @param mixed $model one of items or null
     * @return bool
     */
    protected function isIntegerKey($current, $model): bool {
        if ($model != null && $model instanceof Model) {
            return $model->getKeyType() == 'int';
        } else {
            if ($current != null && is_numeric($current) || ctype_digit($current)) {
                return true;
            }
        }
        return false;
    }
}