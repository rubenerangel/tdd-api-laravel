<?php

namespace Tests;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\ExpectationFailedException;

trait MakesJsonApiRequests
{
    protected bool $formatJsonApiDocument = true;

    protected function setUp(): void
    {
        parent::setUp();

        TestResponse::macro(
            'assertJsonApiValidationErrors',
            $this->assertJsonApiValidationErrors()
        );
    }

    public function withoutJsonApiDocumentFormatting()
    {
        $this->formatJsonApiDocument = false;
    }

    public function json($method, $uri, array $data = [], array $headers = [], $options = 0): TestResponse
    {
        $headers['accept'] = 'application/vnd.api+json';

        // $formattedData['data'] = $data;
        // Recived only the attributes that are needed to make the request

        if ($this->formatJsonApiDocument) {
            $formattedData = $this->getFormattedData($uri, $data);
        }
        // dd(Str::of($uri)->after('api/v1/'));
        // dd($formattedData);

        // return parent::json($method, $uri, $data, $headers, $options);
        // return parent::json($method, $uri, $formattedData, $headers, $options);
        return parent::json($method, $uri, $formattedData ?? $data, $headers, $options);
    }

    public function postJson($uri, array $data = [], array $headers = [], $options = 0): TestResponse
    {
        $headers['content-type'] = 'application/vnd.api+json';

        return parent::postJson($uri, $data, $headers, $options);
    }

    public function patchJson($uri, array $data = [], array $headers = [], $options = 0): TestResponse
    {
        $headers['content-type'] = 'application/vnd.api+json';

        return parent::patchJson($uri, $data, $headers, $options);
    }

    protected function assertJsonApiValidationErrors(): Closure
    {
        return function ($attribute) {
            /** @var TestResponse $this */
            $pointer = Str::of($attribute)->startsWith('data')
                ? "/".str_replace('.', '/', $attribute) // This starts with data?
                : "/data/attributes/{$attribute}";

            try {
                $this->assertJsonFragment([
                    // 'source' => ['pointer' => "/data/attributes/{$attribute}"],
                    'source' => ['pointer' => $pointer],
                ]);
            } catch (ExpectationFailedException $th) {
                // dd($th->getMessage());
                PHPUnit::fail("Failed to find a JSON:API validation error for key: '{$attribute}'"
                    .PHP_EOL.PHP_EOL.$th->getMessage()
                );
            }

            try {
                $this->assertJsonStructure([
                    'errors' => [
                        ['title', 'detail', 'source' => ['pointer']]
                    ]
                ]);
            } catch (ExpectationFailedException $th) {
                // dd($th->getMessage());
                PHPUnit::fail("Failed to find a valid JSON:API error response"
                    .PHP_EOL.PHP_EOL.$th->getMessage()
                );
            }

            $this->assertHeader(
                'content-type', 'application/vnd.api+json'
            )->assertStatus(422);
        };
    }

    public function getFormattedData($uri, array $data)
    {
        $path = parse_url($uri)['path'];
        $type = (string) Str::of($path)->after('api/v1/')->before('/');
        $id = (string) Str::of($path)->after($type)->replace('/', '');
        // $formattedData['data']['attributes'] = $data;
        // $formattedData['data']['type'] = $type = (string)Str::of($uri)->after('api/v1/')->before('/');
        // $formattedData['data']['type'] = $type = (string)Str::of($path)->after('api/v1/')->before('/');
        // $formattedData['data']['id'] = $id = (string)Str::of($uri)->after($type.'/');
        // $formattedData['data']['id'] = $id = (string)Str::of($path)->after($type)->replace('/', '');

        return [
            'data' => array_filter([
                'type' => $type,
                'id' => $id,
                'attributes' => $data,
            ])
        ];
        // dump(array_filter($formattedData['data']));
    }
}
