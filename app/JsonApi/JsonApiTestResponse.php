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
            $pointer = Str::of($attribute)->startsWith('data')
                ? "/".str_replace('.', '/', $attribute) // This starts with data?
                : "/data/attributes/{$attribute}";

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

            $this->assertHeader(
                'content-type', 'application/vnd.api+json'
            )->assertStatus(422);
        };
    }

    public function assertJsonApiResource(): Closure
    {
        return function ($model, $attributes) {
            /** @var TestResponse $this */
            $this->assertJson([
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
        };
    }
}
