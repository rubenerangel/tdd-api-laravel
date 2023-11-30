<?php

namespace Tests\Feature\Articles;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IncludeCategoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function can_include_related_category_of_an_article(): void
    {
        $article = Article::factory()->create();

        $url = route('api.v1.articles.show', [
            'article' => $article,
            'include' => 'category'
        ]);

        $this->getJson($url)->assertJson([
            // 'data'
            'included' => [
                [
                    'type' => 'categories',
                    'id' => $article->category->getRouteKey(),
                    'attributes' => [
                        'name' => $article->category->name
                    ]
                ]
            ]

        ])/* ->dump() */;

        // dd(urldecode($url));
    }

    /**
     * @test
     */
    public function can_include_related_categories_of_multiple_articles(): void
    {

        // Article::factory()->count(13)->create();

        $article = Article::factory()->create()->load('category');
        $article2 = Article::factory()->create()->load('category');

        $url = route('api.v1.articles.index', [
            'include' => 'category'
        ]);

        // DB::listen(function ($query) {
        //     dump($query->sql);
        // });

        $this->getJson($url)->assertJson([
            // 'data'
            'included' => [
                [
                    'type' => 'categories',
                    'id' => $article->category->getRouteKey(),
                    'attributes' => [
                        'name' => $article->category->name
                    ]
                ],
                [
                    'type' => 'categories',
                    'id' => $article2->category->getRouteKey(),
                    'attributes' => [
                        'name' => $article2->category->name
                    ]
                ],
            ]

        ])/* ->dump() */;

        // dd(urldecode($url));
    }
}
