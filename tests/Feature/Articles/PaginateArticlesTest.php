<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaginateArticlesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function can_paginate_articles(): void
    {
        $articles = Article::factory()->count(6)->create();

        // articles?page[size]=2&page[number]=2
        $url = route('api.v1.articles.index', [
            'page' => [
                'size' => 2,
                'number' =>2
            ]
        ]);

        // dd(urldecode($url));

        $response = $this->getJson($url);

        $response->assertSee([
            $articles[2]->title,
            $articles[3]->title,
        ]);

        $response->assertDontSee([
            $articles[0]->title,
            $articles[1]->title,
            $articles[4]->title,
            $articles[5]->title,
        ]);

        $response->assertJsonStructure([
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ]
        ]);

        $firstLink = urldecode($response->json('links.first'));
        $lastLink  = urldecode($response->json('links.last'));
        $prevLink = urldecode($response->json('links.prev'));
        $nextLink = urldecode($response->json('links.next'));
        // dd($firstLink);
        $this->assertStringContainsString('page[size]=2', $firstLink);
        $this->assertStringContainsString('page[number]=1', $firstLink);

        $this->assertStringContainsString('page[size]=2', $lastLink);
        $this->assertStringContainsString('page[number]=3', $lastLink);
        // dd($prevLink);
        $this->assertStringContainsString('page[size]=2', $prevLink);
        $this->assertStringContainsString('page[number]=1', $prevLink);
        // dd($nextLink);
        $this->assertStringContainsString('page[size]=2', $nextLink);
        $this->assertStringContainsString('page[number]=3', $nextLink);
    }

    /**
     * @test
     */
    public function can_paginate_and_sort_articles(): void
    {
        Article::factory()->create(['title' => 'C title']);
        Article::factory()->create(['title' => 'A title']);
        Article::factory()->create(['title' => 'B title']);

        // articles?sort=title&page[size]=1&page[number]=2
        $url = route('api.v1.articles.index', [
            'sort' => 'title',
            'page' => [
                'size' => 1,
                'number' =>2
            ]
        ]);

        // dd(urldecode($url));

        $response = $this->getJson($url);

        $response->assertSee([
            'B title'
        ]);

        $response->assertDontSee([
            'A title',
            'C title',
        ]);

        $firstLink = urldecode($response->json('links.first'));
        $lastLink  = urldecode($response->json('links.last'));
        $prevLink  = urldecode($response->json('links.prev'));
        $nextLink  = urldecode($response->json('links.next'));
        // dd($firstLink);
        $this->assertStringContainsString('sort=title', $firstLink);

        $this->assertStringContainsString('sort=title', $lastLink);
        // dd($prevLink);
        $this->assertStringContainsString('sort=title', $prevLink);
        // dd($nextLink);
        $this->assertStringContainsString('sort=title', $nextLink);

        // dd($firstLink);
    }
}
