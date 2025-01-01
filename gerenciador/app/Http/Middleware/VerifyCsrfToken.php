<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/webhook',
        '/api/ip',
        '/api/ip2',
        '/api/ip3',
        '/api/ip4',
        '/api/ip6',
        '/api/ip7',
    ];
}
