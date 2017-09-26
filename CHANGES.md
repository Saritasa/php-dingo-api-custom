# Changes History

1.0.14
------
PagingInfo checks existence of keys in input array

1.0.13
------
Fix cursor pagination for models, loaded with relations

1.0.12
------
Do not substitute key in CursorQueryBuilder
Handle Illuminate\Database\Query\Builder correctly

1.0.11
------
Add CursorQueryBuilder for arbitrary queries

1.0.10
------
Improved cursor pagination to search row number in result set for paginate custom ordered query

1.0.9
-----
Proper handling for Saritasa\Exceptions\PermissionsException to produce 403 error

1.0.8
-----
Fixes and improvements for CursorResultAuto

1.0.7
-----
Try parse cursor value as int, if possible

1.0.6
-----
Improved cursor pagination

1.0.5
-----
Fix namespace in DingoApiFractalAdapter

1.0.4
-----
Weeker typing requiredments for paging
Remove duplicates of BaseApiController

1.0.3
-----
Fix namespaces

1.0.2
-----
Add default pagination limits config

1.0.1
-----
Fix Dingo/Api service provider registration

1.0.0
-----
Initial version: Custom patches for Dingo API and error handler
