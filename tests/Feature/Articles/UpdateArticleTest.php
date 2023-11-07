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

        // $response->assertHeader(
        //     'Location',
        //     route('api.v1.articles.show', $article)
        // );

        // $response->assertExactJson([
        $response->assertJsonApiResource($article,
            [
                'title' => 'Update Article',
                'slug' => $article->slug,
                'content' => 'Update content.',
            ]
        );
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
    public function slug_must_only_contains_letters_numbers_and_dashes(): void
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'New Article',
            'slug' => '$%^&*()',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ])->assertJsonApiValidationErrors('slug');
    }

    /**
     * @test
     */
    public function slug_must_not_contain_uderscores(): void
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'New Article',
            'slug' => 'with_underscores',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ])
            // ->dump()
            ->assertSee(
                __(
                    'validation.no_underscores',
                    [
                        'attribute' => 'data.attributes.slug'
                    ]
                )
            )->assertJsonApiValidationErrors('slug');
    }

    /**
     * @test
     */
    public function slug_must_not_start_with_dashes(): void
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'New Article',
            'slug' => '-start-with-dashes',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ])
        // ->dump()
        ->assertSee(
            __(
                'validation.no_starting_with_dashes',
                [
                    'attribute' => 'data.attributes.slug'
                ]
            )
        )->assertJsonApiValidationErrors('slug');
    }

    /**
     * @test
     */
    public function slug_must_not_end_with_dashes(): void
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'New Article',
            'slug' => 'end-with-dashes-',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ])
        // ->dump()
        ->assertSee(
            __(
                'validation.no_ending_with_dashes',
                [
                    'attribute' => 'data.attributes.slug'
                ]
            )
        )->assertJsonApiValidationErrors('slug');
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
