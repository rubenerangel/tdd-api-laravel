<?php

namespace Tests\Feature\Articles;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateArticleTest extends TestCase
{
    use RefreshDatabase;

    // protected function setUp(): void
    // {
    //     parent::setUp();


    // }

    /**
     * @test
     */
    public function can_create_articles(): void
    {
        // $this->withoutExceptionHandling();

        $response = $this->postJson(route('api.v1.articles.store'), [
            // 'data' => [
                // 'type' => 'articles',
                // 'attributes' => [
                    'title' => 'New Article',
                    'slug' => 'new-article',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                // ],
            // ],
        ])
            // ->dump()
            ->assertCreated();

        // $response->assertCreated();

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

        // $response = $this->postJson(route('api.v1.articles.store'), [
        $this->postJson(route('api.v1.articles.store'), [
            // 'data' => [
                // 'type' => 'articles',
                // 'attributes' => [
                    // 'title' => 'New article',
                    'slug' => 'new-article',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                // ],
            // ],
        ])/* ->dump() */->assertJsonApiValidationErrors('title');

        // $response->assertJsonStructure([
        //     'errors' => [
        //         ['title', 'detail', 'source' => ['pointer']]
        //     ]
        //  ])->assertJson nt([
        //     'source' => ['pointer' => '/data/attributes/title'],
        //  ])->assertHeader(
        //     'content-type', 'application/vnd.api+json'
        // )->assertStatus(422);

        // $response->assertJsonValidationErrors('data.attributes.title');
        // $response->assertJsonApiValidationErrors('title');
    }

    /**
     * @test
     */
    public function slug_is_required(): void
    {
        // $this->withoutExceptionHandling();

        // $response = $this->postJson(route('api.v1.articles.store'), [
        $this->postJson(route('api.v1.articles.store'), [
            // 'data' => [
                // 'type' => 'articles',
                // 'attributes' => [
                    'title' => 'New Article',
                    // 'slug' => 'new-article',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                // ],
            // ],
        ])->assertJsonApiValidationErrors('slug');

        // $response->assertJsonApiValidationErrors('slug');
    }

    /**
     * @test
     */
    public function slug_must_be_unique(): void
    {
        // $this->withoutExceptionHandling();
        $article = Article::factory()->create();

        // $response = $this->postJson(route('api.v1.articles.store'), [
        $this->postJson(route('api.v1.articles.store'), [
            'title' => 'New Article',
            'slug' => $article->slug,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ])->assertJsonApiValidationErrors('slug');

        // $response->assertJsonApiValidationErrors('slug');
    }

    /**
     * @test
     */
    public function slug_must_only_contains_letters_numbers_and_dashes(): void
    {
        // $this->withoutExceptionHandling();

        $this->postJson(route('api.v1.articles.store'), [
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
        // $this->withoutExceptionHandling();

        $this->postJson(route('api.v1.articles.store'), [
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
        // $this->withoutExceptionHandling();

        $this->postJson(route('api.v1.articles.store'), [
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
        // $this->withoutExceptionHandling();

        $this->postJson(route('api.v1.articles.store'), [
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

        // $response = $this->postJson(route('api.v1.articles.store'), [
        $this->postJson(route('api.v1.articles.store'), [
            // 'data' => [
                // 'type' => 'articles',
                // 'attributes' => [
                    'title' => 'New Article',
                    'slug' => 'new-article',
                    // 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                // ],
            // ],
        ])->assertJsonApiValidationErrors('content');

        // $response->assertJsonApiValidationErrors('content');
    }

     /**
     * @test
     */
    public function title_must_be_at_least_4_caracters(): void
    {
        // $this->withoutExceptionHandling();

        // $response = $this->postJson(route('api.v1.articles.store'), [
        $this->postJson(route('api.v1.articles.store'), [
            // 'data' => [
                // 'type' => 'articles',
                // 'attributes' => [
                    'title' => 'New',
                    'slug' => 'new-article',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                // ],
            // ],
        ])->assertJsonApiValidationErrors('title');

        // $response->assertJsonApiValidationErrors('title');
    }
}
