<?php

namespace Tests\Unit\JsonApi;

use App\JsonApi\Document;
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{
    /**
     * @test
     */
    public function can_create_json_api_documents(): void
    {
        $document = Document::type('articles')
            ->id('article-id')
            ->attributes([
                'title' => 'Article title',
            ])
            ->toArray();

        // dd($document);
        $expected = [
            'data' => [
                'type'       => 'articles',
                'id'         => 'article-id',
                'attributes' => [
                    'title' => 'Article title',
                ]
            ],
        ];

        $this->assertEquals($expected, $document);
    }
}
