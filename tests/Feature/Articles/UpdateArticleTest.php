<?php

namespace Tests\Feature\Articles;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateArticleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function can_update_articles(): void
    {
        // $this->withoutExceptionHandling();
        $article = Article::factory()->create();

        $response = $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Update Article',
            // 'slug' => 'update-article',
            'slug' => $article->slug,
            'content' => 'Update content.',
        ])/* ->dump() */->assertOk();

        $response->assertHeader(
            'Location',
            route('api.v1.articles.show', $article)
        );

        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attributes' => [
                    'title' => 'Update Article',
                    'slug' => $article->slug,
                    'content' => 'Update content.',
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
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'slug' => 'update-article',
            'content' => 'Update content.',
        ])->assertJsonApiValidationErrors('title');
    }

    /**
     * @test
     */
    public function slug_is_required(): void
    {
        // $this->withoutExceptionHandling();
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'content' => 'Update content.',
            'title' => 'Update Article',
        ])->assertJsonApiValidationErrors('slug');
    }



    /**
     * @test
     */
    public function slug_must_be_unique(): void
    {
        // $this->withoutExceptionHandling();
        $article1 = Article::factory()->create();
        $article2 = Article::factory()->create();

        // $response = $this->postJson(route('api.v1.articles.store'), [
        $this->patchJson(route('api.v1.articles.update', $article1), [
            'title' => 'New Article',
            'slug' => $article2->slug,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ])->assertJsonApiValidationErrors('slug');

        // $response->assertJsonApiValidationErrors('slug');
    }

    /**
     * @test
     */
    public function content_is_required(): void
    {
        // $this->withoutExceptionHandling();
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Update Article',
            'slug' => 'update-article',
        ])->assertJsonApiValidationErrors('content');
    }

     /**
     * @test
     */
    public function title_must_be_at_least_4_caracters(): void
    {
        // $this->withoutExceptionHandling();
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Upd',
            'slug' => 'update-article',
            'content' => 'Update content.',
        ])->assertJsonApiValidationErrors('title');
    }
}
