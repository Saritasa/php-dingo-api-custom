<?php

namespace Saritasa\DingoApi\Paging;

use Saritasa\Exceptions\PagingException;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Cursor-type pagination information.
 * Contains details about items count in page and to which item this cursor points to.
 *
 * @property-read int $pageSize Number of items in current cursor-type page
 * @property-read int $current Position of item from which this cursor starts. Zero-based.
 */
class CursorRequest implements Arrayable
{
    const PAGE_SIZE = 'per_page';
    const CURRENT = 'current';

    const KEYS = ['per_page', 'current'];
    const PROPERTIES = ['pageSize', 'current'];

    /* @var int */
    private $pageSize;

    /* @var int */
    private $current;

    /**
     * Cursor-type pagination information.
     * Contains details about items count in page and to which item this cursor points to.
     *
     * @param array|null $input Associated array with key-value pairs of cursor configuration
     * @see CursorRequest::KEYS for valid configuration keys
     */
    public function __construct(array $input = null)
    {
        $this->current = 0;
        $this->pageSize = config('api.defaultLimit');

        if ($input) {
            if (isset($input[static::CURRENT])) {
                $this->current = ctype_digit($input[static::CURRENT])
                    ? (int)$input[static::CURRENT]
                    : $input[static::CURRENT];
            }
            if (isset($input[static::PAGE_SIZE])) {
                $this->pageSize = $input[static::PAGE_SIZE];
            }
        }
    }

    public function __get($name)
    {
        switch ($name) {
            case 'pageSize':
                return $this->pageSize;
            case static::CURRENT:
                return $this->current;
            default:
                throw new PagingException('Unknown cursor property requested: '.$name);
        }
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            static::PAGE_SIZE => $this->pageSize,
            static::CURRENT => $this->current
        ];
    }
}
