<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Vercel Serverless Compatibility
|--------------------------------------------------------------------------
|
| In serverless environments like Vercel, the filesystem is read-only.
| We redirect all runtime cache, service, package, config, route, and
| view paths to the /tmp folder which is writable on Vercel.
|
*/

if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL']) || getenv('VERCEL') || isset($_ENV['LAMBDA_TASK_ROOT']) || isset($_SERVER['LAMBDA_TASK_ROOT'])) {
    $_ENV['APP_SERVICES_CACHE'] = '/tmp/services.php';
    $_ENV['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
    $_ENV['APP_CONFIG_CACHE'] = '/tmp/config.php';
    $_ENV['APP_ROUTES_CACHE'] = '/tmp/routes.php';
    $_ENV['VIEW_COMPILED_PATH'] = '/tmp';
    $_ENV['CACHE_DRIVER'] = 'array';
    $_ENV['SESSION_DRIVER'] = 'cookie';
    $_ENV['LOG_CHANNEL'] = 'stderr';

    $_SERVER['APP_SERVICES_CACHE'] = '/tmp/services.php';
    $_SERVER['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
    $_SERVER['APP_CONFIG_CACHE'] = '/tmp/config.php';
    $_SERVER['APP_ROUTES_CACHE'] = '/tmp/routes.php';
    $_SERVER['VIEW_COMPILED_PATH'] = '/tmp';
    $_SERVER['CACHE_DRIVER'] = 'array';
    $_SERVER['SESSION_DRIVER'] = 'cookie';
    $_SERVER['LOG_CHANNEL'] = 'stderr';
}


/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
