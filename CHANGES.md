# Changes History

3.0.0
-----
+ Declare compatibility with up to Laravel 11
+ Changed dingo/api package to api-ecosystem-for-laravel/dingo-api

2.2.2
-----
Fix for dingo/api [Known issue](https://github.com/dingo/api/wiki/Known-Issues) [#1636](https://github.com/dingo/api/issues/1636) with ScopeFactory binding.

2.2.1
-----
Fix notice in the Saritasa\DingoApi\Exceptions\ValidationException at transforming errors

2.2.0
-----
+ Declare compatibility with Laravel 6.0+
+ Drop PHP 7.0 support and Travis CI testing

2.1.1
-----
If collection resource key explicitly set to empty string, it won't be wrapped into 'resource' envelope.
See [this](https://stackoverflow.com/questions/33454645/dingo-api-remove-data-envelope) for details.

2.1.0
-----
Switched to Dingo/Api 2.1 (which has support of laravel 5.8)

2.0.2
-----
Switched to Dingo/Api 2.0 beta (which contains bugfix in authentication)

2.0.1
-----
+ Resolve issue with CursorRequest initialisation
+ Add CursorRequest unit tests

2.0.0
-----
Switched to Dingo/Api 2.0. Use this version with Laravel 5.5+

1.0.15
------
Add apiRoute helper method to simplify retrieving api routes

1.0.14
------
* PagingInfo checks existence of keys in input array
* Enable Laravel's package discovery https://laravel.com/docs/5.5/packages#package-discovery

1.0.13
------
Fix cursor pagination for models, loaded with relations

1.0.12
------
* Do not substitute key in CursorQueryBuilder
* Handle Illuminate\Database\Query\Builder correctly

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
* Weeker typing requiredments for paging
* Remove duplicates of BaseApiController

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
