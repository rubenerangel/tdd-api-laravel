<?php

namespace App\JsonApi\Traits;

use App\JsonApi\Document;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

trait JsonApiResource
{
    abstract public function toJsonApi(): array;

    // public function toArray(Request $request): array
    public function toArray($request): array
    {
        return Document::type($this->getResourceType())
            ->id($this->resource->getRouteKey())
            ->attributes($this->filterAttributes( $this->toJsonApi()))
            ->links([
                'self' => route('api.v1.'.$this->getResourceType().'.show', $this->resource),
            ])
            ->get('data');

        // return [
        //     // 'type' => 'articles',
        //     'type' => $this->getResourceType(),
        //     'id' => (string)$this->resource->getRouteKey(),
        //     'attributes'  => $this->filterAttributes( $this->toJsonApi()),
        //     'links' => [
        //         // 'self' => route('api.v1.articles.show', $this->resource),
        //         'self' => route('api.v1.'.$this->getResourceType().'.show', $this->resource),
        //     ]
        // ];
    }

    // public function toResponse($request)
    public function withResponse($request, $response)
    {
        // return parent::toResponse($request)->withHeaders([
        //     // 'Location' => route('api.v1.articles.show', $this->resource),
        //     'Location' => route('api.v1.'.$this->getResourceType().'.show', $this->resource),
        // ]);
        $response->header(
            'Location',
            route('api.v1.'.$this->getResourceType().'.show', $this->resource)
        );
    }

    public function filterAttributes(array $attributes): array
    {
        return array_filter($attributes, function($value) {
            if (request()->isNotFilled('fields')) {
                return true;
            }

            // $fields = explode(',', request('fields.articles'));
            $fields = explode(',', request('fields.'.$this->getResourceType()));

            // dump($this->getRouteKey());
            if ($value === $this->getRouteKey()) {
                // return in_array('slug', $fields);
                return in_array($this->getRouteKeyName(), $fields);
            }
            // dd(in_array('slug', $fields));

            return $value;
        });
    }

    public static function collection($resource): AnonymousResourceCollection
    {
        $collection = parent::collection($resource);

        // dd($resource->path());

        $collection->with['links'] = ['self' => $resource->path()];

        return $collection;
    }
}
