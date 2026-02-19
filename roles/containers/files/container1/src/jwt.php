<?php

const JWT_SECRET = 'TheD0ll3xVauItKey_PR4NK3X';
const JWT_TTL = 3600;

function b64url_encode(string $data): string {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}
function b64url_decode(string $data): string {
    $remainder = strlen($data) % 4;
    if ($remainder) $data .= str_repeat('=', 4 - $remainder);
    return base64_decode(strtr($data, '-_', '+/'));
}

function jwt_sign(array $payload): string {
    $header = ['alg' => 'HS256', 'typ' => 'JWT'];
    $payload['iat'] = time();
    $payload['exp'] = time() + JWT_TTL;

    $h = b64url_encode(json_encode($header, JSON_UNESCAPED_SLASHES));
    $p = b64url_encode(json_encode($payload, JSON_UNESCAPED_SLASHES));
    $sig = hash_hmac('sha256', "$h.$p", JWT_SECRET, true);
    $s = b64url_encode($sig);

    return "$h.$p.$s";
}

function jwt_verify(string $jwt): array {
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) throw new Exception('Malformed token');

    [$h, $p, $s] = $parts;

    $calc = b64url_encode(hash_hmac('sha256', "$h.$p", JWT_SECRET, true));
    if (!hash_equals($calc, $s)) throw new Exception('Bad signature');

    $payload = json_decode(b64url_decode($p), true);
    if (!is_array($payload)) throw new Exception('Bad payload');

    if (!isset($payload['exp']) || time() >= (int)$payload['exp']) {
        throw new Exception('Token expired');
    }

    return $payload;
}

function set_auth_cookie(string $jwt): void {
    setcookie('auth', $jwt, [
        'expires'  => time() + JWT_TTL,
        'path'     => '/',
        'httponly' => false,
        'secure'   => false,
    ]);
}

function clear_auth_cookie(): void {
    setcookie('auth', '', [
        'expires'  => 1,
        'path'     => '/', 
        'httponly' => false,
        'secure'   => false,
    ]);

    unset($_COOKIE['auth']);
}


function get_auth_token(): ?string {
    return $_COOKIE['auth'] ?? null;
}
