<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // X-Content-Type-Options
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-Frame-Options
        $response->headers->set('X-Frame-Options', 'DENY');

        // X-XSS-Protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer-Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Content Security Policy
        if (!app()->environment('local')) {
            $csp = "default-src 'self'; " .
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://js.stripe.com https://www.paypal.com; " .
                   "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
                   "font-src 'self' https://fonts.gstatic.com; " .
                   "img-src 'self' data: https: http:; " .
                   "connect-src 'self' https://api.stripe.com https://www.paypal.com; " .
                   "frame-src 'self' https://js.stripe.com https://www.paypal.com;";
            
            $response->headers->set('Content-Security-Policy', $csp);
        }

        // Strict-Transport-Security (only for HTTPS)
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // Permissions-Policy
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        return $response;
    }
}