<?php

namespace Tests;

use Tests\MakesJsonApiRequests;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, MakesJsonApiRequests;

    // public function getJson($uri, array $headers = [], $options = 0)
    // {
    //     $headers['accept'] = 'application/vnd.api+json';
    //     return $this->json('GET', $uri, [], $headers, $options);
    // }
}
