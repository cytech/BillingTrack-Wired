<?php

use BT\Http\Middleware\AfterMiddleware;
use BT\Http\Middleware\AuthenticateAdmin;
use BT\Http\Middleware\AuthenticateAPI;
use BT\Http\Middleware\AuthenticateClientCenter;
use BT\Http\Middleware\BeforeMiddleware;
use BT\Http\Middleware\CheckForMaintenanceMode;
use BT\Http\Middleware\EncryptCookies;
use BT\Http\Middleware\RedirectIfAuthenticated;
use BT\Http\Middleware\TrimStrings;
use BT\Http\Middleware\TrustProxies;
use BT\Http\Middleware\VerifyCsrfToken;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\ValidatePostSize;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
//        web: __DIR__.'/../routes/web.php',
//        commands: __DIR__.'/../routes/console.php',
//        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(CheckForMaintenanceMode::class);
        $middleware->append(ValidatePostSize::class);
        $middleware->append(TrimStrings::class);
        $middleware->append(TrustProxies::class);
        $middleware->append(BeforeMiddleware::class);
        $middleware->append(AfterMiddleware::class);

        $middleware->alias(['auth' => Authenticate::class,
            'auth.admin' => AuthenticateAdmin::class,
            'auth.clientCenter' => AuthenticateClientCenter::class,
            'auth.basic' => AuthenticateWithBasicAuth::class,
            'auth.api' => AuthenticateAPI::class,
            'bindings' => SubstituteBindings::class,
            'can' => Authorize::class,
            'guest' => RedirectIfAuthenticated::class,
            'throttle' => ThrottleRequests::class,
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class, ]);

        $middleware->appendToGroup(
            'web', [
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                // \Illuminate\Session\Middleware\AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
            ],

        );

        $middleware->appendToGroup('api', [
            'throttle:60,1',
            'bindings',
        ], );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
