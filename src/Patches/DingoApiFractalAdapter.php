<?php

namespace Saritasa\DingoApi\Patches;

use App\Models\Support\CursorResult;
use Dingo\Api\Http\Request;
use Dingo\Api\Transformer\Adapter\Fractal;
use Dingo\Api\Transformer\Binding;
use Illuminate\Contracts\Pagination\Paginator as IlluminatePaginator;
use League\Fractal\Manager as FractalManager;
use League\Fractal\TransformerAbstract;

/**
 * Same as Default Dingo\Api adapter, but uses custom serializer,
 * which does not wrap response in additional 'data' and 'meta' envelopes.
 */
class DingoApiFractalAdapter extends Fractal
{
    public function __construct(FractalManager $fractal,
                                $includeKey = 'include',
                                $includeSeparator = ',',
                                $eagerLoading = true)
    {
        $fractal->setSerializer(new CustomArraySerializer());
        parent::__construct($fractal, $includeKey, $includeSeparator, $eagerLoading);
    }

    /**
     * Dingo API has internal support for cursors, but no public API to register it.
     * Fractal Adapter is not designed to override partially (no way to call parent::transform gracefully),
     * so this is a modified copy of original transform() method
     *
     * @param mixed $response
     * @param TransformerAbstract $transformer
     * @param Binding $binding
     * @param Request $request
     * @return array
     */
    public function transform($response, $transformer, Binding $binding, Request $request)
    {
        $this->parseFractalIncludes($request);

        if ($response instanceof CursorResult) {
            $resource = $this->createResource($response->getItems(), $transformer, $parameters = $binding->getParameters());
            $resource->setCursor($response);
        }
        else {
            $resource = $this->createResource($response, $transformer, $parameters = $binding->getParameters());
        }
        // If the response is a paginator then we'll create a new paginator
        // adapter for Laravel and set the paginator instance on our
        // collection resource.
        if ($response instanceof IlluminatePaginator) {
            $paginator = $this->createPaginatorAdapter($response);

            $resource->setPaginator($paginator);
        }

        if ($this->shouldEagerLoad($response)) {
            $eagerLoads = $this->mergeEagerLoads($transformer, $this->fractal->getRequestedIncludes());

            $response->load($eagerLoads);
        }

        foreach ($binding->getMeta() as $key => $value) {
            $resource->setMetaValue($key, $value);
        }

        $binding->fireCallback($resource, $this->fractal);

        $identifier = isset($parameters['identifier']) ? $parameters['identifier'] : null;

        return $this->fractal->createData($resource, $identifier)->toArray();
    }
}
