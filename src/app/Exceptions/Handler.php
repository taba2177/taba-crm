<?php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
        public function register()
    {
        $this->renderable(function (Throwable $e, $request) {
            if ($request->header('X-Livewire')) {
                // You can return a custom response for Livewire requests here
                return response()->json([
                    'message' => 'Something went wrong. Please refresh the page.',
                ], 500);
            }
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($this->isHttpException($exception)) {
        $statusCode = $exception->getStatusCode();
        $customViews = [404, 500, 419, 503];

        if (in_array($statusCode, $customViews)) {
            return response()->view("errors.{$statusCode}", [], $statusCode);
        }
    }


        return parent::render($request, $exception);
    }
}
