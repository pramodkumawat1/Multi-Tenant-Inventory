<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Helper\ResponseBuilder;
use App\Http\Middleware\AuthGates;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            AuthGates::class,
        ]);
        $middleware->api(prepend: [
            AuthGates::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ModelNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return ResponseBuilder::error('Resource not found.', 404);
            }
        });
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return ResponseBuilder::error('Resource not found.', 404);
            }
        });
        $exceptions->render(function (AuthorizationException|AccessDeniedHttpException $e, $request) {
            if ($request->expectsJson()) {
                return ResponseBuilder::error('You are not authorized to perform this action.', 403);
            }
        });
        $exceptions->render(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return ResponseBuilder::error($e->errors(), 422);
            }
        });
        $exceptions->render(function (Throwable $e, $request) {
            if ($request->expectsJson()) {
                return ResponseBuilder::error($e->getMessage(), 500);
            }
        });
    })
    ->create();
