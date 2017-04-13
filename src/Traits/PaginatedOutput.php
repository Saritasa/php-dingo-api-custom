<?php

namespace Saritasa\DingoApi\Traits;

use Saritasa\DingoApi\CursorRequest;
use Saritasa\DingoApi\PagingInfo;
use Illuminate\Http\Request;

const PAGE_SIZE = 'per_page';
const API_PAGE_SIZE_MAX = 'api.maxPageSize';
const API_PAGE_SIZE_DEFAULT = 'api.defaultPageSize';

trait PaginatedOutput
{
    /**
     * Read request paging data
     *
     * If checking value specified, allows only integer positive value equal or less 100 (setting).
     * If value not specified, take the default value from settings.
     *
     * @param Request $request raw request data
     * @return PagingInfo
     */
    protected function readPaging(Request $request): PagingInfo
    {
        $input = $request->only(PagingInfo::KEYS);
        if (isset($input[PAGE_SIZE])) {
            $pageSize = (int)$input[PAGE_SIZE];
            if ($pageSize <= 0) {
                $input[PAGE_SIZE] = config(API_PAGE_SIZE_MAX);
            }
            if ($pageSize > config(API_PAGE_SIZE_MAX)) {
                $input[PAGE_SIZE] = config(API_PAGE_SIZE_MAX);
            }
        }
        return new PagingInfo($input);
    }

    protected function readCursor(Request $request): CursorRequest
    {
        $input = $request->only(CursorRequest::KEYS);
        if (isset($input[PAGE_SIZE])) {
            $pageSize = (int)$input[PAGE_SIZE];
            if ($pageSize <= 0) {
                $input[PAGE_SIZE] = config(API_PAGE_SIZE_DEFAULT);
            }
            if ($pageSize > config(API_PAGE_SIZE_MAX)) {
                $input[PAGE_SIZE] = config(API_PAGE_SIZE_MAX);
            }
        }
        return new CursorRequest($input);
    }
}
