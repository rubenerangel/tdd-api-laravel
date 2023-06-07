<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Middleware\ValidateJsonApiHeaders;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ValidateJsonApiHeadersTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Route::any('test_route', fn() => 'OK')->middleware(ValidateJsonApiHeaders::class);
    }

    /**
     * @test
     */
    public function accept_header_must_be_present_in_all_requests(): void
    {
        $this->get('test_route')->assertStatus(406);

        $this->get('test_route', [
            'accept' => 'application/vnd.api+json'
        ])->assertSuccessful();
    }

    /**
     * @test
     */
    public function content_type_header_must_be_present_on_all_post_request(): void
    {
        // No se envia content-type; FALLA
        $this->post('test_route', [] /** BODY */, /** HEADERS */[
            'accept' => 'application/vnd.api+json'
        ])->assertStatus(415);

        // Se envia content-type; Status 200 OK
        $this->post('test_route', [] /** BODY */, /** HEADERS */[
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertSuccessful();
    }

    /**
     * @test
     */
    public function content_type_header_must_be_present_on_all_patch_request(): void
    {
        // No se envia content-type; FALLA
        $this->patch('test_route', [] /** BODY */, /** HEADERS */[
            'accept' => 'application/vnd.api+json'
        ])->assertStatus(415);

        // Se envia content-type; Status 200 OK
        $this->patch('test_route', [] /** BODY */, /** HEADERS */[
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertSuccessful();
    }

    /**
     * @test
     */
    public function content_type_header_must_be_present_in_responses(): void
    {
        $this->get('test_route', [
            'accept' => 'application/vnd.api+json'
        ])->assertHeader('content-type', 'application/vnd.api+json');

        $this->post('test_route', /*BODY*/ [], /** HEADERS */ [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertHeader('content-type', 'application/vnd.api+json');

        $this->patch('test_route', /*BODY*/ [], /** HEADERS */ [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertHeader('content-type', 'application/vnd.api+json');
    }

    /**
     * @test
     */
    public function cotent_type_header_must_not_be_present_in_empty_responses(): void
    {
        Route::any('empty_response', fn() => response()->noContent())
            ->middleware(ValidateJsonApiHeaders::class);

        $this->get('empty_response', [
            'accept' => 'application/vnd.api+json'
        ])->assertHeaderMissing('content-type');

        $this->post('empty_response', [], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertHeaderMissing('content-type');

        $this->patch('empty_response', [], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertHeaderMissing('content-type');

        $this->delete('empty_response', [], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertHeaderMissing('content-type');
    }
}
