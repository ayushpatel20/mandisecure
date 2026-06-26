<?php
/**
 * Vercel Serverless Entry Point for Laravel
 *
 * Sets writable /tmp paths, correct server variables, and then
 * boots the Laravel application via public/index.php.
 */

// ── Writable cache paths (Vercel's filesystem is read-only except /tmp) ──────
$_ENV['APP_SERVICES_CACHE'] = '/tmp/services.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
$_ENV['APP_CONFIG_CACHE']   = '/tmp/config.php';
$_ENV['APP_ROUTES_CACHE']   = '/tmp/routes.php';
$_ENV['VIEW_COMPILED_PATH'] = '/tmp';
$_ENV['CACHE_DRIVER']       = 'array';
$_ENV['SESSION_DRIVER']     = 'cookie';
$_ENV['LOG_CHANNEL']        = 'stderr';

$_SERVER['APP_SERVICES_CACHE'] = '/tmp/services.php';
$_SERVER['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
$_SERVER['APP_CONFIG_CACHE']   = '/tmp/config.php';
$_SERVER['APP_ROUTES_CACHE']   = '/tmp/routes.php';
$_SERVER['VIEW_COMPILED_PATH'] = '/tmp';
$_SERVER['CACHE_DRIVER']       = 'array';
$_SERVER['SESSION_DRIVER']     = 'cookie';
$_SERVER['LOG_CHANNEL']        = 'stderr';

putenv('APP_SERVICES_CACHE=/tmp/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');
putenv('VIEW_COMPILED_PATH=/tmp');
putenv('CACHE_DRIVER=array');
putenv('SESSION_DRIVER=cookie');
putenv('LOG_CHANNEL=stderr');

// ── Fix routing — Symfony uses SCRIPT_NAME to compute the base path ───────────
// Without this override, all routes appear to have base "/api" and 404.
$_SERVER['SCRIPT_NAME']     = '/index.php';
$_SERVER['PHP_SELF']        = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';

// ── Ensure HTTPS scheme is detected correctly on Vercel ───────────────────────
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

// ── Boot Laravel ──────────────────────────────────────────────────────────────
require __DIR__ . '/../public/index.php';
