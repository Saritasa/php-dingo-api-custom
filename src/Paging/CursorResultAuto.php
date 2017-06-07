<?php

namespace Saritasa\DingoApi\Paging;

use Illuminate\Support\Collection;

/**
 * Cursor result, that tries to automatically detect next, previous cursor values
 */
class CursorResultAuto extends CursorResult
{
    function __construct(CursorRequest $cursorRequest, Collection $items, $prev = null, $next = null, $count = null)
    {
        $current = $cursorRequest->current ?: $this->getKeyOrNull($items->first());
        $count = $items->count();

        if ($next == null && is_int($current) && $count >= $cursorRequest->pageSize) {
            $next = $this->getKeyOrNull($items->last());
        }
        if ($prev == null && is_int($current)) {
            $prev = $current - $cursorRequest->pageSize;
            if ($prev < 0) {
                $prev = null;
            }
        }
        parent::__construct($cursorRequest, $items, $prev, $next, $count);
    }
}