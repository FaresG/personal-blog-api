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
        // Custom exceptions for API Token's abilities
        if ($e instanceof MissingAbilityException) {
            $abilities = collect($e->abilities());
            $message = $e->getMessage();
            if ($abilities->contains('admin')) {
                $message = 'Only Admins can manage users.';
            }
            else if ($abilities->contains('default')) {
                $message = 'You cannot access this service.';
            }

            return response()->json([
                'message' => $message,
            ], Response::HTTP_FORBIDDEN);
        }

        return parent::render($request, $e);
    }
}
