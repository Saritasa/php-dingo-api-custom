<?php

namespace Saritasa\DingoApi\Paging;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Saritasa\Exceptions\PagingException;
use Saritasa\Transformers\Traits\SimpleJsonSerialize;

/**
 * Paging Data - information about page size, current page, number of total pages and items count.
 *
 * @property integer $page Selected page in data set
 * @property integer $pageSize Number of records in single page
 * @property-read integer $totalPages Number of pages in data set
 * @property integer $totalCount Total number of records in data set
 */
class PagingInfo implements Arrayable, Jsonable, \JsonSerializable
{
    use SimpleJsonSerialize;

    public const PAGE = 'page';
    public const PAGE_SIZE = 'per_page';
    public const TOTAL_PAGES = 'total_pages';
    public const TOTAL_COUNT = 'total_count';

    private $page = 1;
    private $pageSize = 0;
    private $totalPages = 0;
    private $totalCount = 0;

    const KEYS = [self::PAGE, self::PAGE_SIZE, self::TOTAL_PAGES, self::TOTAL_COUNT];
    const PROPERTIES = ['page', 'pageSize', 'totalPages', 'totalCount'];

    /**
     * Paging Data - information about page size, current page, number of total pages and items count.
     *
     * @param array|null $input
     *
     * @throws PagingException
     */
    public function __construct(array $input = null)
    {
        $this->pageSize = config('api.defaultLimit');
        if ($input) {
            if (isset($input[static::PAGE])) {
                $this->setPage($input[static::PAGE]);
            }
            if (isset($input[static::TOTAL_COUNT])) {
                $this->setTotalCount($input[static::TOTAL_COUNT]);
            }
            if (isset($input[static::PAGE_SIZE])) {
                $this->setPageSize($input[static::PAGE_SIZE]);
            }
        }
        $this->calculateTotalPages();
    }

    public function __get(string $key): int
    {
        if (in_array($key, static::PROPERTIES)) {
            return $this->$key;
        } else {
            throw new PagingException("Unknown property $key requested");
        }
    }

    /**
     * @param int $value
     *
     * @throws PagingException
     */
    public function setPage(int $value): void
    {
        if ($value < 1) {
            throw new PagingException('Page number cannot be less, than 1');
        }
        $this->page = $value;
    }

    /**
     * Set page size.
     *
     * @param int $value Page size to set
     *
     * @throws PagingException
     *
     * @return void
     */
    public function setPageSize(int $value): void
    {
        if ($value < 1) {
            throw new PagingException("Page size cannot be less, than 1");
        }
        // We do not handle config('api.maxLimit') here, because, unlike user input,
        // it's OK, if developer set it programmatically to greater value
        $this->pageSize = $value;
    }

    /**
     * Calculate total pages from total count and page size.
     *
     * @throws PagingException
     *
     * @return void
     */
    public function calculateTotalPages(): void
    {
        if ($this->pageSize < 1) {
            throw new PagingException('Page size cannot be less, than 1');
        }
        $this->totalPages = (int)ceil($this->totalCount / $this->pageSize);
    }

    /**
     * Set total rows count.
     *
     * @param int $value Total rows count to set
     *
     * @throws PagingException
     */
    public function setTotalCount(int $value): void
    {
        if ($value < 0) {
            throw new PagingException("Total items count cannot be less, than 0");
        }
        $this->totalCount = $value;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->pageSize * min($this->page - 1, 0);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            static::PAGE => $this->page,
            static::PAGE_SIZE => $this->pageSize,
            static::TOTAL_PAGES => $this->totalPages,
            static::TOTAL_COUNT => $this->totalCount
        ];
    }
}
