<?php

namespace App\Http\Resources;

use App\JsonApi\Traits\JsonApiResource;
// use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
// use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ArticleResource extends JsonResource
{
    use JsonApiResource;

    public function toJsonApi() : array {
        return [
            'title'   => $this->resource->title,
            'slug'    => $this->resource->slug,
            'content' => $this->resource->content,
        ];
    }

    public function getIncludes(): array
    {
        return [
            CategoryResource::make($this->resource->category)
        ];
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    // public function toArray(Request $request): array
    // {
    //     return [
    //         // 'type' => 'articles',
    //         'type' => $this->getResourceType(),
    //         'id' => (string)$this->resource->getRouteKey(),
    //         'attributes'  => $this->filterAttributes( $this->toJsonApi()),
    //         'links' => [
    //             // 'self' => route('api.v1.articles.show', $this->resource),
    //             'self' => route('api.v1.'.$this->getResourceType().'.show', $this->resource),
    //         ]
    //     ];
    // }

    // // public function toResponse($request)
    // public function withResponse($request, $response)
    // {
    //     // return parent::toResponse($request)->withHeaders([
    //     //     // 'Location' => route('api.v1.articles.show', $this->resource),
    //     //     'Location' => route('api.v1.'.$this->getResourceType().'.show', $this->resource),
    //     // ]);
    //     $response->header(
    //         'Location',
    //         route('api.v1.'.$this->getResourceType().'.show', $this->resource)
    //     );
    // }

    // public function filterAttributes(array $attributes): array
    // {
    //     return array_filter($attributes, function($value) {
    //         if (request()->isNotFilled('fields')) {
    //             return true;
    //         }

    //         // $fields = explode(',', request('fields.articles'));
    //         $fields = explode(',', request('fields.'.$this->getResourceType()));

    //         // dump($this->getRouteKey());
    //         if ($value === $this->getRouteKey()) {
    //             // return in_array('slug', $fields);
    //             return in_array($this->getRouteKeyName(), $fields);
    //         }
    //         // dd(in_array('slug', $fields));

    //         return $value;
    //     });
    // }
}
