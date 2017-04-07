<?php

namespace Saritasa\DingoApi;

use Saritasa\Exceptions\PagingException;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Read-only paging cursor description.
 *
 * @property-read int $pageSize
 * @property-read int $current
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

    public function __construct(array $input = null)
    {
        $this->pageSize = config('api.defaultLimit');
        if ($input) {
            $this->current = isset($input[static::CURRENT]) ? $input[static::CURRENT] : 0;
            if ($input[static::PAGE_SIZE]) {
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