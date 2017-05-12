<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Max page size for paginated list results
    |--------------------------------------------------------------------------
    |
    | If any user will to try request list more then maxPageSize results,
    | pageSize will set to maxPageSize value
    |
    */

    'maxPageSize' => env('API_PAGE_SIZE_MAX', 500),

    /*
    |--------------------------------------------------------------------------
    | Default page size for paginated list results
    |--------------------------------------------------------------------------
    |
    | If any user does not specify page size for paginated list, he will get
    | defaultPageSize number of records.
    */

    /**
     * If limit not specified, any list will by limited by this value
     */
    'defaultPageSize' => env('API_PAGE_SIZE_DEFAULT', 30),
];
