<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; // Corrected this import
use Illuminate\Validation\ValidationException; // Added for better validation responses

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
    // 1. Enables Sanctum's ability to guard API routes using Cookies/CSRF
        $middleware->statefulApi();
        
        // 2. We keep this empty. 
        // Your Next.js app should fetch the CSRF cookie before posting to login/register.
        $middleware->validateCsrfTokens(
            except: [
                // 'api/login', 
                // 'api/register'
                ]
        );

        $middleware->api(prepend: [
            \App\Http\Middleware\AppendJwtFromCookie::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        
        // Handle Validation Errors Specifically (so you get the "errors" array)
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'The given data was invalid.',
                    'errors'  => $e->errors(),
                ], 422);
            }
        });

        // Your Catch-all Handler
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/*')) {

                // 1. Determine Status Code
                $status = 500;
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                    $status = $e->getStatusCode();
                } elseif ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    $status = 401;
                } elseif ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                    $status = 403;
                }

                // 2. Build Response
                return response()->json([
                    'status'  => 'error',
                    'message' => ($status == 500 && !config('app.debug'))
                        ? 'An internal server error occurred.'
                        : $e->getMessage(),
                    'debug'   => config('app.debug') ? [
                        'exception' => get_class($e),
                        'file'      => $e->getFile(),
                        'line'      => $e->getLine(),
                        'trace'     => array_slice($e->getTrace(), 0, 5) 
                    ] : null
                ], $status);
            }
        });
    })->create();