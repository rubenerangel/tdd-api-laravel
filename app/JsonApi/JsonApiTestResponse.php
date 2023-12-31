<?php

namespace App\JsonApi;

use Closure;
use Illuminate\Support\Str;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\Assert as PHPUnit;

class JsonApiTestResponse
{
    public function assertJsonApiValidationErrors( ): Closure
    {
        return function ($attribute) {
            /** @var TestResponse $this */
            $pointer = "/data/attributes/{$attribute}";
            // $pointer = Str::of($attribute)->startsWith('data')
            if (Str::of($attribute)->startsWith('data')) {// This starts with data?
                $pointer =  "/".str_replace('.', '/', $attribute);
            } elseif(Str::of($attribute)->startsWith('relationships')) {// This starts with relashionships?
                $pointer =  "/data/".str_replace('.', '/', $attribute).'/data/id';
            }

            try {
                $this->assertJsonFragment([
                    // 'source' => ['pointer' => "/data/attributes/{$attribute}"],
                    'source' => ['pointer' => $pointer],
                ]);
            } catch (ExpectationFailedException $th) {
                // dd($th->getMessage());
                PHPUnit::fail("Failed to find a JSON:API validation error for key: '{$attribute}'"
                    .PHP_EOL.PHP_EOL.$th->getMessage()
                );
            }

            try {
                $this->assertJsonStructure([
                    'errors' => [
                        ['title', 'detail', 'source' => ['pointer']]
                    ]
                ]);
            } catch (ExpectationFailedException $th) {
                // dd($th->getMessage());
                PHPUnit::fail("Failed to find a valid JSON:API error response"
                    .PHP_EOL.PHP_EOL.$th->getMessage()
                );
            }

            return $this->assertHeader(
                'content-type', 'application/vnd.api+json'
            )->assertStatus(422);
        };
    }

    public function assertJsonApiResource(): Closure
    {
        return function ($model, $attributes) {
            /** @var TestResponse $this */
            return $this->assertJson([
                'data' => [
                    'type' => $model->getResourceType(),
                    'id' => (string)$model->getRouteKey(),
                    'attributes' => $attributes,
                    'links' => [
                        // 'self' => url('/api/v1/articles/'.$article->getRouteKey()),
                        'self' => route('api.v1.'.$model->getResourceType().'.show', $model),
                    ]
                ],
            ])->assertHeader(
                'Location',
                route('api.v1.'.$model->getResourceType().'.show', $model)
            );
        };
    }

    public function assertJsonApiResourceCollection(): Closure
    {
        return function ($collections, $attributesKeys) {
            /** @var TestResponse $this */
            // dd($attributesKeys);

            try {
                $this->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'attributes' => $attributesKeys
                        ]
                    ]
                ]);
            } catch (ExpectationFailedException $th) {
                // dd($th->getMessage());
                PHPUnit::fail("Failed to find a valid JSON:API error response"
                    .PHP_EOL.PHP_EOL.$th->getMessage()
                );
            }

            foreach ($collections as $model) {
                $this->assertJsonFragment([
                    'type' => $model->getResourceType(),
                    'id' => (string) $model->getRouteKey(),
                    'links' => [
                        'self' => route('api.v1.'.$model->getResourceType().'.show', $model),
                    ]
                ]);
            }

            return $this;
        };
    }
}
