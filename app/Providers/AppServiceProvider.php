<?php

namespace App\Providers;

use App\Models\Article;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot()
    {
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
    }
}
