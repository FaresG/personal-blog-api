<?php

namespace App\Exceptions;

use BadMethodCallException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
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

    public function render($request, Throwable $e)
    {
        if ($e instanceof MissingAbilityException) {
            $abilities = collect($e->abilities());

            if ($abilities->contains('manage-users')) {
                return response()->json([
                    'message' => 'Only Admins can manage users',
                ], Response::HTTP_FORBIDDEN);
            }
            else if ($abilities->contains('server-update')) {
                return response()->json([
                    'message' => 'You cannot update the server.',
                ], Response::HTTP_FORBIDDEN);
            }
        }

        if ($e instanceof TooManyRequestsHttpException) {
            return response()->json([
                'message' => 'Too many requests, retry later!',
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Not Found!',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($e instanceof RouteNotFoundException) {
            return response()->json([
                'message' => 'Something wrong with the request headers. Check Accept header',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Could not fetch result from the database.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'message' => $e->getMessage()
        ], 400);
    }
}
