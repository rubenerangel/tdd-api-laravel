<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FilterArticlesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function can_filter_articles_by_title(): void
    {
        Article::factory()->create([
            'title' => 'Learn Laravel from the Beginner',
        ]);

        Article::factory()->create([
            'title' => 'Learn MVC from the Beginner',
        ]);

        // articles?filter[title]=Laravel

        $url = route('api.v1.articles.index', [
            'filter' => [
                'title' => 'Laravel',
            ]
        ]);

        // dd(urldecode($url));

        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Learn Laravel from the Beginner')
            ->assertDontSee('Learn MVC from the Beginner');
    }

    /**
     * @test
     */
    public function can_filter_articles_by_content(): void
    {
        Article::factory()->create([
            'content' => 'Learn Laravel from the Beginner',
        ]);

        Article::factory()->create([
            'content' => 'Learn MVC from the Beginner',
        ]);

        // articles?filter[content]=Laravel

        $url = route('api.v1.articles.index', [
            'filter' => [
                'content' => 'Laravel',
            ]
        ]);

        // dd(urldecode($url));

        $this->getJson($url)
            // ->dump()
            ->assertJsonCount(1, 'data')
            ->assertSee('Learn Laravel from the Beginner')
            ->assertDontSee('Learn MVC from the Beginner');
    }

    /**
     * @test
     */
    public function can_filter_articles_by_year(): void
    {
        Article::factory()->create([
            'title' => 'Article from 2021',
            'created_at' => now()->year(2021),
        ]);

        Article::factory()->create([
            'title' => 'Article from 2022',
            'created_at' => now()->year(2022),
        ]);

        // articles?filter[year]=2021

        $url = route('api.v1.articles.index', [
            'filter' => [
                'year' => '2021',
            ]
        ]);

        // dd(urldecode($url));

        $this->getJson($url)
            // ->dump()
            ->assertJsonCount(1, 'data')
            ->assertSee('Article from 2021')
            ->assertDontSee('Article from 2022');
    }

    /**
     * @test
     */
    public function can_filter_articles_by_month(): void
    {
        Article::factory()->create([
            'title' => 'Article from month 3',
            'created_at' => now()->month(3),
        ]);

        Article::factory()->create([
            'title' => 'Another Article from month 3',
            'created_at' => now()->month(3),
        ]);

        Article::factory()->create([
            'title' => 'Article from month 1',
            'created_at' => now()->month(1),
        ]);

        // articles?filter[month]=3

        $url = route('api.v1.articles.index', [
            'filter' => [
                'month' => '3',
            ]
        ]);

        // dd(urldecode($url));

        $this->getJson($url)
            // ->dump()
            ->assertJsonCount(2, 'data')
            ->assertSee('Article from month 3')
            ->assertSee('Another Article from month 3')
            ->assertDontSee('Article from month 1');
    }

    /**
     * @test
     */
    public function cannot_filter_articles_by_unknown_filters(): void
    {
        Article::factory()->count(2)->create();

        // articles?filter[unknown]=filter

        $url = route('api.v1.articles.index', [
            'filter' => [
                'unknown' => 'filter',
            ]
        ]);

        // dd(urldecode($url));

        $this->getJson($url)
            // ->dump()
            ->assertStatus(400);
    }
}
