<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateArticleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function can_create_articles(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'New Article',
                    'slug' => 'new-article',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                ],
            ],
        ]);

        $response->assertCreated();

        $article = Article::first();

        $response->assertHeader('Location', route('api.v1.articles.show', $article));

        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attributes' => [
                    'title' => 'New Article',
                    'slug' => 'new-article',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                ],
                'links' => [
                    'self' => route('api.v1.articles.show', $article),
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function title_is_required(): void
    {
        // $this->withoutExceptionHandling();

        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    // 'title' => 'Ne',
                    'slug' => 'new-article',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                ],
            ],
        ])->dump();

        $response->assertJsonStructure([
            'errors' => [
                ['title', 'detail', 'source' => ['pointer']]
            ]
         ])->assertJsonFragment([
            'source' => ['pointer' => '/data/attributes/title'],
         ])->assertHeader(
            'content-type', 'application/vnd.api+json'
        )->assertStatus(422);

        // $response->assertJsonValidationErrors('data.attributes.title');
    }

    /**
     * @test
     */
    public function slug_is_required(): void
    {
        // $this->withoutExceptionHandling();

        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'New Article',
                    // 'slug' => 'new-article',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                ],
            ],
        ]);

        $response->assertJsonValidationErrors('data.attributes.slug');
    }

    /**
     * @test
     */
    public function content_is_required(): void
    {
        // $this->withoutExceptionHandling();

        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'New Article',
                    'slug' => 'new-article',
                    // 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                ],
            ],
        ]);

        $response->assertJsonValidationErrors('data.attributes.content');
    }

     /**
     * @test
     */
    public function title_must_be_at_least_4_caracters(): void
    {
        // $this->withoutExceptionHandling();

        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'New',
                    'slug' => 'new-article',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                ],
            ],
        ]);

        $response->assertJsonValidationErrors('data.attributes.title');
    }
}
