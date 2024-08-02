<?php

namespace Saritasa\DingoApi\Patches;

use League\Fractal\Serializer\ArraySerializer as DingoArraySerializer;

/**
 * Same as Dingo ArraySerializer, but:
 * - Does not wrap metadata in envelope 'meta'
 * - Does not wrap any content in 'data' envelope (unlike DataArraySerializer, used by default)
 * - Wraps collection in 'results' envelope instead of default 'data' envelope
 */
class CustomArraySerializer extends DingoArraySerializer
{
    public function meta(array $meta): array
    {
        return empty($meta) ? [] : $meta;
    }

    /**
     * Serialize a collection.
     *
     * @param string $resourceKey Key of hashmap, under which this data will appear in response
     * @param array  $data Data to include in response
     *
     * @return array
     */
    public function collection($resourceKey, array $data): array
    {
        return $resourceKey === '' ? $data : [$resourceKey ?: 'results' => $data];
    }
}
