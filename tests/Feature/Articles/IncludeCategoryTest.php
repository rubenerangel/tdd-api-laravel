<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
        $article = Article::factory()->create();
        $article2 = Article::factory()->create();

        $url = route('api.v1.articles.index', [
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
