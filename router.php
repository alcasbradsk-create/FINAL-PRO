<?php
/**
 * Router for PHP's built-in server (used by start_server.bat).
 * Blocks the same private files that the .htaccess rules block under Apache,
 * then lets the server handle everything else normally.
 */
$path = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');
$path = str_replace('\\', '/', $path);

$private = preg_match('#^/+(config|includes)\b#i', $path)   // PHP includes and settings
    || preg_match('#/\.#', $path)                           // dotfiles such as .git and .htaccess
    || preg_match('#\.(sql|bat|md|txt)$#i', $path)             // schema dump, launcher, docs
    || preg_match('#^/+router\.php#i', $path);

if ($private) {
    http_response_code(404);
    echo 'Not found';
    return true;
}

return false;
