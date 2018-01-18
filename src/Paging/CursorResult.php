<?php

namespace Saritasa\DingoApi\Paging;

use Illuminate\Contracts\Support\Arrayable;
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
     * Data items portion, contained in cursor
     *
     * @var Collection
     */
    private $items;

    /**
     * Result structure for cursor pagination - based on ID of first record, shown on previous and next page.
     *
     * @param CursorRequest $cursorRequest - requested pagination parameters
     * @param Collection $items - records for selected page
     * @param mixed $prev Cursor value for previous page
     * @param mixed $next Cursor value for next page
     * @param integer $count - number of records on current page
     */
    public function __construct(
        CursorRequest $cursorRequest,
        Collection $items,
        $prev = null,
        $next = null,
        $count = null
    ) {
        parent::__construct($cursorRequest->current, $prev, $next, $count);
        $this->items = $items;
    }

    /**
     * Data items portion, contained in cursor
     *
     * @return Collection
     */
    public function getItems(): Collection
    {
        return $this->items;
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
