<?php

namespace Saritasa\DingoApi\Paging;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use League\Fractal\Pagination\Cursor;

/**
 * Result structure for cursor pagination - based on ID of first record, shown on previous and next page.
 *
 * http://fractal.thephpleague.com/pagination/
 */
class CursorResult extends Cursor implements Arrayable
{
    /**
     * @var Collection
     */
    private $items;

    /**
     * Result structure for cursor pagination - based on ID of first record, shown on previous and next page.
     *
     * @param CursorRequest $cursorRequest - requested pagination parameters
     * @param Collection $items - records for selected page
     * @param mixed $prev ID of first record, that should be shown on previous page
     * @param mixed $next ID of first record, that should be shown on next page. If not passed, last record ID + 1.
     * @param integer $count - number of records on current page
     */
    function __construct(CursorRequest $cursorRequest, Collection $items, $prev = null, $next = null, $count = null)
    {
        $current = $cursorRequest->current ?: $this->getKeyOrNull($items->first());
        $count = $items->count();

        if ($next == null && is_int($current) && $count >= $cursorRequest->pageSize) {
            $next = $this->getKeyOrNull($items->last()) + 1;
        }
        if ($prev == null && is_int($current)) {
            $prev = $current - $cursorRequest->pageSize;
            if ($prev < 0) {
                $prev = null;
            }
        }

        parent::__construct($current, $prev, $next, $count);
        $this->items = $items;
    }

    /**
     *
     *
     * @return Collection
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    private function getKeyOrNull($model)
    {
        if ($model != null && $model instanceof Model)
        {
            return $model->getKey();
        }
        return null;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return ['results' => $this->items];
    }
}