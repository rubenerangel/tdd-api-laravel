<?php

namespace Tests\Unit\JsonApi;

// use Tests\TestCase;
use App\Models\Category;
use App\JsonApi\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{
    // use RefreshDatabase;
    /**
     * @test
     */
    public function can_create_json_api_documents(): void
    {
        // $category = Category::factory()->create();
        $category = Mockery::mock('Category', function ($mock) {
            $mock->shouldReceive('getResourceType')->andReturn('categories');
            $mock->shouldReceive('getRouteKey')->andReturn('category-id');
        });

        $document = Document::type('articles')
            ->id('article-id')
            ->attributes([
                'title' => 'Article title',
            ])->relationships([
                'category' => $category
            ])->toArray();

        // dd($document);
        $expected = [
            'data' => [
                'type'       => 'articles',
                'id'         => 'article-id',
                'attributes' => [
                    'title' => 'Article title',
                ],
                'relationships' => [
                    'category' => [
                        'data' => [
                            'type' => 'categories',
                            'id'   => 'category-id',
                        ]
                    ]
                ],
            ],
        ];

        $this->assertEquals($expected, $document);
    }
}
