<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
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
        return parent::render($request, $e); // TODO: Change the autogenerated stub
    }
}
