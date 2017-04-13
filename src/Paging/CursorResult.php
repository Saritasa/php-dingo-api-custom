<?php

namespace Saritasa\DingoApi;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\Pagination\Cursor;

class CursorResult extends Cursor implements Arrayable
{
    /**
     * @var Collection
     */
    private $items;

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