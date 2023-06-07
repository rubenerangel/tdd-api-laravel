<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function invalidJson($request, $exception)
    {
        $errors = [];

        // foreach($exception->errors() as $field => $messages) {
        //     $pointer = '/'.str_replace('.', '/', $field);

        //     $errors = [
        //         [
        //             'title' => 'The given data was invalid',
        //             'detail' => $messages[0],
        //             'source' => [
        //                 'pointer' => $pointer,
        //             ]
        //         ]
        //     ];
        // }

        // $errors = collect($exception->errors())
        //     ->map(function($messages, $field){
        //         return [
        //             'title' => 'The given data was invalid',
        //             'detail' => $messages[0],
        //             'source' => [
        //                 'pointer' => '/'.str_replace('.', '/', $field),
        //             ]
        //         ];
        //     })->values();

        // dd($errors);
        // $title = $exception->getMessage();

        return response()->json([
            'errors' => collect($exception->errors())
                ->map(function($messages, $field){
                    return [
                        'title' => 'The given data was invalid',
                        'detail' => $messages[0],
                        'source' => [
                            'pointer' => '/'.str_replace('.', '/', $field),
                        ]
                    ];
                })->values()
        ], 422);
    }
}
