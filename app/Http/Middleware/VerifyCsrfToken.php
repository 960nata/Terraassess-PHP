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
        // Exclude specific routes from CSRF verification if needed
        'api/iot/device-status', // ESP8266 device status updates
        'api/iot/device-status/*', // ESP8266 device status updates
        'groups/get-students/*', // Group management - get students
        'groups/get-groups/*', // Group management - get groups
    ];
}
