<?php
/**
 * Shared setup loaded by every page: hardened session, security headers,
 * CSRF protection and display helpers.
 */

// Dates and greetings use Philippine time unless APP_TIMEZONE says otherwise.
date_default_timezone_set(getenv('APP_TIMEZONE') ?: 'Asia/Manila');

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
    session_start();
}

if (!headers_sent()) {
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: same-origin');
}

const PASSWORD_MIN_LENGTH = 8;

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

function csrf_valid(): bool
{
    $token = $_POST['csrf_token'] ?? '';
    return is_string($token) && $token !== '' && hash_equals(csrf_token(), $token);
}

function is_logged_in(): bool
{
    return !empty($_SESSION['uid']);
}

/* ---------- Display helpers ---------- */

/** "First Middle Last", skipping an empty middle name. */
function full_name(array $person): string
{
    $parts = [$person['firstname'] ?? '', $person['middlename'] ?? '', $person['lastname'] ?? ''];
    return trim(preg_replace('/\s+/', ' ', implode(' ', $parts)));
}

/** Two-letter initials for avatars. */
function initials(array $person): string
{
    $first = mb_substr(trim($person['firstname'] ?? ''), 0, 1);
    $last = mb_substr(trim($person['lastname'] ?? ''), 0, 1);
    return mb_strtoupper($first . $last) ?: '?';
}

/** Initials avatar with a stable colour per record. */
function avatar(array $person, string $extraClass = ''): string
{
    $tone = 'tone-' . (crc32(($person['firstname'] ?? '') . ($person['lastname'] ?? '')) % 4);
    return '<span class="avatar ' . $tone . ' ' . htmlspecialchars($extraClass) . '" aria-hidden="true">'
        . htmlspecialchars(initials($person)) . '</span>';
}

/** Coloured pill for a faculty status. */
function status_badge(string $value): string
{
    $tones = ['Active' => 'is-green', 'Inactive' => 'is-grey'];
    return '<span class="badge-soft ' . ($tones[$value] ?? '') . '">' . htmlspecialchars($value) . '</span>';
}

/** Format a stored date (YYYY-MM-DD or timestamp) as "Jun 1, 2015". */
function format_date(?string $value, string $format = 'M j, Y'): string
{
    if (!$value) {
        return '';
    }
    $timestamp = strtotime($value);
    return $timestamp ? date($format, $timestamp) : $value;
}

function redirect(string $location): never
{
    header('Location: ' . $location);
    exit();
}
