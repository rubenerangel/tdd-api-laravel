<?php

namespace App\Providers;

use App\JsonApi\JsonApiQueryBuilder;
use App\JsonApi\JsonApiTestResponse;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
// use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\ExpectationFailedException;

class JsonApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // TestResponse::macro(
        //     'assertJsonApiValidationErrors',
        //     function ($attribute) {
        //         /** @var TestResponse $this */
        //         $this->assertJsonStructure([
        //             'errors' => [
        //                 ['title', 'detail', 'source' => ['pointer']]
        //             ]
        //          ])->assertJsonFragment([
        //             'source' => ['pointer' => "/data/attributes/{$attribute}"],
        //          ])->assertHeader(
        //             'content-type', 'application/vnd.api+json'
        //         )->assertStatus(422);
        //     });

        // Builder::macro('allowedSorts', function($allowedSorts) {
        //     if (request()->filled('sort')) {
        //         $sortFields = explode(',', request()->input('sort'));

        //         foreach ($sortFields as $sortField) {
        //             $sortDirection = Str::of( $sortField)->startsWith('-')
        //                 ? 'desc'
        //                 : 'asc';
        //             $sortField = ltrim($sortField, '-');

        //             abort_unless(in_array($sortField, $allowedSorts), 400);

        //             $this->orderBy($sortField, $sortDirection);
        //         }
        //     }

        //     return $this;
        // });

        // Builder::macro('jsonPaginate', function() {
        //     return $this->paginate(
        //         $perPage = request('page.size', 15),
        //         $columns = ['*'],
        //         $pageName = 'page[number]',
        //         $page = request('page.number', 1)
        //     )->appends(request()->only('sort','page.size'));
        // });

        Builder::mixin(new JsonApiQueryBuilder());

        // TestResponse::macro(
        //     'assertJsonApiValidationErrors',
        //     function ($attribute) {
        //         /** @var TestResponse $this */
        //         $pointer = Str::of($attribute)->startsWith('data')
        //             ? "/".str_replace('.', '/', $attribute) // This starts with data?
        //             : "/data/attributes/{$attribute}";

        //         try {
        //             $this->assertJsonFragment([
        //                 // 'source' => ['pointer' => "/data/attributes/{$attribute}"],
        //                 'source' => ['pointer' => $pointer],
        //             ]);
        //         } catch (ExpectationFailedException $th) {
        //             // dd($th->getMessage());
        //             PHPUnit::fail("Failed to find a JSON:API validation error for key: '{$attribute}'"
        //                 .PHP_EOL.PHP_EOL.$th->getMessage()
        //             );
        //         }

        //         try {
        //             $this->assertJsonStructure([
        //                 'errors' => [
        //                     ['title', 'detail', 'source' => ['pointer']]
        //                 ]
        //             ]);
        //         } catch (ExpectationFailedException $th) {
        //             // dd($th->getMessage());
        //             PHPUnit::fail("Failed to find a valid JSON:API error response"
        //                 .PHP_EOL.PHP_EOL.$th->getMessage()
        //             );
        //         }

        //         $this->assertHeader(
        //             'content-type', 'application/vnd.api+json'
        //         )->assertStatus(422);
        //     }
        // );

        TestResponse::mixin(new JsonApiTestResponse());
    }
}
